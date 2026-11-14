<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['id','title','description','image','category_id','author_id','views_counter','date','tags','published','draft'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function author(){
        return $this->belongsTo(User::class,'author_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    protected static function booted(): void
    {
        static::deleted(function($post){
            $post->comments()->delete();
        });
    }

    // Fast title search. Uses FULLTEXT for normal words and LIKE for very short words.
    public function scopeTitleSearch($query, ?string $term)
    {
        $term = trim((string) $term);
        if ($term === '' || $term === 'all') {
            return $query;
        }

        // keep only letters/numbers, then split into words
        $words = preg_split('/\s+/', preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $term), -1, PREG_SPLIT_NO_EMPTY);
        if (!$words) {
            return $query;
        }

        // FULLTEXT ignores words shorter than 3 letters, so use LIKE in that case
        if (collect($words)->contains(fn ($w) => mb_strlen($w) < 3)) {
            return $query->where('title', 'LIKE', '%' . $term . '%');
        }

        // "slow liv" becomes "+slow* +liv*" (every word must match, and the end of the word can be unfinished)
        $boolean = collect($words)->map(fn ($w) => '+' . $w . '*')->implode(' ');

        return $query->whereFullText('title', $boolean, ['mode' => 'boolean']);
    }
}
