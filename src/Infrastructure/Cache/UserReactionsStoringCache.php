<?php

namespace App\Infrastructure\Cache;

use App\Application\DTO\ReactToUserDTO;
use Psr\Cache\InvalidArgumentException;

class UserReactionsStoringCache
{
    private const CACHE_KEY_PREFIX = 'user_reactions_';

    public function __construct(
        private CacheWrapper $cache,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function append(int $userId, ReactToUserDTO $data, int $ttl = 300): void
    {
        $this->cache->append(self::CACHE_KEY_PREFIX . $userId, $data, $ttl);
    }

    /**
     * @throws InvalidArgumentException
     * @return ReactToUserDTO[]
     */
    public function get(int $userId): array
    {
        return $this->cache->get(self::CACHE_KEY_PREFIX . $userId) ?? [];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(int $userId): void
    {
        $this->cache->delete(self::CACHE_KEY_PREFIX . $userId);
    }
}
