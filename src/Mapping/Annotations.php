<?php declare(strict_types=1);
namespace SpareParts\Enum\Mapping;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;

class Annotations
{
    /** @var AnnotationsAnalyzer */
    static private $analyzer;


    /**
     * @return string[]
     * @throws \InvalidArgumentException
     */
    public static function loadEnumValues(string $classname): array
    {
        try {
            $reflection = new \ReflectionClass($classname);
        } catch (\ReflectionException $exception) {
            throw new \InvalidArgumentException("Problem with fetching reflection of class: `" . var_export($classname, true) . "`", 0, $exception);
        }

        $analyzer = static::getAnalyzer();
        $docblock = $reflection->getDocComment() ?: '';
        return $analyzer->analyzeValuesFromDocblock($docblock);
    }

    private static function getAnalyzer(): AnnotationsAnalyzer
    {
        if (!static::$analyzer) {
            static::$analyzer = new AnnotationsAnalyzer();
        }
        return static::$analyzer;
    }

}