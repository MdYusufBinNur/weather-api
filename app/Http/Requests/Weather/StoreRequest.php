<?php

namespace App\Http\Requests\Weather;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'city_id' => 'required|exists:cities,id',
            'coordinates' => 'required',
            'weather_condition' => 'required',
            'temperature' => 'required',
            'feels_like' => 'required',
            'humidity' => 'required',
            'wind_speed' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'error' => true,
            'message' => $validator->errors()->first(),
            'data' => null,
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
