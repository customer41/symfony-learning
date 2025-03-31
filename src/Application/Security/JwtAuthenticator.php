<?php

namespace App\Application\Security;

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

class JwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(private readonly JWTEncoderInterface $jwtEncoder) {}

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $jwt = $extractor->extract($request);
        if (!$jwt) {
            throw new UnauthorizedHttpException('Bearer realm="Test jwt auth"', 'Unauthorized');
        }

        try {
            $jwtData = $this->jwtEncoder->decode($jwt);
        } catch (JWTDecodeFailureException $e) {
            $message = ($e->getReason() === JWTDecodeFailureException::EXPIRED_TOKEN) ? 'Expired token' : 'Unauthorized';
            throw new UnauthorizedHttpException('Bearer realm="Test jwt auth"', $message);
        }

        if (!isset($jwtData['email'])) {
            throw new UnauthorizedHttpException('Bearer realm="Test jwt auth"', 'Unauthorized');
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
}