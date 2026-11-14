<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id','name','slug','description','icon','post_count'];
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
