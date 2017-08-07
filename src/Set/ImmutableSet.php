<?php
namespace SpareParts\Enum\Set;

use SpareParts\Enum\Enum;
use SpareParts\Enum\Exception\EnumSetMustContainEnumsException;

class ImmutableSet implements ISet
{
    use SetTrait;

    /**
     * @param Enum $enum
     * @return self
     */
    public function add(Enum $enum)
    {
        if (!($enum instanceof $this->enumClass)) {
            throw new EnumSetMustContainEnumsException(sprintf("Expected %s, got %s", $this->enumClass, get_class($enum)));
        }
        $set = $this->set;
        $set[(string) $enum] = $enum;

        return new ImmutableSet($this->enumClass, $set);
    }

    /**
     * @param Enum $enum
     * @return self
     */
    public function remove(Enum $enum)
    {
        if (!($enum instanceof $this->enumClass)) {
            throw new EnumSetMustContainEnumsException(sprintf("Expected %s, got %s", $this->enumClass, get_class($enum)));
        }
        $set = $this->set;
        unset($set[(string) $enum]);

        return new ImmutableSet($this->enumClass, $set);
    }
}
