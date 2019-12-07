<?php declare(strict_types=1);
namespace SpareParts\Enum\Tests\Mapping;

use PHPUnit\Framework\TestCase;
use SpareParts\Enum\Mapping\AnnotationsAnalyzer;

class AnnotationsAnalyzerTest extends TestCase
{

    /**
     * @test
     * @dataProvider phpdocProvider
     */
    public function correctDocWorks(string $phpdoc, array $values)
    {
        $analyzer = new AnnotationsAnalyzer();

        $this->assertEquals($values, $analyzer->analyzeValuesFromDocblock($phpdoc));
    }

    public function phpdocProvider()
    {
        return [
            'correct' => [
                "/**
 * @method static VALUE_ONE()
 * @method static VALUE_TWO()
*/",
                ["VALUE_ONE", "VALUE_TWO"],
            ],

            'whitespace bonanza' => [
                "/**
 * @method static             VALUE_ONE()
*    @method      static VALUE_TWO()    
    */",
                ["VALUE_ONE", "VALUE_TWO"],
            ],

            'various syntax' => [
                "/**
 * @method static VALUE_ONE()
 * @method static Enum VALUE_TWO()
 * @method VALUE_THREE
*/",
                ["VALUE_ONE", "VALUE_TWO", "VALUE_THREE"],
            ],

            'extra stuff is ignored' => [
                "/** THIS IS DOC

 * @method static VALUE_ONE()
 * @method static VALUE_TWO()
 * @throws Exception
 * @since 2004
*/",
                ["VALUE_ONE", "VALUE_TWO"],
            ],

            'empty doc is empty' => [
                '',
                []
            ]
        ];
    }
}