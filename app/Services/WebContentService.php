<?php

namespace App\Services;

use App\Models\WebContent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WebContentService
{
    protected $validStatuses = ['draft', 'published'];

    /**
     * Create a new WebContent record.
     *
     * @param array $data
     * @return WebContent
     * @throws ValidationException
     */
    public function createWebContent(array $data): WebContent
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'sometimes|string|in:draft,published',
            'slug' => 'sometimes|string|unique:web_contents,slug' // Basic uniqueness for slug
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();

        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
            // Ensure slug uniqueness if auto-generated
            $count = WebContent::where('slug', $validatedData['slug'])->count();
            if ($count > 0) {
                 $validatedData['slug'] = $validatedData['slug'] . '-' . ($count + 1);
            }
        }

        $validatedData['status'] = $validatedData['status'] ?? 'draft';
        if (!in_array($validatedData['status'], $this->validStatuses)) {
            $validatedData['status'] = 'draft';
        }

        return WebContent::create($validatedData);
    }

    /**
     * Update an existing WebContent record.
     *
     * @param WebContent $webContent
     * @param array $data
     * @return WebContent
     * @throws ValidationException
     */
    public function updateWebContent(WebContent $webContent, array $data): WebContent
    {
        $validator = Validator::make($data, [
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'status' => 'sometimes|string|in:draft,published',
            'slug' => 'sometimes|string|unique:web_contents,slug,' . $webContent->id // Slug unique rule for update
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();

        if (!empty($validatedData['status']) && !in_array($validatedData['status'], $this->validStatuses)) {
            // If status is provided but invalid, perhaps revert to original or default, or reject.
            // For now, let's ensure it falls back to a valid default if it's being changed to something invalid.
            // This logic might be better handled by ensuring input is always valid before calling the service.
            $validatedData['status'] = $webContent->getOriginal('status', 'draft');
        }

        // If title is changing and slug is not provided, regenerate slug
        if (isset($validatedData['title']) && !isset($validatedData['slug'])) {
            $newSlug = Str::slug($validatedData['title']);
            if ($newSlug !== $webContent->slug) {
                 $count = WebContent::where('slug', $newSlug)->where('id', '!=', $webContent->id)->count();
                 if ($count > 0) {
                      $validatedData['slug'] = $newSlug . '-' . ($count + 1);
                 } else {
                      $validatedData['slug'] = $newSlug;
                 }
            }
        }

        $webContent->update($validatedData);
        return $webContent->fresh();
    }
}
