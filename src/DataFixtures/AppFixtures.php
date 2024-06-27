<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Adding dummy data
        $this
            ->addRecipe($manager, 'recipe1', 'content1', ['breakfast', 'dinner'])
            ->addRecipe($manager, 'recipe2', 'content2', ['breakfast', 'dinner'])
            ->addRecipe($manager, 'recipe3', 'content3', ['pizza', 'dinner'])
            ->addRecipe($manager, 'recipe4', 'content4', ['pizza', 'dinner']);

        //Admin acc
        $admin = new User;
        $admin
            ->setRoles(['USER_ADMIN'])
            ->setUsername('admin')
            ->setPassword('$2y$13$K6ismNdXeb3D9jrMfiK6GONfo88SVFzjA9w1JSAiLi2iXbQ2HuzcO'); //Set default password: 'admin'
        $manager->persist($admin);

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
