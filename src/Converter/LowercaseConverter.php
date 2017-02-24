<?php
namespace SpareParts\Enum\Converter;

use SpareParts\Enum\Enum;

/**
 * Converts Enum to lowercase string.
 *
 * Usage:
 *
 * $converter = new LowercaseConverter(WindowState::class);
 *
 * $converter->toEnum('closed') // returns WindowState::CLOSED() instance
 *
 * $converter->fromEnum(WindowState::CLOSED()) // returns 'closed'

 */
class LowercaseConverter implements IConverter
{
    /**
     * @var string
     */
    protected $enumClass;

    /**
     * @param string $enumClass
     * @throws ConverterSetupException
     */
    public function __construct($enumClass)
    {
        if (!is_subclass_of($enumClass, Enum::class)) {
            throw new ConverterSetupException("Class ${enumClass} is not a valid Enum. (doesnt extend Enum class)");
        }
        $this->enumClass = $enumClass;
    }

    /**
     * @param mixed $value
     * @return Enum
     */
    public function toEnum($value)
    {
        return call_user_func_array([$this->enumClass, 'instance'], [strtoupper($value)]);
    }

    /**
     * @param Enum $enum
     * @return string
     * @throws UnableToConvertException
     */
    public function fromEnum(Enum $enum)
    {
        if (!($enum instanceof $this->enumClass)) {
            throw new UnableToConvertException(sprintf('Enum %s is not of type %s.', get_class($enum), $this->enumClass));
        }
        return strtolower((string) $enum);
    }
}