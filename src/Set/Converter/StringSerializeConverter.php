<?php

namespace SpareParts\Enum\Set\Converter;


use SpareParts\Enum\Enum;
use SpareParts\Enum\Exception\InvalidEnumClassException;
use SpareParts\Enum\Set\ISet;
use SpareParts\Enum\Set\ImmutableSet;

class StringSerializeConverter implements IEnumSetConverter
{
    /**
     * @var string
     */
    private $enumClass;

    /**
     * @param string $enumClass
     */
    public function __construct($enumClass)
    {
        if (!is_subclass_of($enumClass, Enum::class)) {
            throw new InvalidEnumClassException("Class ${enumClass} does not implement Enum (as it should).");
        }
        $this->enumClass = $enumClass;
    }

    /**
     * @param string $serialized
     * @return ImmutableSet
     */
    public function convertToEnumSet($serialized)
    {
        $serialized = (string) $serialized;
        $stringList = unserialize($serialized);

        $set = [];
        foreach ($stringList as $value) {
            $set[] = call_user_func([$this->enumClass, 'instance'], $value);
        }
        return new ImmutableSet($this->enumClass, $set);
    }

    /**
     * @param ISet $enumSet
     * @return string
     */
    public function convertFromEnumSet(ISet $enumSet)
    {
        $stringList = [];
        foreach ($enumSet as $enum) {
            $stringList[] = (string) $enum;
        }
        return serialize($stringList);
    }
}
