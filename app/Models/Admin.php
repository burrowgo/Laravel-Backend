<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Important: extend Authenticatable
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; // Import and use Passport trait
use Spatie\Permission\Traits\HasRoles; // Import HasRoles

class Admin extends Authenticatable // Extend Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // Add HasRoles here

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Assuming you want to hash admin passwords
        ];
    }
}
