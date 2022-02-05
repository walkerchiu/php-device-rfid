<?php

namespace WalkerChiu\DeviceRFID\Models\Entities;

use WalkerChiu\Core\Models\Entities\LangTrait;
use WalkerChiu\Core\Models\Entities\UuidEntity;

class Card extends UuidEntity
{
    use LangTrait;



    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.device-rfid.cards');

        $this->fillable = array_merge($this->fillable, [
            'reader_id',
            'user_id',
            'status_id', 'level_id',
            'serial',
            'identifier', 'username',
            'is_black',
            'begin_at', 'end_at', 'only_dayType', 'exclude_date', 'exclude_time',
        ]);

        $this->dates = array_merge($this->dates, [
            'begin_at', 'end_at'
        ]);

        $this->casts = array_merge($this->casts, [
            'only_dayType' => 'json',
            'exclude_date' => 'json',
            'exclude_time' => 'json'
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get it's lang entity.
     *
     * @return Lang
     */
    public function lang()
    {
        if (
            config('wk-core.onoff.core-lang_core')
            || config('wk-device-rfid.onoff.core-lang_core')
        ) {
            return config('wk-core.class.core.langCore');
        } else {
            return config('wk-core.class.device-rfid.cardLang');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function langs()
    {
        if (
            config('wk-core.onoff.core-lang_core')
            || config('wk-device-rfid.onoff.core-lang_core')
        ) {
            return $this->langsCore();
        } else {
            return $this->hasMany(config('wk-core.class.device-rfid.cardLang'), 'morph_id', 'id');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reader()
    {
        return $this->belongsTo(config('wk-core.class.device-rfid.reader'), 'reader_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('wk-core.class.user'), 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data()
    {
        return $this->hasMany(config('wk-core.class.device-rfid.data'), 'card_id', 'id');
    }

    /**
     * Get all of the categories for the card.
     *
     * @param String  $type
     * @param Bool    $is_enabled
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories($type = null, $is_enabled = null)
    {
        $table = config('wk-core.table.morph-category.categories_morphs');
        return $this->morphToMany(config('wk-core.class.morph-category.category'), 'morph', $table)
                    ->when(is_null($type), function ($query) {
                          return $query->whereNull('type');
                      }, function ($query) use ($type) {
                          return $query->where('type', $type);
                      })
                    ->unless( is_null($is_enabled), function ($query) use ($is_enabled) {
                        return $query->where('is_enabled', $is_enabled);
                    });
    }

    /**
     * Check if it belongs to the user.
     * 
     * @param User  $user
     * @return Bool
     */
    public function isOwnedBy($user): bool
    {
        if (empty($user))
            return false;

        return $this->user_id == $user->id;
    }
}
