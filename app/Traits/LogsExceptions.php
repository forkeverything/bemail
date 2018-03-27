<?php

namespace App\Traits;

use Exception;

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
        \LOG::error($name, [
            'code' => $exception->getCode(),
            'msg' => $exception->getMessage(),
            'exception' => $exception
        ]);
    }
}