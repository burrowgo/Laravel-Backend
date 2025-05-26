<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebContent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'status',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'draft', // Default status
    ];

    // It's good practice to ensure 'slug' is unique, typically handled by a validation rule
    // or a database unique constraint. For status, while a default is set,
    // validation for 'draft' or 'published' should ideally be in a Request class
    // or service layer when handling input.
}
