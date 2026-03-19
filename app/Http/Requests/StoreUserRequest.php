<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
            
            // Imagen de perfil
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB máximo
            
            // Mueblerías
            'mueblerías' => ['nullable', 'array'],
            'mueblerías.*' => ['exists:mueblerías,id'],
            'primary_muebleria_id' => ['nullable', 'exists:mueblerías,id'],
            
            // Rol (opcional, por defecto se asigna ID 4)
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
            'password.required' => 'La contraseña es obligatoria.',
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