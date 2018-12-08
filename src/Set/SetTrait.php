<?php
namespace SpareParts\Enum\Set;

use SpareParts\Enum\Enum;
use SpareParts\Enum\Exception\EnumSetMustContainEnumsException;
use SpareParts\Enum\Exception\InvalidEnumClassException;

trait SetTrait
{
    /**
     * @var Enum[]
     */
    protected $set = [];

    /**
     * @var string
     */
    protected $enumClass;

    /**
     * @param string $enumClass
     * @param Enum[] $set
     */
    public function __construct(string $enumClass, $set = [])
    {
        // if enum class was sent, we must make sure it is valid
        $this->setEnumClass($enumClass);

        if (!is_array($set)) {
            throw new EnumSetMustContainEnumsException(sprintf("Enum set must be initialized with array of enums."));
        }
        foreach ($set as $enum) {
            if (!($enum instanceof $this->enumClass)) {
                throw new EnumSetMustContainEnumsException(sprintf("Expected %s, got %s", $this->enumClass, get_class($enum)));
            }
            $this->set[(string) $enum] = $enum;
        }
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator(array_values($this->set));
    }

    public function count(): int
    {
        return count($this->set);
    }

    public function contains(Enum $value): bool
    {
        if (in_array($value, $this->set)) {
            return true;
        }
        return false;
    }

    protected function setEnumClass(string $enumClass): void
    {
        if (!is_null($enumClass) && !is_subclass_of($enumClass, Enum::class)) {
            throw new InvalidEnumClassException("Class ${enumClass} does not implement Enum (as it should). Maybe you forgot to specify correct enum class in constructor?");
        }
        $this->enumClass = $enumClass;
    }
}
