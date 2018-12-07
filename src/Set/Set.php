<?php
namespace SpareParts\Enum\Set;

use SpareParts\Enum\Enum;
use SpareParts\Enum\Exception\EnumSetMustContainEnumsException;

class Set implements ISet
{
    use SetTrait;

    public function add(Enum $enum): ISet
    {
        if (!($enum instanceof $this->enumClass)) {
            throw new EnumSetMustContainEnumsException(sprintf("Expected %s, got %s", $this->enumClass, get_class($enum)));
        }
        $this->set[(string) $enum] = $enum;
        return $this;
    }

    public function remove(Enum $enum): ISet
    {
        if (!($enum instanceof $this->enumClass)) {
            throw new EnumSetMustContainEnumsException(sprintf("Expected %s, got %s", $this->enumClass, get_class($enum)));
        }
        unset($this->set[(string) $enum]);
        return $this;
    }
}
