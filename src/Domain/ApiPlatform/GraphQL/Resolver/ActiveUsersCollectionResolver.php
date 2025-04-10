<?php

namespace App\Domain\ApiPlatform\GraphQL\Resolver;

use ApiPlatform\GraphQl\Resolver\QueryCollectionResolverInterface;
use ApiPlatform\State\Pagination\ArrayPaginator;
use App\Domain\Enum\UserType;
use App\Domain\Service\UserService;

class ActiveUsersCollectionResolver implements QueryCollectionResolverInterface
{
    public const int DEFAULT_MAX_RESULTS = 5;

    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        $userType = UserType::tryFrom($context['args']['userType'] ?? '');
        $maxResults = $context['args']['maxResults'] ?? self::DEFAULT_MAX_RESULTS;

        $activeUsers = $this->userService->findActiveUsers($userType);

        return new ArrayPaginator($activeUsers, 0, $maxResults);
    }
}
