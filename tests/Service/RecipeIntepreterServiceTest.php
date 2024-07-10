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

        $this->assertEquals($result, $expectedJson);
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

    /**
     * @dataProvider dataGetTagsFromRecipe
     */
    public function testGetTagsFromRecipe($jsonRecipe, $expectedArray)
    {
        $recipeIntepreterService = new RecipeIntepreterService();
        $recipeTags = $recipeIntepreterService->getTagsFromRecipe($jsonRecipe);

        $this->assertEquals($expectedArray, $recipeTags);
    }

    /**
     * @dataProvider dataGetContentsFromRecipe
     */
    public function testGetContentsFromRecipe($jsonRecipe, $expectedArray)
    {
        $recipeIntepreterService = new RecipeIntepreterService();
        $recipeTags = $recipeIntepreterService->getContentsFromRecipe($jsonRecipe);

        $this->assertEquals($expectedArray, $recipeTags);
    }

    public function dataJsonEncryption(): array
    {
        return [
            [
                "#&token::p#&token::A#&token::", '{"tags":["p"],"contents":["A"]}'
            ],
            [
                '#&token::p#&token::B#&token::h2#&token::test#&token::', '{"tags":["p","h2"],"contents":["B","test"]}'  
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

    public function dataGetTagsFromRecipe(): array
    {
        return [
            [
                '{"tags":["p","h2"],"contents":["A","B"]}',
                ['p', 'h2']
            ]
        ];
    }

    public function dataGetContentsFromRecipe(): array
    {
        return [
            [
                '{"tags":["p","h2"],"contents":["A","B"]}',
                ['A', 'B']
            ]
        ];
    }
}
