<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\AppHelper;

class State extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function create(array $attributes = [])
    {
        $attributes['icon_path'] = AppHelper::friendlyUrl($attributes['state_name']);
        return static::query()->create($attributes);
    }
}
