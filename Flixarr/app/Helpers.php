<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Usernotnull\Toast\Concerns\WireToast;

/**
 * Settings Helper
 * A helper that returns Settings::get('key', 'default');
 * To get a settings item, $data must be string.
 * To set a settings item, $data must be array.
 *
 * @param  array|string $data
 * @param  string $default
 * @return mixed
 */
function settings(array|string $data, string $default = null): mixed
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            \App\Models\Settings::set($key, $value);
        }
        return $data;
    } else {
        return \App\Models\Settings::get($data, $default);
    }
}

/**
 * Returns bool if error exists
 *
 * @param mixed $response
 * @return bool
 */
function hasError($response): bool
{
    if (is_array($response) && array_key_exists('error', $response)) {
        // Has error
        return true;
    } else {
        // Doesn't have error
        return false;
    }

    // return array_key_exists('error', $response);
}


function logError(string $title, string $message = null, object $throwable = null): void
{
    $context = (!$throwable) ? [] : [
        'message' => $throwable->getMessage(),
        'file' => debug_backtrace()[0]['file'],
        'line' => debug_backtrace()[0]['line'],
        'code' => $throwable->getCode(),
        'previous' => $throwable->getPrevious(),
    ];

    Log::error($title . ': ' . $message, $context);
}

function logAction(string $where, string $message, array $data = []): void
{
    Log::info($where . ' - ' . $message, $data);
}

/**
 * I was researching a few new Laravel practices and came across this neat little "attempt()"
 * helper function that Koel was utilizing. I thought it would be very helpful keeping
 * my error handling uniform. So here's their credit on this idea.
 *
 * Credit: https://github.com/koel/koel/blob/master/app/Helpers.php
 *
 * P.S. The attempt(), attemptIf(), & attemptUnless().
 *
 * @throws Throwable
 * @return null;
 */
function attempt(callable $callback, string $action, bool $trace = false): mixed
{
    try {
        return $callback();
    } catch (Throwable $e) {

        // Idk if I want to use this or not. Let's come back to this.
        report($e);
        // Log::error('[' . $e->getCode() . '] "' . $e->getMessage() . '" on line ' . $e->getTrace()[0]['line'] . ' of file ' . $e->getTrace()[0]['file']);
        // parent::report($e);

        // If running unit tests, throw the throwable
        // if (app()->runningUnitTests()) {
        //     throw $e;
        // }

        // // Format and log the error
        // Log::info('');
        // Log::info('-------------------START-------------------');
        // Log::info('Action: ' . $action);
        // Log::info('');
        // Log::error('Failed attempt', ['error' => $e->getMessage()]);
        // Log::info('--------------------END--------------------');
        // Log::info('');


        // // Dispatch a notification for the user to see
        // toast()->danger('Please refresh the page and try again.', 'Server Error')->sticky()->push();
        // // Dispatch a toast for the developer to see, but only if the environment is local
        // if (app()->isLocal()) {
        //     toast()->debug($e->getMessage())->sticky()->push();
        // }

        return null;
    }
}

function xml2array($xml): array
{
    normalizeSimpleXML(simplexml_load_string($xml), $result);
    return $result;
}

function normalizeSimpleXML($obj, &$result): void
{
    $data = $obj;
    if (is_object($data)) {
        $data = get_object_vars($data);
    }
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $res = null;
            normalizeSimpleXML($value, $res);
            if (($key == '@attributes') && ($key)) {
                $result = $res;
            } else {
                $result[$key] = $res;
            }
        }
    } else {
        $result = $data;
    }
}
