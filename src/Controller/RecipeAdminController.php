<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class RecipeAdminController extends AbstractController
{
    #[Route('/', name: 'app_recipe_admin_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe_admin/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_admin_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if($image)
            {
                try
                {
                    $name = bin2hex(random_bytes(40)) .'.'. $image->guessExtension();
                    $image->move('uploads/images', $name);
                    $recipe->setImage($name);
                }
                catch(FileException $e)
                {
                    return $this->redirectToRoute('app_recipe_admin_index', [], Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
                }
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe_admin/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_admin_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe_admin/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_admin_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Recipe $recipe, 
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if($file)
            {
                $name = bin2hex(random_bytes(40)) .'.'. $file->guessExtension();
                try
                {
                    $file->move('uploads/images', $name);
                }
                catch(FileException $e)
                {
                    return $this->redirectToRoute('app_recipe_admin_index', [], Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
                }
                $recipe->setImage($name);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe_admin/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_admin_delete', methods: ['POST'])]
    public function delete(
        Request $request, Recipe $recipe, 
        EntityManagerInterface $entityManager
        ): Response
    {
        if($recipe->getImage())
        {
            try
            {
                unlink(__DIR__.'/../../public/uploads/images/'.$recipe->getImage());
            }
            catch(\Exception $e)
            {
                return $this->redirectToRoute('app_recipe_admin_edit', ['id' => $recipe->getId()], Response::HTTP_BAD_REQUEST);
            }
        }
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
