<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'surname', 'birth'];

    public function by_name($value){
        $id_array = [];
        foreach ($value as $actor){
            $pieces = explode(" ", $actor['herec']);
            $name = $pieces[0];
            $surname = $pieces[1];
            $id = Participant::select(['id'])->where('name', '=', $name)->where('surname', '=', $surname)->first();
            if($id != null){
                $id_array[$id['id']] = $actor['herec'];
            }
        }
        return $id_array;
    }

    public function title(){
        return $this->belongsToMany(Title::class, 'participant_title');
    }

    public static function get_actor_by_id($id){
        return Participant::where('id', $id)->get();
    }
}
