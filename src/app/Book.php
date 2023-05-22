<?php

namespace App\Https;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = ['title', 'author'];
}
