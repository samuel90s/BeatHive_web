<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Faq extends Model
{
protected $fillable = [
'faq_category_id','question','answer','slug','is_published','order_column'
];


public function category()
{
return $this->belongsTo(FaqCategory::class, 'faq_category_id');
}


public function scopePublished($q){ return $q->where('is_published', true); }


public function scopeSearch($q, $term){
if(!$term) return $q;
return $q->where(function($qq) use ($term){
$qq->where('question','like',"%{$term}%")
->orWhere('answer','like',"%{$term}%");
});
}
}