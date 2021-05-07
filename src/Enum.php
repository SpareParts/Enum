<?php
/**
MIT License

Copyright (c) 2017 Ondrej Hatala

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 */
namespace SpareParts\Enum;

use SpareParts\Enum\Exception\InvalidEnumSetupException;
use SpareParts\Enum\Exception\InvalidEnumValueException;
use SpareParts\Enum\Exception\OperationNotAllowedException;
use SpareParts\Enum\Mapping\Annotations;
use SpareParts\Enum\Set\ISet;

abstract class Enum
{

    /** @var string[] */
    protected static $values = [];

    /** @var string[][] */
    private static $innerValues = [];

    /** @var self[][] */
    protected static $instances = [];

    /**
     * @var string
     */
    protected $value;

    /**
     * PROTECTED!! Not callable directly.
     * @see static::instance()
     * @see static::__callStatic()
     * @internal
     */
    protected function __construct(string $value)
    {
        if (!isset(self::$innerValues[get_class($this)])) {
            // first, try to fetch correct values
            if (empty(static::$values)) {
                // try to use annotations
                self::$innerValues[get_class($this)] = Annotations::loadEnumValues(get_class($this));
            } else {
                self::$innerValues[get_class($this)] = static::$values;
            }

            // then check we did it in a constructive way
            if (empty(self::$innerValues[get_class($this)])) {
                throw new InvalidEnumSetupException('Incorrect setup! Enum '.get_called_class().' doesn\'t have its static::$values set.');
            }
        }

        // does this $value exist in current Enum
        if (!in_array($value, self::$innerValues[get_class($this)])) {
            throw new InvalidEnumValueException('Enum '.get_called_class().' does not contain value '.$value.'. Possible values are: '.implode(', ', static::$values));
        }
        $this->value = $value;
    }

    /**
     * String representation
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /** @return static */
    public static function __callStatic(string $method, array $args): self
    {
        if ($method[0] === '_') {
            $method = substr($method, 1);
        }
        return static::instance($method);
    }

    /** @return static */
    public static function instance(string $value): self
    {
        if (!isset(static::$instances[get_called_class()][$value])) {
            static::$instances[get_called_class()][$value] = new static($value);
        }

        return static::$instances[get_called_class()][$value];
    }

    /**
     *
     *
     * @param iterable|self[] $enumList
     * @return bool
     */
    public function isAnyOf(iterable $enumList): bool
    {
        foreach ($enumList as $enum) {
            if ($enum === $this) {
                return true;
            }
        }
        return false;
    }

    public function __clone()
    {
        throw new OperationNotAllowedException('Singleton cannot be cloned.');
    }

    public function __sleep()
    {
        throw new OperationNotAllowedException('Singleton cannot be serialized.');
    }

    public function __wakeup()
    {
        throw new OperationNotAllowedException('Singleton cannot be serialized');
    }
}
