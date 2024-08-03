<?php

/**
 * Settings Helper
 * A helper that returns Settings::get('key', 'default');
 *
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

/**
 * We will tackle this at a later date,
 * maybe even add spatie/laravel-settings instead
 */
// class SettingsHelper
// {
//     public function get($key)
//     {
//         $value = DB::table('settings')->where('key', $key)->value('value');
//         return new SettingWrapper($value);
//     }

//     public function set($key, $value)
//     {
//         DB::table('settings')->updateOrCreate(['key' => $key], ['value' => $value]);
//     }
// }

// class SettingWrapper
// {
//     private $value;

//     public function __construct($value)
//     {
//         $this->value = $value;
//     }

//     public function add($value)
//     {
//         // Implement logic for adding to the setting (might not be applicable)
//         // You can throw an exception if adding doesn't make sense
//         throw new \Exception("Adding to settings doesn't make sense for this key.");
//     }

//     public function missing()
//     {
//         return is_null($this->value);
//     }

//     public function __toString()
//     {
//         return (string) $this->value; // Implicitly cast to string
//     }
// }
