<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'id',
        'name',
        'price',
        'description',
        'stock',
        'image',
        'category_id',
        'hub_product_id'

    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
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
