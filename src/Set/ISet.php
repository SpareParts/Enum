<?php
namespace SpareParts\Enum\Set;


use SpareParts\Enum\Enum;

interface ISet extends \Countable, \IteratorAggregate
{
    public function contains(Enum $value): bool;

    public function add(Enum $enum): ISet;

    public function remove(Enum $enum): ISet;
}
