<?php

namespace WalkerChiu\DeviceRFID\Models\Forms;

use WalkerChiu\Core\Models\Forms\FormRequest;

class ReaderDeleteFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        return [
            'id' => ['required','string','exists:'.config('wk-core.table.device-rfid.readers').',id']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.required' => trans('php-core::validation.required'),
            'id.string'   => trans('php-core::validation.string'),
            'id.exists'   => trans('php-core::validation.exists')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
    }
}
