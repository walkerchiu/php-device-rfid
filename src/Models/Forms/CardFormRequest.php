<?php

namespace WalkerChiu\DeviceRFID\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use WalkerChiu\Core\Models\Forms\FormRequest;

class CardFormRequest extends FormRequest
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
            'reader_id'    => trans('php-device-rfid::card.reader_id'),
            'user_id'      => trans('php-device-rfid::card.user_id'),
            'status_id'    => trans('php-device-rfid::card.status_id'),
            'level_id'     => trans('php-device-rfid::card.level_id'),

            'serial'       => trans('php-device-rfid::card.serial'),
            'identifier'   => trans('php-device-rfid::card.identifier'),
            'username'     => trans('php-device-rfid::card.username'),
            'is_black'     => trans('php-device-rfid::card.is_black'),
            'is_enabled'   => trans('php-device-rfid::card.is_enabled'),

            'begin_at'     => trans('php-device-rfid::card.begin_at'),
            'end_at'       => trans('php-device-rfid::card.end_at'),
            'only_dayType' => trans('php-device-rfid::card.only_dayType'),
            'exclude_date' => trans('php-device-rfid::card.exclude_date'),
            'exclude_time' => trans('php-device-rfid::card.exclude_time'),

            'name'         => trans('php-device-rfid::card.name'),
            'description'  => trans('php-device-rfid::card.description')
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
            'reader_id'      => ['required','string','exists:'.config('wk-core.table.device-rfid.readers').',id'],
            'user_id'        => ['nullable','string','exists:'.config('wk-core.table.user').',id'],
            'status_id'      => '',
            'level_id'       => '',

            'serial'         => '',
            'identifier'     => 'required|string|max:255',
            'username'       => 'required|string|max:255',
            'is_black'       => 'required|boolean',
            'is_enabled'     => 'required|boolean',

            'begin_at'       => 'required|date|date_format:Y-m-d H:i:s|before:end_at',
            'end_at'         => 'required|date|date_format:Y-m-d H:i:s|after:begin_at',
            'only_dayType'   => 'nullable|array|min:1|max:7',
            'only_dayType.*' => 'required|integer|distinct|between:0,7',
            'exclude_date'   => 'nullable|array',
            'exclude_date.*' => 'date|distinct|date_format:Y-m-d',
            'exclude_time'   => 'nullable|array',
            'exclude_time.*' => 'date|distinct|date_format:H:i:s',

            'name'           => 'required|string|max:255',
            'description'    => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','string','exists:'.config('wk-core.table.device-rfid.cards').',id']]);
        } elseif ($request->isMethod('post')) {
            $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.device-rfid.cards').',id']]);
        }

        if ( config('wk-device-rfid.onoff.morph-rank') ) {
            if ( !empty(config('wk-core.class.morph-rank.status')) ) {
                $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.morph-rank.statuses').',id']]);
            } elseif ( !empty(config('wk-core.class.morph-rank.levels')) ) {
                $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.morph-rank.levels').',id']]);
            }
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
            'id.string'           => trans('php-core::validation.string'),
            'id.exists'           => trans('php-core::validation.exists'),
            'reader_id.required'  => trans('php-core::validation.required'),
            'reader_id.string'    => trans('php-core::validation.string'),
            'reader_id.exists'    => trans('php-core::validation.exists'),
            'user_id.required'    => trans('php-core::validation.required'),
            'user_id.string'      => trans('php-core::validation.string'),
            'user_id.exists'      => trans('php-core::validation.exists'),
            'status_id.required'  => trans('php-core::validation.required'),
            'status_id.string'    => trans('php-core::validation.string'),
            'status_id.exists'    => trans('php-core::validation.exists'),
            'level_id.required'   => trans('php-core::validation.required'),
            'level_id.string'     => trans('php-core::validation.string'),
            'level_id.exists'     => trans('php-core::validation.exists'),
            'identifier.required' => trans('php-core::validation.required'),
            'identifier.string'   => trans('php-core::validation.string'),
            'identifier.max'      => trans('php-core::validation.max'),
            'username.required'   => trans('php-core::validation.required'),
            'username.string'     => trans('php-core::validation.string'),
            'username.max'        => trans('php-core::validation.max'),
            'is_black.required'   => trans('php-core::validation.required'),
            'is_black.boolean'    => trans('php-core::validation.boolean'),
            'is_enabled.required' => trans('php-core::validation.required'),
            'is_enabled.boolean'  => trans('php-core::validation.boolean'),

            'begin_at.required'          => trans('php-core::validation.required'),
            'begin_at.date'              => trans('php-core::validation.date'),
            'begin_at.date_format'       => trans('php-core::validation.date_format'),
            'begin_at.before'            => trans('php-core::validation.before'),
            'end_at.required'            => trans('php-core::validation.required'),
            'end_at.date'                => trans('php-core::validation.date'),
            'end_at.date_format'         => trans('php-core::validation.date_format'),
            'end_at.after'               => trans('php-core::validation.after'),
            'only_dayType.array'         => trans('php-core::validation.array'),
            'only_dayType.min'           => trans('php-core::validation.min'),
            'only_dayType.max'           => trans('php-core::validation.max'),
            'only_dayType.*.required'    => trans('php-core::validation.required'),
            'only_dayType.*.integer'     => trans('php-core::validation.integer'),
            'only_dayType.*.distinct'    => trans('php-core::validation.distinct'),
            'only_dayType.*.between'     => trans('php-core::validation.between'),
            'exclude_date.array'         => trans('php-core::validation.array'),
            'exclude_date.*.date'        => trans('php-core::validation.date'),
            'exclude_date.*.distinct'    => trans('php-core::validation.distinct'),
            'exclude_date.*.date_format' => trans('php-core::validation.date_format'),
            'exclude_time.array'         => trans('php-core::validation.array'),
            'exclude_time.*.date'        => trans('php-core::validation.date'),
            'exclude_time.*.distinct'    => trans('php-core::validation.distinct'),
            'exclude_time.*.date_format' => trans('php-core::validation.date_format'),

            'name.required' => trans('php-core::validation.required'),
            'name.string'   => trans('php-core::validation.string'),
            'name.max'      => trans('php-core::validation.max')
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
        $validator->after( function ($validator) {
            $data = $validator->getData();
            if (isset($data['identifier'])) {
                $result = config('wk-core.class.device-rfid.card')::where('identifier', $data['identifier'])
                                ->when(isset($data['reader_id']), function ($query) use ($data) {
                                    return $query->where('reader_id', $data['reader_id']);
                                  })
                                ->when(isset($data['id']), function ($query) use ($data) {
                                    return $query->where('id', '<>', $data['id']);
                                  })
                                ->exists();
                if ($result)
                    $validator->errors()->add('identifier', trans('php-core::validation.unique', ['attribute' => trans('php-device-rfid::card.identifier')]));
            }
        });
    }
}
