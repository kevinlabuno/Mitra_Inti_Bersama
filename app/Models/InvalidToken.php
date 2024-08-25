<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvalidToken extends Model
{
    use HasFactory;

    protected $table = 'invalid_tokens';

    protected $fillable = ['token'];
}
