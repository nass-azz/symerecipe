<?php

namespace App\DataFixtures;
use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use faker\Generator;
use Faker\Factory;
use App\Entity\Recipe;

class AppFixtures extends Fixture
{
    private Generator $faker;
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // 1. Création des Ingrédients
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->word() . ' ' . $i)
                ->setPrice(mt_rand(1, 100) / 10); // Prix entre 0.1€ et 10€

            $manager->persist($ingredient);
            $ingredients[] = $ingredient; // On les stocke pour les recettes après
        }

        // 2. Création des Recettes
        for ($j = 0; $j < 25; $j++) {
            $recipe = new Recipe();
            $recipe->setName($faker->sentence(2))
                ->setTime(mt_rand(0, 1) === 1 ? mt_rand(10, 1440) : null)
                ->setNbPersons(mt_rand(0, 1) === 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) === 1 ? mt_rand(1, 5) : null)
                ->setDescription($faker->text(200))
                ->setPrice(mt_rand(0, 1) === 1 ? mt_rand(5, 999) : null)
                ->setIsFavorite(mt_rand(0, 1) === 1);

            // On ajoute des ingrédients aléatoires à la recette (relation ManyToMany)
            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recipe);
        }

        // 3. On envoie tout en base de données d'un coup
        $manager->flush();
    }
}
