<?php

namespace Okapi\CodeTransformer\Core\Cache;


use Okapi\CodeTransformer\Core\Cache\CacheState\EmptyResultCacheState;
use Okapi\CodeTransformer\Core\Cache\CacheState\NoTransformationsCacheState;
use Okapi\CodeTransformer\Core\Cache\CacheState\TransformedCacheState;
use Okapi\CodeTransformer\Core\DI;
use TypeError;

// TODO: docs
class CacheStateFactory
{
    /**
     * Map of cache state types to cache state classes.
     */
    public const CACHE_STATE_MAP = [
        'TransformedCacheState'       => TransformedCacheState::class,
        'EmptyResultCacheState'       => EmptyResultCacheState::class,
        'NoTransformationsCacheState' => NoTransformationsCacheState::class,
    ];

    /**
     * Create cache states from the cache state file.
     *
     * @param array<string, array<string, string>> $cacheStatesArray
     *
     * @return CacheState[]
     */
    public function createCacheStates(array $cacheStatesArray): array
    {
        $cacheStateObjects = [];

        foreach ($cacheStatesArray as $cacheStateArray) {
            // Skip cache states without a type
            if (!isset($cacheStateArray[CacheState::TYPE])) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            // Get type
            $type = $cacheStateArray[CacheState::TYPE];
            unset($cacheStateArray[CacheState::TYPE]);

            // Instantiate cache state
            try {
                /** @var CacheState $cacheState */
                $cacheState = DI::make(self::CACHE_STATE_MAP[$type]);
                // @codeCoverageIgnoreStart
            } catch (TypeError) {
                continue;
            }
            // @codeCoverageIgnoreEnd

            // Validate cache state
            if (!$cacheState->valid($cacheStateArray)) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            // Set cache state data
            $cacheState->setData($cacheStateArray);

            // Add cache state to array
            $cacheStateObjects[$cacheState->originalFilePath] = $cacheState;
        }

        return $cacheStateObjects;
    }
}
