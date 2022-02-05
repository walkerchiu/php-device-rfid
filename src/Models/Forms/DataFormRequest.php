<?php

namespace WalkerChiu\DeviceRFID\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use WalkerChiu\Core\Models\Forms\FormRequest;

class DataFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        if (
            $request->isMethod('put')
            && empty($data['id'])
            && isset($request->id)
        ) {
            $data['id'] = (string) $request->id;
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return [
            'reader_id'   => trans('php-device-rfid::card.reader_id'),
            'register_id' => trans('php-device-rfid::card.register_id'),
            'card_id'     => trans('php-device-rfid::card.card_id'),

            'identifier'  => trans('php-device-rfid::card.identifier'),
            'log'         => trans('php-device-rfid::card.log'),
            'trigger_at'  => trans('php-device-rfid::card.trigger_at')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        $rules = [
            'reader_id'   => ['required','string','exists:'.config('wk-core.table.device-rfid.readers').',id'],
            'register_id' => ['nullable','string','exists:'.config('wk-core.table.device-rfid.readers_registers').',id'],
            'card_id'     => ['nullable','string','exists:'.config('wk-core.table.device-rfid.cards').',id'],

            'identifier'  => 'required|string|max:255',
            'log'         => 'required|string',
            'trigger_at'  => 'required|date|date_format:Y-m-d H:i:s'
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','string','exists:'.config('wk-core.table.device-rfid.data').',id']]);
        } elseif ($request->isMethod('post')) {
            $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.device-rfid.data').',id']]);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.string'            => trans('php-core::validation.string'),
            'id.exists'            => trans('php-core::validation.exists'),
            'reader_id.required'   => trans('php-core::validation.required'),
            'reader_id.string'     => trans('php-core::validation.string'),
            'reader_id.exists'     => trans('php-core::validation.exists'),
            'register_id.required' => trans('php-core::validation.required'),
            'register_id.string'   => trans('php-core::validation.string'),
            'register_id.exists'   => trans('php-core::validation.exists'),
            'card_id.required'     => trans('php-core::validation.required'),
            'card_id.string'       => trans('php-core::validation.string'),
            'card_id.exists'       => trans('php-core::validation.exists'),

            'identifier.required'    => trans('php-core::validation.required'),
            'identifier.string'      => trans('php-core::validation.string'),
            'identifier.max'         => trans('php-core::validation.max'),
            'log.required'           => trans('php-core::validation.required'),
            'log.string'             => trans('php-core::validation.string'),
            'trigger_at.required'    => trans('php-core::validation.required'),
            'trigger_at.date'        => trans('php-core::validation.date'),
            'trigger_at.date_format' => trans('php-core::validation.date_format')
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
