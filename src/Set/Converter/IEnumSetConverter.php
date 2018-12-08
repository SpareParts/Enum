<?php
namespace SpareParts\Enum\Set\Converter;

use SpareParts\Enum\Set\ISet;

interface IEnumSetConverter
{
    /**
     * @param mixed $values
     * @return ISet
     */
    public function convertToEnumSet($values): ISet;

    /**
     * @param ISet $enumSet
     * @return mixed
     */
    public function convertFromEnumSet(ISet $enumSet);
}
