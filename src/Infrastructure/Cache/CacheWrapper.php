<?php

namespace App\Infrastructure\Cache;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class CacheWrapper
{
    public function __construct(
        private CacheItemPoolInterface  $cache,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function set(string $key, mixed $value, int $ttl = 300): bool
    {
        $item = $this->cache->getItem($key);
        $item->set($value);
        $item->expiresAfter($ttl);

        return $this->cache->save($item);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function append(string $key, mixed $value, int $ttl = 300): bool
    {
        $item = $this->cache->getItem($key);
        $data = $item->isHit() ? $item->get() : [];
        $data[] = $value;
        $item->set($data);
        $item->expiresAfter($ttl);

        return $this->cache->save($item);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $key): mixed {
        $item = $this->cache->getItem($key);

        return $item->isHit() ? $item->get() : null;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(string $key): void
    {
        $this->cache->deleteItem($key);
    }
}
