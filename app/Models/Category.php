<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Category extends Model
{
    protected $table = 'categories';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'id',
        'name',
        'ia_active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
