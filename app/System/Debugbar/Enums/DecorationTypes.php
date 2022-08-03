<?php

namespace App\System\Debugbar\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static HTML()
 * @method static CSV()
 * @method static MYJSON()
 */
class DecorationTypes extends Enum
{
    public const HTML = 1;
    public const CSV = 2;
    public const MYJSON = 3;
}