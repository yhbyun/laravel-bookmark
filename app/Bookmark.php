<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model {

    public function tags() {
        return $this->hasMany('App\Tag');
    }
}
