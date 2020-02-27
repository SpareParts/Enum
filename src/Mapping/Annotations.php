<?php declare(strict_types=1);
namespace SpareParts\Enum\Mapping;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use SpareParts\Enum\Converter\IConverter;

class Annotations
{
    /** @var Reader */
    static private $reader;

    /** @var AnnotationsAnalyzer */
    static private $analyzer;

    /** @var IConverter[][] */
    static private $conversionsCache = [];


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

    /**
     * @param string $classname
     * @return IConverter[]
     */
    public static function loadConversions(string $classname): array
    {
        try {
            $reflection = new \ReflectionClass($classname);
        } catch (\ReflectionException $exception) {
            throw new \InvalidArgumentException("Problem with fetching reflection of class: `" . var_export($classname, true) . "`", 0, $exception);
        }

        $reader = static::getReader();
        $annotationList = $reader->getClassAnnotations($reflection);
        foreach ($annotationList as $annotation) {
            if (!$annotation instanceof Conversion) {
                continue;
            }
            $conversionAnnotations[] = $annotation;
        }



    }

    public static function injectReader(Reader $reader): void
    {
        static::$reader = $reader;
    }

    private static function getReader(): Reader
    {
        if (!static::$reader) {
            static::$reader = new AnnotationReader();
        }
        return static::$reader;
    }

    private static function getAnalyzer(): AnnotationsAnalyzer
    {
        if (!static::$analyzer) {
            static::$analyzer = new AnnotationsAnalyzer();
        }
        return static::$analyzer;
    }

}