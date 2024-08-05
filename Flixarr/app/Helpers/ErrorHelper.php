<?php

/**
 * Returns bool if error exists
 */
function hasError(mixed $response, bool $showToast = false, ?string $toastTitle = null): bool
{
    if (is_array($response) && array_key_exists('error', $response)) {
        // Has error
        if ($showToast) {
            toast()->danger($response['error'], $toastTitle)->sticky()->push();
        }

        return true;
    } else {
        // Doesn't have error
        return false;
    }

    // return array_key_exists('error', $response);
}
