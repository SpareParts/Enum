<?php
namespace SpareParts\Enum\Tests\Set\Converter;

use SpareParts\Enum\Set\Converter\BitMaskConverter;
use SpareParts\Enum\Set\Set;
use SpareParts\Enum\Set\ISet;
use SpareParts\Enum\Set\ImmutableSet;
use SpareParts\Enum\Tests\AnotherTestEnum;
use SpareParts\Enum\Tests\TestEnum;

class BitMaskConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param ISet $set
     * @param int $expectedMask
     * @test
     * @dataProvider dataProviderForConvert
     */
    public function convertToIntegerWorks(ISet $set, $expectedMask)
    {
        $converter = new BitMaskConverter([
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
            TestEnum::BROKEN(),
        ]);

        $mask = $converter->convertFromEnumSet($set);
        $this->assertEquals($expectedMask, $mask);
    }


    public function dataProviderForConvert()
    {
        return [
            '2 values' => [
                'set' => new ImmutableSet(TestEnum::class, [TestEnum::CLOSED(), TestEnum::BROKEN()]),
                'value' => 6,
            ],
            '2 same values' => [
                'set' => new Set(TestEnum::class, [TestEnum::CLOSED(), TestEnum::CLOSED()]),
                'value' => 2,
            ],
            '3 values' => [
                'set' => new ImmutableSet(TestEnum::class, [TestEnum::CLOSED(), TestEnum::OPEN(), TestEnum::BROKEN()]),
                'value' => 7,
            ],
            'no values' => [
                'set' => new Set(TestEnum::class),
                'value' => 0,
            ],
        ];
    }


    /**
     * @param ISet $expectedSet
     * @param int $mask
     * @test
     * @dataProvider dataProviderForConvert
     */
    public function convertToEnumWorks(ISet $expectedSet, $mask)
    {
        $converter = new BitMaskConverter([
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
            TestEnum::BROKEN(),
        ]);

        $set = $converter->convertToEnumSet($mask);

        $this->assertEquals($set->count(), $expectedSet->count());
        foreach ($expectedSet->getIterator() as $item) {
            $this->assertTrue($set->contains($item));
        }
    }


    /**
     * @test
     */
    public function convertToEnumWorksContainsFalse()
    {
        $converter = new BitMaskConverter([
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
            TestEnum::BROKEN(),
        ]);
        $set = $converter->convertToEnumSet(1);
        $this->assertTrue($set->contains(TestEnum::OPEN()));
        $this->assertFalse($set->contains(TestEnum::CLOSED()));
        $this->assertFalse($set->contains(TestEnum::BROKEN()));
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\EnumSetMustContainEnumsException
     */
    public function converterNeedsAtLeastSingleEnumItemAtSetup()
    {
        new BitMaskConverter([]);
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function converterNeedsAnArrayAtSetup()
    {
        new BitMaskConverter(true);
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\EnumSetMustContainEnumsException
     */
    public function multipleEnumsCannotBeMixed()
    {
        new BitMaskConverter([
            TestEnum::OPEN(),
            AnotherTestEnum::FRIENDS(),
            TestEnum::BROKEN(),
        ]);
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function allMapValuesMustBeEnums()
    {
        new BitMaskConverter([
            TestEnum::OPEN(),
            true,
            TestEnum::BROKEN(),
        ]);
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function firstMapValueMustBeEnum()
    {
        new BitMaskConverter([
            true,
            TestEnum::OPEN(),
            TestEnum::BROKEN(),
        ]);
    }
}
