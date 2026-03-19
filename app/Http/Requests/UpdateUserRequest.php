<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user'); // Obtiene el ID del usuario desde la ruta

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => ['sometimes', 'nullable', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
            
            // Imagen de perfil
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_profile_image' => ['boolean'], // Flag para eliminar la imagen
            
            // Mueblerías
            'mueblerías' => ['nullable', 'array'],
            'mueblerías.*' => ['exists:mueblerías,id'],
            'primary_muebleria_id' => ['nullable', 'exists:mueblerías,id'],
            
            // Rol
            'role_id' => ['nullable', 'exists:roles,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'profile_image.image' => 'El archivo debe ser una imagen.',
            'profile_image.mimes' => 'La imagen debe ser de tipo: jpg, jpeg, png o webp.',
            'profile_image.max' => 'La imagen no debe superar los 2MB.',
            'mueblerías.*.exists' => 'Una o más mueblerías seleccionadas no existen.',
            'primary_muebleria_id.exists' => 'La mueblería principal seleccionada no existe.',
            'role_id.exists' => 'El rol seleccionado no existe.',
        ];
    }
}