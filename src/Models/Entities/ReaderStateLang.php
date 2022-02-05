<?php

namespace WalkerChiu\DeviceRFID\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class ReaderStateLang extends Lang
{
    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.device-rfid.readers_states_lang');

        parent::__construct($attributes);
    }
}
