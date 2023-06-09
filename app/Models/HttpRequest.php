<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HttpRequest extends Model
{
    protected static function booted()
    {
        parent::booted();
        static::saving(function (Model $model) {
            if(\auth()->check()){
                $model->user_id = \auth()->user()->id;
            }
        });
    }
}
