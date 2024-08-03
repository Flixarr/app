<?php

use Illuminate\Support\Facades\Log;

function logError(string $title, ?string $message = null, ?object $throwable = null): void
{
    $context = (! $throwable) ? [] : [
        'message' => $throwable->getMessage(),
        'file' => debug_backtrace()[0]['file'],
        'line' => debug_backtrace()[0]['line'],
        'code' => $throwable->getCode(),
        'previous' => $throwable->getPrevious(),
    ];

    Log::error($title.': '.$message, $context);
}

function logAction(string $where, string $message, array $data = []): void
{
    Log::info($where.' - '.$message, $data);
}
