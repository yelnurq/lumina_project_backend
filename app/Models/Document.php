<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    protected $guarded=[];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            $document->slug = Str::slug($document->title);
        });
    }
}
