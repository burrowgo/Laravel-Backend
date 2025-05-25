<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
            // Ensure status is one of the allowed values, defaulting to 'draft'
            if (!in_array($model->status, ['draft', 'published'])) {
                $model->status = 'draft';
            }
        });

        static::updating(function ($model) {
            // Ensure status is one of the allowed values when updating
            if ($model->isDirty('status') && !in_array($model->status, ['draft', 'published'])) {
                // Revert to original status or set to default if somehow invalid
                $model->status = $model->getOriginal('status', 'draft');
             }
        });
    }

    // It's good practice to ensure 'slug' is unique, typically handled by a validation rule
    // or a database unique constraint. For status, while a default is set,
    // validation for 'draft' or 'published' should ideally be in a Request class
    // or service layer when handling input.
}
