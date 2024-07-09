<?php

declare(strict_types=1);

namespace App\Service;

use InvalidArgumentException;

class RecipeIntepreterService
{
    private array $allowedTags = ['h2', 'h3', 'ol', 'ul', 'li', 'p'];

    public function encodeRecipeToJson(string $recipe): string
    {
        $recipeArray = explode('#&token::', $recipe);
        array_shift($recipeArray);
        array_pop($recipeArray);

        $tagArray = [];
        $contentArray = [];
        $loopIterator = 0;

        foreach($recipeArray as $element)
        {
            if($loopIterator % 2 == 0){

                if(!in_array($element, $this->allowedTags))
                    throw new InvalidArgumentException("Tag $element nie jest wspierany.");

                $tagArray[] = $element;

            }
            else
                $contentArray[] = $element;

                $loopIterator++;
        }

        if($loopIterator % 2 === 1)
            throw new InvalidArgumentException("Niepoprawna ilość tagów");

        return json_encode([
            'tags' => $tagArray,
            'contents' => $contentArray
        ]);
    }
}