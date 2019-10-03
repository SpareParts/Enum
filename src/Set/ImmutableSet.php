<?php
namespace SpareParts\Enum\Set;

use SpareParts\Enum\Enum;
use SpareParts\Enum\Exception\EnumSetMustContainEnumsException;

class ImmutableSet implements ISet
{
    use SetTrait;

    public function add(Enum $enum): ISet
    {
        if (!($enum instanceof $this->enumClass)) {
            throw new EnumSetMustContainEnumsException(sprintf("Expected %s, got %s", $this->enumClass, get_class($enum)));
        }
        $set = $this->set;
        $set[(string) $enum] = $enum;

        return new static($this->enumClass, $set);
    }

    public function remove(Enum $enum): ISet
    {
        if (!($enum instanceof $this->enumClass)) {
            throw new EnumSetMustContainEnumsException(sprintf("Expected %s, got %s", $this->enumClass, get_class($enum)));
        }
        $set = $this->set;
        unset($set[(string) $enum]);

        return new static($this->enumClass, $set);
    }
}
