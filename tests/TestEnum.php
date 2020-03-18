<?php
namespace SpareParts\Enum\Tests;

use SpareParts\Enum\Enum;

/**
 * @method static TestEnum OPEN()
 * @method static TestEnum CLOSED()
 * @method static TestEnum BROKEN()
 */
class TestEnum extends Enum
{
    protected static $values = [
        'OPEN',
        'CLOSED',
        'BROKEN'
    ];
}