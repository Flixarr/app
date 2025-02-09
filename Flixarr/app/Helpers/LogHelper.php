<?php

use Illuminate\Support\Facades\Log;

function logAction(string $message, mixed $data = null)
{
    Log::info($message, [$data]);
}

// function logAction(string $where, string $message, array $data = []): void
// {
//     Log::info($where . ' - ' . $message, $data);
//     Log::info(__FILE__);
// }

function logError(?string $title = 'unknown', ?string $message = null, ?array $data = null, ?object $throwable = null): void
{
    $context = (!$throwable) ? [] : [
        'message' => $throwable->getMessage(),
        'file' => debug_backtrace()[0]['file'],
        'line' => debug_backtrace()[0]['line'],
        'code' => $throwable->getCode(),
        'previous' => $throwable->getPrevious(),
    ];

    Log::error($title . ': ' . $message, [$data, $context]);
}
