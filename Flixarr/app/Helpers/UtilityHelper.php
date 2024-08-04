<?php

/**
 * I was researching a few new Laravel practices and came across this neat little "attempt()"
 * helper function that Koel was utilizing. I thought it would be very helpful keeping
 * my error handling uniform. So here's their credit on this idea.
 *
 * Credit: https://github.com/koel/koel/blob/master/app/Helpers.php
 *
 * P.S. The attempt(), attemptIf(), & attemptUnless().
 *
 * @return null;
 *
 * @throws  Throwable
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
