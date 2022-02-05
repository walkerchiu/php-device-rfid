<?php

namespace WalkerChiu\DeviceRFID\Models\Entities;

use WalkerChiu\DeviceRFID\Models\Entities\Card;
use WalkerChiu\MorphImage\Models\Entities\ImageTrait;

class CardWithImage extends Card
{
    use ImageTrait;
}
