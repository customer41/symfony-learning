<?php

namespace App\Application\Security;

use App\Domain\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JwtOrSimpleTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly JWTEncoderInterface $jwtEncoder,
        private readonly UserService $userService,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $token = $extractor->extract($request);
        if (!$token) {
            throw new UnauthorizedHttpException('Bearer realm="Test cross tokens auth"', 'Unauthorized');
        }

        try {
            $jwtData = $this->jwtEncoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            switch ($e->getReason()) {
                case JWTDecodeFailureException::EXPIRED_TOKEN:
                    throw new UnauthorizedHttpException('Bearer realm="Test cross tokens auth"', 'Expired token');
                case JWTDecodeFailureException::UNVERIFIED_TOKEN:
                    throw new UnauthorizedHttpException('Bearer realm="Test cross tokens auth"', 'Unauthorized');
                case JWTDecodeFailureException::INVALID_TOKEN:
                    return $this->authenticateBySimpleToken($token);
            }
        }

        if (!isset($jwtData['email'])) {
            throw new UnauthorizedHttpException('Bearer realm="Test cross tokens auth"', 'Unauthorized');
        }

        return new SelfValidatingPassport(
            new UserBadge($jwtData['email'], fn() => new AuthUser($jwtData))
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new AccessDeniedHttpException('Access denied');
    }

    private function authenticateBySimpleToken(string $token): Passport
    {
        return new SelfValidatingPassport(
            new UserBadge($token, fn($token) => $this->userService->findUserByToken($token))
        );
    }
}
