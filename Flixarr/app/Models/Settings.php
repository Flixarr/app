<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Usernotnull\Toast\Concerns\WireToast;

class Settings extends Model
{
    use WireToast;

    protected $fillable = [
        'key',
        'value',
    ];

    public static $settings = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        if (empty(self::$settings)) {
            self::$settings = self::all();
        }

        $model = self::$settings->where('key', $key)->first();

        if (empty($model)) {
            return $default;
        } else {
            return $model->value;
        }
    }

    public static function set(string $key, $value, $returnValue = false): mixed
    {
        if (empty(self::$settings)) {
            self::$settings = self::all();
        }

        $model = self::$settings->where('key', $key)->first();

        if (empty($model)) {
            $model = self::create([
                'key' => $key,
                'value' => $value,
            ]);
            self::$settings->push($model);
        } else {
            $model->update(compact('value'));
        }

        if ($returnValue) {
            return $value;
        } else {
            return true;
        }
    }
}
