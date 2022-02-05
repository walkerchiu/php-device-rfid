<?php

namespace WalkerChiu\DeviceRFID\Models\Entities;

use WalkerChiu\DeviceRFID\Models\Entities\Reader;
use WalkerChiu\MorphImage\Models\Entities\ImageTrait;

class ReaderWithImage extends Reader
{
    use ImageTrait;
}
