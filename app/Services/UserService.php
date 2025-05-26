<?php

namespace App\Services;

use App\Models\User; // Default to User model, can be adapted for Admin or use BaseUser if type hinting allows
use App\Models\Admin;
use App\Models\BaseUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * Create a new user (User or Admin).
     *
     * @param array $data
     * @param string $userType Choose 'user' or 'admin'
     * @return BaseUser
     * @throws ValidationException
     */
    public function createUser(array $data, string $userType = 'user'): BaseUser
    {
        $model = $userType === 'admin' ? new Admin() : new User();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique($model->getTable(), 'email')],
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        return $model->create($validatedData);
    }

    /**
     * Update an existing user (User or Admin).
     *
     * @param BaseUser $baseUser
     * @param array $data
     * @return BaseUser
     * @throws ValidationException
     */
    public function updateUser(BaseUser $baseUser, array $data): BaseUser
    {
        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique($baseUser->getTable(), 'email')->ignore($baseUser->id)],
            'password' => 'sometimes|string|min:8',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $baseUser->update($validatedData);
        return $baseUser->fresh();
    }
}
