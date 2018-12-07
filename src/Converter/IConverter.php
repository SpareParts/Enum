<?php
namespace SpareParts\Enum\Converter;

use SpareParts\Enum\Enum;

interface IConverter
{

    /**
     * Convert given value using this converter to Enum value
     *
     * @param mixed $value
     * @return Enum
     */
    public function toEnum($value): Enum;

    /**
     * Convert Enum value to whatever this converter converts to.
     *
     * @param Enum $enum
     * @return mixed
     */
    public function fromEnum(Enum $enum);

}