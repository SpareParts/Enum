<?php
namespace SpareParts\Enum\Tests;
use SpareParts\Enum\Enum;


/**
 * @method static SWIM()
 * @method static RUN()
 * @method static FLY()
 */
class TestEnum2 extends Enum
{
    protected static $values = [
        'SWIM',
        'RUN',
        'FLY'
    ];
}