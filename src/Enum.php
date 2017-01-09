<?php
namespace SpareParts\Enum;

use SpareParts\Enum\Exception\InvalidEnumSetupException;
use SpareParts\Enum\Exception\InvalidEnumValueException;

/**
 *
 */
abstract class Enum
{

    /**
     * @var string[]
     */
    protected static $values = [];

    /**
     * @var $this[]
     */
    protected static $instances = [];

    /**
     * @var string
     */
    protected $value;

    /**
     * PROTECTED!! Not callable directly.
     * @see static::instance()
     * @see static::__callStatic()
     *
     * @param string $value
     */
    protected function __construct($value)
    {
        if (empty(static::$values)) {
            throw new InvalidEnumSetupException('Incorrect setup! Enum '.get_called_class().' doesn\'t have its static::$values set.');
        }
        if (!in_array($value, static::$values)) {
            throw new InvalidEnumValueException('Enum '.get_called_class().'does not contain value '.$value.'. Possible values are: '.implode(', ', static::$values));
        }
        $this->value = $value;
    }


    /**
     * @param string $method
     * @param array $args
     *
     * @return \SpareParts\Enum\Enum
     */
    public static function __callStatic($method, $args)
    {
        return static::instance($method);
    }


    /**
     * @param string $value
     *
     * @return $this
     */
    public static function instance($value)
    {
        if (!isset(static::$instances[get_called_class()][$value])) {
            static::$instances[get_called_class()][$value] = new static($value);
        }

        return static::$instances[get_called_class()][$value];
    }
}