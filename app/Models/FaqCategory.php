<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class FaqCategory extends Model
{
    protected $fillable = ['name', 'slug'];


    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('order_column');
    }


    // Auto-slug (optional): set in controller or here
    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->slug) && !empty($model->name)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
