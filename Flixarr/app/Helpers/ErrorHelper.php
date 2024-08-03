<?php

/**
 * Returns bool if error exists
 *
 * @param  mixed  $response
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
