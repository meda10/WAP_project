<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\AppHelper;

class Language extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['language', 'language_name'];

    public static function create(array $attributes = [])
    {
        $attributes['language_name'] = AppHelper::friendlyUrl($attributes['language']);
        return static::query()->create($attributes);
    }
}
