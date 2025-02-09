<?php

/**
 * Returns bool if error exists
 */
function hasError(mixed $response, bool $logError = false, bool $showToast = false, ?string $toastTitle = null, ?string $toastType = "danger", ?bool $stickyToast = true, ?int $toastDuration = null): bool
{
    if (is_array($response) && array_key_exists('error', $response)) {
        // Has error
        if ($showToast) {
            if ($stickyToast) {
                toast()->$toastType($response['error'], $toastTitle)->duration($toastDuration ?? config('tall-toasts.duration'))->sticky()->push();
            } else {
                toast()->$toastType($response['error'], $toastTitle)->duration($toastDuration ?? config('tall-toasts.duration'))->push();
            }
        }

        if ($logError) {
            Log::info($response['error']);
            Log::error(
                var_export($response['data'], true)
            );
        }


        return true;
    } else {
        // Doesn't have error
        return false;
    }

    // return array_key_exists('error', $response);
}

/**
 * The default method of throwing an error.
 */
// function throwError(string $message, string $title = null, array $data = [])
// {
//     toast()->danger($message, $title)->sticky()->push();
// }
