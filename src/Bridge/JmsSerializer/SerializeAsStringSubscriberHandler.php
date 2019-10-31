<?php declare(strict_types=1);
namespace SpareParts\Enum\Bridge\JmsSerializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use SpareParts\Enum\Converter\LowercaseConverter;
use SpareParts\Enum\Enum;

class SerializeAsStringSubscriberHandler implements SubscribingHandlerInterface
{
	public static function getSubscribingMethods(): array
	{
		return [
			[
				'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
				'format' => 'json',
				'type' => 'enum',
				'method' => 'serializeEnum',
			]
		];
	}

	public function serializeEnum(VisitorInterface $visitor, Enum $enum, array $type, Context $context)
	{
		$converter = new LowercaseConverter(get_class($enum));
		return $visitor->visitString($converter->fromEnum($enum), $type, $context);
	}
}
