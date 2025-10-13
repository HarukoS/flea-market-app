<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'brand_name',
        'description',
        'price',
        'item_image',
        'condition_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // // ログインユーザーがこのアイテムをLikeしているか判定
    // public function isLikedBy($user)
    // {
    //     if (!$user) return false;
    //     return $this->likes()->where('user_id', $user->id)->exists();
    // }
}
