<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this
            ->addRecipe($manager, 'recipe1', 'content1', ['breakfast', 'dinner'])
            ->addRecipe($manager, 'recipe2', 'content2', ['breakfast', 'dinner'])
            ->addRecipe($manager, 'recipe3', 'content3', ['pizza', 'dinner'])
            ->addRecipe($manager, 'recipe4', 'content4', ['pizza', 'dinner']);

        $manager->flush();
    }

    public function addRecipe(
        ObjectManager $manager,
        string $name, 
        string $content, 
        array $category
        ): self
    {
        $recipe = new Recipe;
        $recipe
            ->setName($name)
            ->setContent($content)
            ->setCategory($category)
            ->setCreatedAt(new DateTimeImmutable());

        $manager->persist($recipe);
        return $this;
    }
}
