<?php
namespace SpareParts\Enum\Converter;

use SpareParts\Enum\Enum;

/**
 * Converts Enum values to custom string values using map
 *
 * Usage:
 * $converter = new MapConverter(
 *      [
 *          'totally_opened' => WindowState::OPEN(),
 *          'totally_closed' => WindowState::CLOSED(),
 *      ]
 * );
 *
 * $converter->toEnum('totally_closed') // returns WindowState::CLOSED() instance
 *
 * $converter->fromEnum(WindowState::CLOSED()) // returns 'totally_closed' string
 */
class MapConverter implements IConverter
{
    /**
     * @var array mapValue => enumInstance
     */
    protected $scalarMap = [];

    /**
     * @var array enumString => mapValue
     */
    protected $enumMap = [];

    /**
     * @param array $map { [enumValue: string]: Enum }
     * @throws ConverterSetupException
     */
    public function __construct(array $map)
    {
        $this->enumMap = $map;
        foreach ($map as $value => $enum) {
            if (!($enum instanceof Enum)) {
                $ident = is_object($enum) ? get_class($enum) : print_r($enum, true);
                throw new ConverterSetupException(sprintf('Class %s is not a valid Enum. (doesnt extend Enum class)', $ident));
            }
            $this->scalarMap[(string) $enum] = $value;
        }
    }

    /**
     * @param mixed $value
     * @return Enum
     * @throws UnableToConvertException
     */
    public function toEnum($value): Enum
    {
        if (!is_scalar($value)) {
            throw new UnableToConvertException(sprintf('Unable to convert: Value %s is not a scalar value.', print_r($value, true)));
        }

        if (!isset($this->enumMap[$value])) {
            throw new UnableToConvertException(sprintf('Unable to convert: Value %s not found in possible options: [%s]', $value, implode(', ', array_keys($this->enumMap))));
        }

        return $this->enumMap[$value];
    }

    /**
     * @param Enum $enum
     * @return string
     * @throws UnableToConvertException
     */
    public function fromEnum(Enum $enum): string
    {
        if (!isset($this->scalarMap[(string) $enum])) {
            throw new UnableToConvertException(
                sprintf(
                    'Unable to convert: Enum %s::%s() not found in possible options: [%s]',
                    get_class($enum),
                    (string) $enum,
                    implode(', ', array_keys($this->enumMap))
                )
            );
        }

        return $this->scalarMap[(string) $enum];
    }
}