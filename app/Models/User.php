<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\BaseUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends BaseUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    // ... other User-specific model code, if any ...
}
