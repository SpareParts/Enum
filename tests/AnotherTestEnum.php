<?php
namespace SpareParts\Enum\Tests;

use SpareParts\Enum\Enum;

/**
 * @method static PUBLIC
 * @method static PRIVATE
 * @method static FRIENDS
 */
class AnotherTestEnum extends Enum
{
    protected static $values = [
        'PUBLICC',
        'PRIVATE',
        'FRIENDS',
    ];
}
