<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Post extends Model
{
    //Table Name
    protected $table = 'posts';

    //Primary Key
    public $primaryKey = 'id';

    //TimeStamps
    public $timeStamps = true;
    
    public function user(){
        return $this->blongsTo('App\User');
    }
}
