<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'app_recipe', methods: ['GET', 'POST'])]
    public function index(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $recipes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipe/new', name: 'recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request,
        \Doctrine\ORM\EntityManagerInterface $manager
    ):Response {
        $recipe = new \App\Entity\Recipe();

        $form = $this->createForm(\App\Form\RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success', 'Votre recette a été ajoutée avec succès !');
            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}   