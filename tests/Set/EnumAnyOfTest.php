<?php declare(strict_types=1);
namespace SpareParts\Enum\Tests\Set;

use SpareParts\Enum\Set\ImmutableSet;
use SpareParts\Enum\Tests\TestEnum;

class EnumAnyOfTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function isAnyOf_works()
    {
        $open = TestEnum::OPEN();

        $this->assertTrue($open->isAnyOf([
            TestEnum::OPEN(),
            TestEnum::BROKEN(),
        ]));
    }

    /**
     * @test
     */
    public function isAnyOf_fails()
    {
        $open = TestEnum::CLOSED();

        $this->assertFalse($open->isAnyOf([
            TestEnum::OPEN(),
            TestEnum::BROKEN(),
        ]));
    }

    /**
     * @test
     */
    public function isAnyOf_cornerCase()
    {
        $open = TestEnum::CLOSED();

        $this->assertFalse($open->isAnyOf([]));
    }

    /**
     * @test
     */
    public function isAnyOf_works_enumSet()
    {
        $set = new ImmutableSet(TestEnum::class, [TestEnum::BROKEN()]);
        $this->assertTrue(TestEnum::BROKEN()->isAnyOf($set));
        $this->assertFalse(TestEnum::OPEN()->isAnyOf($set));
    }
}