<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_phone',
        'name',
        'role',
        'message',
        'type',
    ];
}
