<?php

namespace WalkerChiu\DeviceRFID\Models\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use WalkerChiu\Core\Models\Entities\UuidModel;

class Data extends UuidModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var Array
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var Array
     */
    protected $fillable = [
        'reader_id', 'register_id',
        'card_id',
        'identifier', 'log',
        'trigger_at',
    ];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var Array
	 */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var Array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'trigger_at'
    ];



    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.device-rfid.data');

        parent::__construct($attributes);
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
    public function register()
    {
        return $this->belongsTo(config('wk-core.class.device-rfid.readerRegister'), 'register_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo(config('wk-core.class.device-rfid.card'), 'card_id', 'id');
    }
}
