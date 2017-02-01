<?php
namespace SpareParts\Enum\Tests\Set\Converter;

use SpareParts\Enum\Set\Converter\StringSerializeConverter;
use SpareParts\Enum\Set\ImmutableSet;
use SpareParts\Enum\Set\ISet;
use SpareParts\Enum\Set\Set;
use SpareParts\Enum\Tests\TestEnum;

class StringSerializeConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param ISet $set
     * @param string $expectedSerialization
     * @test
     * @dataProvider dataProviderForConvert
     */
    public function convertFromEnumWorks(ISet $set, $expectedSerialization)
    {
        $converter = new StringSerializeConverter(TestEnum::class);

        $serialized = $converter->convertFromEnumSet($set);
        $this->assertEquals($expectedSerialization, $serialized);
    }

    /**
     * @param ISet $expectedSet
     * @param string $serialization
     * @test
     * @dataProvider dataProviderForConvert
     */
    public function convertToEnumWorks(ISet $expectedSet, $serialization)
    {
        $converter = new StringSerializeConverter(TestEnum::class);

        $set = $converter->convertToEnumSet($serialization);
        $this->assertEquals($expectedSet, $set);
    }


    public function dataProviderForConvert()
    {
        return [
            '2 values' => [
                'set' => new ImmutableSet(TestEnum::class, [TestEnum::CLOSED(), TestEnum::BROKEN()]),
                'value' => 'a:2:{i:0;s:6:"CLOSED";i:1;s:6:"BROKEN";}',
            ],
            '2 same values' => [
                'set' => new ImmutableSet(TestEnum::class, [TestEnum::CLOSED(), TestEnum::CLOSED()]),
                'value' => 'a:1:{i:0;s:6:"CLOSED";}',
            ],
            '3 values' => [
                'set' => new ImmutableSet(TestEnum::class, [TestEnum::CLOSED(), TestEnum::OPEN(), TestEnum::BROKEN()]),
                'value' => 'a:3:{i:0;s:6:"CLOSED";i:1;s:4:"OPEN";i:2;s:6:"BROKEN";}',
            ],
            'no values' => [
                'set' => new ImmutableSet(TestEnum::class),
                'value' => 'a:0:{}',
            ],
        ];
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\InvalidEnumClassException
     */
    public function converterSetupNeedsEnumClass()
    {
        new StringSerializeConverter(\ArrayIterator::class);
    }
}
