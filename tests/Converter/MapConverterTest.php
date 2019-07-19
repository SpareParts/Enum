<?php
namespace SpareParts\Enum\Tests\Converter;

use SpareParts\Enum\Converter\MapConverter;
use SpareParts\Enum\Tests\TestEnum;

class MapConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException \SpareParts\Enum\Converter\ConverterSetupException
     */
    public function mapMustContainOnlyEnums()
    {
        new MapConverter([
            'tralala' => 'wut',
        ]);
    }

    /**
     * @test
     */
    public function fromEnumWorks()
    {
        $converter = new MapConverter([
            'openned' => TestEnum::OPEN(),
            'closeded' => TestEnum::CLOSED(),
            'brokenned' => TestEnum::BROKEN(),
        ]);

        $this->assertSame('openned', $converter->fromEnum(TestEnum::OPEN()));
        $this->assertSame('closeded', $converter->fromEnum(TestEnum::CLOSED()));
        $this->assertSame('brokenned', $converter->fromEnum(TestEnum::BROKEN()));
    }

    /**
     * @test
     */
    public function toEnumWorks()
    {
        $converter = new MapConverter([
            'openned' => TestEnum::OPEN(),
            'closeded' => TestEnum::CLOSED(),
            'brokenned' => TestEnum::BROKEN(),
        ]);

        $this->assertSame(TestEnum::OPEN(), $converter->toEnum('openned'));
        $this->assertSame(TestEnum::BROKEN(), $converter->toEnum('brokenned'));
        $this->assertSame(TestEnum::CLOSED(), $converter->toEnum('closeded'));
    }

    /**
     * @test
     */
    public function fromEnumReturnsVariousTypes()
    {
        $converter = new MapConverter([
            1 => TestEnum::OPEN(),
            'closeded' => TestEnum::CLOSED(),
            TestEnum::BROKEN(),
        ]);

        $this->assertSame(1, $converter->fromEnum(TestEnum::OPEN()));
        $this->assertSame('closeded', $converter->fromEnum(TestEnum::CLOSED()));
        $this->assertSame(2, $converter->fromEnum(TestEnum::BROKEN()));
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Converter\UnableToConvertException
     */
    public function toEnumValueMustBeScalar()
    {
        $converter = new MapConverter([
            'openned' => TestEnum::OPEN(),
            'closeded' => TestEnum::CLOSED(),
            'brokenned' => TestEnum::BROKEN(),
        ]);

        $converter->toEnum([ 'a' => 2]);
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Converter\UnableToConvertException
     */
    public function toEnumValueMustBePresentInMap()
    {
        $converter = new MapConverter([
            'openned' => TestEnum::OPEN(),
            'brokenned' => TestEnum::BROKEN(),
        ]);

        $converter->toEnum('closed');
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Converter\UnableToConvertException
     */
    public function fromEnumMustBeInMap()
    {
        $converter = new MapConverter([
            'openned' => TestEnum::OPEN(),
            'brokenned' => TestEnum::BROKEN(),
        ]);

        $converter->fromEnum(TestEnum::CLOSED());
    }
}