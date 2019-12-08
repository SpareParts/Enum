<?php declare(strict_types=1);
namespace SpareParts\Enum\Mapping;

class AnnotationsAnalyzer
{

    /**
     * @return string[]
     */
    public function analyzeValuesFromDocblock(string $classdocblock): array
    {
        $classdocblock = explode("\n", $classdocblock);

        $values = [];
        foreach ($classdocblock as $docLine) {
            preg_match_all('/^\s*\*\s*@method(\W+(\w+))+/', $docLine, $matches);
            if ($matches[0]) {
                $match = array_pop($matches);
                $value = reset($match);
                $values[] = $value;
            }
        }
        return $values;
    }
}