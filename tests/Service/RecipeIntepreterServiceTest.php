<?php

namespace App\Tests\Service;

use App\Service\RecipeIntepreterService;
use PHPUnit\Framework\TestCase;

class RecipeIntepreterServiceTest extends TestCase
{
    
    /**
     * @dataProvider dataJsonEncryption
     */
    public function testJsonEncryption($text, $expectedJson): void
    {
        $recipeIntepreterService = new RecipeIntepreterService();
        $result = $recipeIntepreterService->encodeRecipeToJson($text);

        $this->assertEquals($result, \json_encode($expectedJson));
    }

    /**
     * @dataProvider dataJsonEncryptionFail
     */
    public function testJsonEncryptionFail($text, $expectedMessage)
    {
        $this->expectExceptionMessage($expectedMessage);
        $recipeIntepreterService = new RecipeIntepreterService();
        $recipeIntepreterService->encodeRecipeToJson($text);
    }

    public function dataJsonEncryption(): array
    {
        return [
            [
                "#&token::p#&token::A#&token::", 
                [
                    'tags' => ['p'],
                    'contents' => ['A']
                ]
            ],
            [
                '#&token::p#&token::B#&token::h2#&token::test#&token::',
                [
                    'tags' => ['p', 'h2'],
                    'contents' => ['B', 'test']
                ]   
            ]
        ];
    }

    public function dataJsonEncryptionFail(): array
    {
        return [
            ['#&token::s#&token::A#&token::', "Tag s nie jest wspierany."],
            ['#&token::p#&token::A#&token::p#&token::', 'Niepoprawna ilość tagów']
        ];
    }
}
