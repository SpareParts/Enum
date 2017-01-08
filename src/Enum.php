<?php
namespace SpareParts\Enum;

use SpareParts\Enum\Exception\InvalidEnumValueException;

class Enum
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
        if (!in_array($method, static::$values)) {
            throw new InvalidEnumValueException('Enum '.get_class(). 'does not contain value '.$method.'. Possible values are: '.implode(', ', static::$values));
        }

        return static::instance($method);
    }


    /**
     * @param string $value
     *
     * @return $this
     */
    public static function instance($value)
    {
        if (!isset(static::$instances[$value])) {
            static::$instances[$value] = new static($value);
        }

        return static::$instances[$value];
    }
}