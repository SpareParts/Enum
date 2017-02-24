<?php
namespace SpareParts\Enum\Tests\Converter;

use SpareParts\Enum\Converter\LowercaseConverter;
use SpareParts\Enum\Tests\TestEnum;
use SpareParts\Enum\Tests\TestEnum2;

class LowercaseConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function convertToEnumWorks()
    {
        $converter = new LowercaseConverter(TestEnum::class);
        $this->assertSame(TestEnum::CLOSED(), $converter->toEnum('closed'));
    }

    /**
     * @test
     */
    public function convertFromEnumWorks()
    {
        $converter = new LowercaseConverter(TestEnum::class);
        $this->assertSame('closed', $converter->fromEnum(TestEnum::CLOSED()));
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\InvalidEnumValueException
     */
    public function convertToEnumDoesntConvertWrongValues()
    {
        $converter = new LowercaseConverter(TestEnum::class);
        $converter->toEnum('wat');
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Converter\UnableToConvertException
     */
    public function convertFromEnumDoesntConvertDifferentEnums()
    {
        $converter = new LowercaseConverter(TestEnum::class);
        $converter->fromEnum(TestEnum2::RUN());
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Converter\ConverterSetupException
     */
    public function classCanBeInitializedOnlyWithEnum()
    {
        new LowercaseConverter('rattata');
    }

}