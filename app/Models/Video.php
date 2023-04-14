<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';
    protected $guarded = ['created_at', 'updated_at'];

    public function scopeFreeword($query, $freeword)
    {
        $query->whereRaw("match(`free_title`) against (? IN BOOLEAN MODE)", [$freeword]);
    }
}
