<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/category/{category}')]
    public function categoryDisplay(
        string $category,
        RecipeRepository $recipeRepository
    )
    {
        $recipes = $recipeRepository->getRecipesByCategory($category);
        return $this->render('home/show.html.twig', [
            'recipes' => $recipes,
            'category' => $category
        ]);
    }
}