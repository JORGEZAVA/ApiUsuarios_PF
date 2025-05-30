<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        "image"
    ];

    protected $hidden = [
        'password',
        'remember_token',
        "created_at",
        "updated_at",
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            "is_admin"=>  "boolean",
            "is_banned"=>  "boolean",
        ];
    }
    
}
