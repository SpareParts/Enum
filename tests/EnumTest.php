<?php
namespace SpareParts\Enum\Tests;


use SpareParts\Enum\Exception\InvalidEnumValueException;

class EnumTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\InvalidEnumSetupException
     */
    public function emptyEnumThrowsException()
    {
        EmptyTestEnum::instance('ENUM VALUE');
    }


    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\InvalidEnumValueException
     */
    public function undefinedValueThrowsException()
    {
        TestEnum::UNDEFINED_STATE();
    }


    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\InvalidEnumValueException
     */
    public function undefinedValueInInstanceThrowsException()
    {
        TestEnum::instance('UNDEFINED_STATE');
    }


    /**
     * @test
     */
    public function existingValueCanBeUsed()
    {
        TestEnum::OPEN();
        TestEnum::CLOSED();
        TestEnum::BROKEN();
    }


    /**
     * @test
     */
    public function comparisonWorks()
    {
        $open = TestEnum::OPEN();
        $closed = TestEnum::CLOSED();

        $this->assertNotEquals($open, $closed);
    }


    /**
     * @test
     */
    public function sameInstanceReturned()
    {
        $open = TestEnum::OPEN();
        $this->assertSame($open, TestEnum::OPEN());
    }

	/**
	 * @test
	 */
    public function stringRepresentation()
    {
	    $open = TestEnum::OPEN();
	    $this->assertEquals('OPEN', (string) TestEnum::OPEN());
    }
}
