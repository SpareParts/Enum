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
    public function __construct(iterable $enumValuesMap)
    {
        if (!count($enumValuesMap)) {
            throw new EnumSetMustContainEnumsException('You have to provide converter mapping.');
        }
        $this->mapping = $this->prepareInnerMapping($enumValuesMap);
    }

    /**
     * @param int $values A number representing a bit mask
     * @return ISet
     */
    public function convertToEnumSet($values) : ISet
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

    public function convertFromEnumSet(ISet $enumSet): int
    {
        $bitValue = 0;
        /** @var Enum $value */
        foreach ($enumSet as $value) {
            $bitValue = $bitValue | $this->mapping[(string) $value];
        }
        return $bitValue;
    }

    private function prepareInnerMapping(iterable $enumValuesMap): array
    {
        // translate enum values to bit values mapping
        $innerMapping = [];
        $shift = 0;
        foreach ($enumValuesMap as $value) {
            $this->updateEnumClass($value);

            $innerMapping[(string)$value] = 1 << $shift;
            $shift++;
        }
        return $innerMapping;
    }

    private function updateEnumClass(Enum $value): void
    {
        // try to guess enum class
        if (is_null($this->enumClass)) {
            $this->enumClass = get_class($value);
        }
        if (!is_object($value) || !($value instanceof $this->enumClass)) {
            throw new EnumSetMustContainEnumsException(sprintf("Expected `%s`, got `%s`", $this->enumClass, $this->printVar($value)));
        }
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function printVar($value): string
    {
        return is_object($value) ? get_class($value) : var_export($value, true);
    }
}
