<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;

class User extends Authenticatable
{
    use HasFactory, LaravelPermissionToVueJS, HasRoles, Notifiable, HasApiTokens;

    public $timestamps = false;
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'surname', 'email', 'password', 'role', 'address',
                            'zip_code', 'city', 'confirmed', 'store_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /*protected $casts = [
        'email_verified_at' => 'datetime',
    ];*/


    public function hasRole($roles)
    {
        foreach ($roles as $role)
            if ($this->role == $role) return true;
        return false;
    }

    public static function get_user_role()
    {
        $user = Auth::user();
        if($user != null){
            return $user->role;
        }else{
            return null;
        }
    }

    public function isConfirmed()
    {
        return boolval($this->confirmed);
    }


    public static function getPossibleRoles()
    {
        $type = DB::select(DB::raw('SHOW COLUMNS FROM users WHERE Field = "role"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $values = array();
        foreach(explode(',', $matches[1]) as $value){
            $values[] = trim($value, "'");
        }
        return $values;
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'id', 'store_id');
    }
}
