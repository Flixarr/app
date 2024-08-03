<?php

/**
 * Settings Helper
 * A helper that returns Settings::get('key', 'default');
 * To get a settings item, $data must be string.
 * To set a settings item, $data must be array.
 */
function settings(array|string $data, ?string $default = null): mixed
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
