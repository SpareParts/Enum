<?php
namespace SpareParts\Enum\Tests;

use SpareParts\Enum\Enum;

/**
 * @method static OPEN()
 * @method static CLOSED()
 * @method static BROKEN()
 */
class TestEnum extends Enum
{
    protected static $values = [
        'OPEN',
        'CLOSED',
        'BROKEN'
    ];
}