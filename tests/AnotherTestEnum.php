<?php
namespace SpareParts\Enum\Tests;

use SpareParts\Enum\Enum;

/**
 * @method static PUBLIC
 * @method static _PUBLIC
 * @method static PRIVATE
 * @method static _PRIVATE
 * @method static FRIENDS
 */
class AnotherTestEnum extends Enum
{
    protected static $values = [
        'PUBLIC',
        'PRIVATE',
        'FRIENDS',
    ];
}
