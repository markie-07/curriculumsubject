<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PerformanceHelper
{
    /**
     * Get database query count for performance monitoring
     */
    public static function getQueryCount(): int
    {
        return count(DB::getQueryLog());
    }

    /**
     * Start query logging for performance analysis
     */
    public static function startQueryLogging(): void
    {
        DB::enableQueryLog();
    }

    /**
     * Get all executed queries for analysis
     */
    public static function getQueries(): array
    {
        return DB::getQueryLog();
    }

    /**
     * Get memory usage in MB
     */
    public static function getMemoryUsage(): float
    {
        return round(memory_get_usage(true) / 1024 / 1024, 2);
    }

    /**
     * Get peak memory usage in MB
     */
    public static function getPeakMemoryUsage(): float
    {
        return round(memory_get_peak_usage(true) / 1024 / 1024, 2);
    }

    /**
     * Clear all performance-related caches
     */
    public static function clearPerformanceCaches(): bool
    {
        try {
            $cacheKeys = [
                'dashboard_stats',
                'dashboard_recent_activities',
                'curriculum_stats',
                'user_stats',
                'activity_stats'
            ];

            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Error clearing performance caches: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cache hit rate (if using Redis or similar)
     */
    public static function getCacheStats(): array
    {
        try {
            $store = Cache::getStore();
            if (method_exists($store, 'getRedis')) {
                $redis = $store->getRedis();
                $info = $redis->info();
                
                return [
                    'hits' => $info['keyspace_hits'] ?? 0,
                    'misses' => $info['keyspace_misses'] ?? 0,
                    'hit_rate' => $info['keyspace_hits'] > 0 
                        ? round(($info['keyspace_hits'] / ($info['keyspace_hits'] + $info['keyspace_misses'])) * 100, 2)
                        : 0
                ];
            }
        } catch (\Exception $e) {
            // Ignore cache stats errors
        }

        return ['hits' => 0, 'misses' => 0, 'hit_rate' => 0];
    }

    /**
     * Log performance metrics
     */
    public static function logPerformanceMetrics(string $operation, float $executionTime, int $queryCount = 0): void
    {
        if (config('app.debug')) {
            \Log::info("Performance: {$operation}", [
                'execution_time' => $executionTime . 'ms',
                'query_count' => $queryCount,
                'memory_usage' => self::getMemoryUsage() . 'MB',
                'peak_memory' => self::getPeakMemoryUsage() . 'MB'
            ]);
        }
    }
}
