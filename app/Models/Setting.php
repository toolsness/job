<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    protected $casts = [
        'value' => 'array', // Ensure the value is always cast to an array
    ];

    public static function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $setting = static::where('key', $keys[0])->first();

        if (!$setting) {
            return $default;
        }

        $value = $setting->value;

        for ($i = 1; $i < count($keys); $i++) {
            if (!isset($value[$keys[$i]])) {
                return $default;
            }
            $value = $value[$keys[$i]];
        }

        return $value;
    }

    public static function set($key, $value)
    {
        $keys = explode('.', $key);
        $mainKey = $keys[0];

        $setting = static::firstOrNew(['key' => $mainKey]);

        if (count($keys) > 1) {
            $settingValue = $setting->value ?? [];
            if (!is_array($settingValue)) {
                $settingValue = [];
            }
            $current = &$settingValue;

            for ($i = 1; $i < count($keys) - 1; $i++) {
                if (!isset($current[$keys[$i]])) {
                    $current[$keys[$i]] = [];
                }
                $current = &$current[$keys[$i]];
            }

            $current[$keys[count($keys) - 1]] = $value;
            $setting->value = $settingValue;
        } else {
            $setting->value = $value;
        }

        $setting->save();
    }
}
