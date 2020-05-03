<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name' => 'required|max:50',
            'category_id' => 'required',
            'description' => 'required|max:255'

        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'A category is required',
            'name.required' => 'A subcategoryName is required',
            'description.required'  => 'A description is required',
        ];
    }
}
