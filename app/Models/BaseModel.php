<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function paginate($a)
    {
        return 'I am the new find function';
    }


    // etc

}