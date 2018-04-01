<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Trait LogsExceptions
 *
 * Manually log exceptions.
 *
 * @package App\Traits
 */
trait LogsExceptions
{
    /**
     * Record error to logs.
     *
     * @param $name
     * @param Exception $exception
     */
    private function logException($name, Exception $exception)
    {
        Log::error($name, [
            'code' => $exception->getCode(),
            'msg' => $exception->getMessage(),
            'exception' => $exception
        ]);
    }
}