<?php
namespace SpareParts\Enum\Tests\Set;


use SpareParts\Enum\Set\Set;
use SpareParts\Enum\Tests\AnotherTestEnum;
use SpareParts\Enum\Tests\TestEnum;

class EnumSetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function setCanBeIteratedOver()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
        ]);

        $array = [];
        foreach ($set as $item) {
            $array[] = $item;
        }

        $this->assertEquals([
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
        ], $array);
    }

    /**
     * @test
     */
    public function setCanBeCounted()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
        ]);

        $this->assertEquals(2, count($set));
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\EnumSetMustContainEnumsException
     * @expectedExceptionMessage Expected SpareParts\Enum\Tests\TestEnum, got SpareParts\Enum\Tests\AnotherTestEnum
     */
    public function enumsCannotBeMixed()
    {
        new Set(TestEnum::class, [TestEnum::OPEN(), AnotherTestEnum::FRIENDS()]);
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\EnumSetMustContainEnumsException
     * @expectedExceptionMessage Expected SpareParts\Enum\Tests\AnotherTestEnum, got SpareParts\Enum\Tests\TestEnum
     */
    public function enumTypeIsRespected()
    {
        new Set(AnotherTestEnum::class, [TestEnum::OPEN()]);
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\InvalidEnumClassException
     */
    public function enumTypeMustImplementEnum()
    {
        new Set(\ArrayIterator::class, []);
    }

    /**
     * @test
     */
    public function singleValueCanBeAddedOnlyOnce()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
            TestEnum::OPEN(),
        ]);
        $this->assertEquals(2, $set->count());
        $this->assertEquals([
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
        ], $set->getIterator()->getArrayCopy());
    }

    /**
     * @test
     */
    public function containsWorks()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
        ]);
        $this->assertTrue($set->contains(TestEnum::OPEN()));
        $this->assertTrue($set->contains(TestEnum::CLOSED()));
        $this->assertFalse($set->contains(TestEnum::BROKEN()));
    }


    /**
     * @test
     */
    public function removeWorks()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
        ]);

        $newSet = $set->remove(TestEnum::OPEN());
        $this->assertTrue($newSet === $set);
        $this->assertCount(1, $newSet);
        $this->assertFalse($newSet->contains(TestEnum::OPEN()));
        $this->assertTrue($newSet->contains(TestEnum::CLOSED()));
    }

    /**
     * @test
     */
    public function removeAlreadyRemovedItemWorks()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
        ]);

        $newSet = $set->remove(TestEnum::BROKEN());
        $this->assertTrue($newSet === $set);
        $this->assertCount(2, $newSet);
        $this->assertTrue($newSet->contains(TestEnum::OPEN()));
        $this->assertTrue($newSet->contains(TestEnum::CLOSED()));
        $this->assertFalse($newSet->contains(TestEnum::BROKEN()));
    }

    /**
     * @test
     */
    public function multipleRemovesDoNotChangeSet()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
            TestEnum::CLOSED(),
        ]);

        $newSet = $set->remove(TestEnum::BROKEN());
        $this->assertTrue($newSet === $set);
        $this->assertCount(2, $newSet);
        $this->assertTrue($newSet->contains(TestEnum::OPEN()));
        $this->assertTrue($newSet->contains(TestEnum::CLOSED()));
        $this->assertFalse($newSet->contains(TestEnum::BROKEN()));

        $newSet2 = $newSet->remove(TestEnum::BROKEN());
        $this->assertTrue($newSet === $newSet2);
        $this->assertCount(2, $newSet2);
        $this->assertTrue($newSet2->contains(TestEnum::OPEN()));
        $this->assertTrue($newSet2->contains(TestEnum::CLOSED()));
        $this->assertFalse($newSet2->contains(TestEnum::BROKEN()));
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\EnumSetMustContainEnumsException
     */
    public function onlyCorrectEnumTypeCanBeRemoved()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::CLOSED(),
        ]);

        $set->remove(AnotherTestEnum::_PRIVATE());
    }

    /**
     * @test
     */
    public function addWorks()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
        ]);

        $newSet = $set->add(TestEnum::CLOSED());
        $this->assertTrue($set->contains(TestEnum::CLOSED()));
        $this->assertTrue($newSet->contains(TestEnum::CLOSED()));
    }

    /**
     * @test
     */
    public function addAlreadyPresentValueWorks()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::OPEN(),
        ]);

        $newSet = $set->add(TestEnum::OPEN());
        $this->assertTrue($newSet === $set);
        $this->assertTrue($newSet->contains(TestEnum::OPEN()));
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\EnumSetMustContainEnumsException
     */
    public function onlyCorrectEnumTypeCanBeAdded()
    {
        $set = new Set(TestEnum::class, [
            TestEnum::CLOSED(),
        ]);

        $set->add(AnotherTestEnum::_PRIVATE());
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\EnumSetMustContainEnumsException
     */
    public function onlyArrayIsAcceptedAtSetSetup()
    {
        new Set(TestEnum::class, true);
    }

    /**
     * @test
     * @expectedException \SpareParts\Enum\Exception\EnumSetMustContainEnumsException
     */
    public function onlySpecifiedEnumIsAcceptedAtSetSetup()
    {
        new Set(TestEnum::class, [AnotherTestEnum::_PRIVATE()]);
    }
}
