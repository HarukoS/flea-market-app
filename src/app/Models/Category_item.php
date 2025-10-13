<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'category_id',
    ];
}
