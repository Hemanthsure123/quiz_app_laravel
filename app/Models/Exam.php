<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'duration'];

    protected static function booted()
    {
        static::creating(function ($exam) {
            $exam->uuid = Str::uuid();  // Generate UUID automatically
        });
    }
}