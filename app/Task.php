<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

    protected $fillable = ['name'];

 //大概意思就是一个评论只能对应一个帖子
    public function user() {
        return $this->belongsTo(User::class);
    }

}
