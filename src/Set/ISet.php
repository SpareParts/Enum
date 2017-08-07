<?php
namespace SpareParts\Enum\Set;


use SpareParts\Enum\Enum;

interface ISet extends \Countable, \IteratorAggregate
{
    /**
     * @param Enum $value
     * @return bool
     */
    public function contains(Enum $value);

    /**
     * @param Enum $enum
     * @return self
     */
    public function add(Enum $enum);

    /**
     * @param Enum $enum
     * @return self
     */
    public function remove(Enum $enum);
}
