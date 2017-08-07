<?php
namespace SpareParts\Enum\Set\Converter;

use SpareParts\Enum\Enum;
use SpareParts\Enum\Exception\EnumSetMustContainEnumsException;
use SpareParts\Enum\Set\ISet;
use SpareParts\Enum\Set\ImmutableSet;

class BitMaskConverter implements IEnumSetConverter
{
    /**
     * @var string
     */
    private $enumClass;

    /**
     * @var array
     */
    private $mapping;

    /**
     * @param Enum[] $enumValuesMap
     */
    public function __construct($enumValuesMap)
    {
        if (!is_array($enumValuesMap) || !count($enumValuesMap)) {
            throw new EnumSetMustContainEnumsException('You have to provide converter mapping.');
        }
        $this->mapping = $this->prepareInnerMapping($enumValuesMap);
    }

    /**
     * @param int $values
     * @return ISet
     */
    public function convertToEnumSet($values)
    {
        $bitValue = (int) $values;

        $set = [];
        foreach ($this->mapping as $value => $bit) {
            if ($bitValue & $bit) {
                $set[] = call_user_func([$this->enumClass, 'instance'], $value);
            }
        }
        return new ImmutableSet($this->enumClass, $set);
    }

    /**
     * @param ISet $enumSet
     * @return int
     */
    public function convertFromEnumSet(ISet $enumSet)
    {
        $bitValue = 0;
        /** @var Enum $value */
        foreach ($enumSet as $value) {
            $bitValue = $bitValue | $this->mapping[(string) $value];
        }
        return $bitValue;
    }

    /**
     * @param $enumValuesMap
     * @return array
     */
    private function prepareInnerMapping($enumValuesMap)
    {
        // translate enum values to bit values mapping
        $innerMapping = [];
        $shift = 0;
        foreach ($enumValuesMap as $value) {
            $this->fetchEnumClass($value);

            $innerMapping[(string)$value] = 1 << $shift;
            $shift++;
        }
        return $innerMapping;
    }

    /**
     * @param $value
     */
    private function fetchEnumClass($value)
    {
        // try to guess enum class
        if (is_null($this->enumClass)) {
            $this->setEnumClass($value);
        }
        if (!is_object($value) || !($value instanceof $this->enumClass)) {
            throw new EnumSetMustContainEnumsException(sprintf("Expected `%s`, got `%s`", $this->enumClass, $this->printVar($value)));
        }
    }

    /**
     * @param mixed $enum
     */
    private function setEnumClass($enum)
    {
        if (!is_object($enum) || !is_subclass_of($enum, Enum::class)) {
            throw new EnumSetMustContainEnumsException(sprintf("Class %s does not implement Enum (as it should).", $this->printVar($enum)));
        }
        $this->enumClass = get_class($enum);
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function printVar($value)
    {
        return is_object($value) ? get_class($value) : var_export($value, true);
    }
}
