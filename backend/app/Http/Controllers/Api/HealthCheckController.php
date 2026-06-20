<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthCheckController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'app' => [
                'status' => 'ok',
                'env' => app()->environment(),
                'debug' => (bool) config('app.debug'),
            ],
            'database' => $this->databaseCheck(),
            'cache' => $this->cacheCheck(),
        ];

        $healthy = collect($checks)->every(fn (array $check): bool => $check['status'] === 'ok');

        return response()->json([
            'status' => $healthy ? 'ok' : 'degraded',
            'timestamp' => now()->toISOString(),
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }

    /**
     * @return array{status: string, driver: string, error?: string}
     */
    private function databaseCheck(): array
    {
        try {
            DB::select('select 1');

            return [
                'status' => 'ok',
                'driver' => config('database.default'),
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'failed',
                'driver' => config('database.default'),
                'error' => class_basename($exception),
            ];
        }
    }

    /**
     * @return array{status: string, store: string, error?: string}
     */
    private function cacheCheck(): array
    {
        try {
            $key = 'health:' . now()->timestamp;
            Cache::put($key, 'ok', 30);
            $ok = Cache::get($key) === 'ok';
            Cache::forget($key);

            return [
                'status' => $ok ? 'ok' : 'failed',
                'store' => config('cache.default'),
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'failed',
                'store' => config('cache.default'),
                'error' => class_basename($exception),
            ];
        }
    }
}
