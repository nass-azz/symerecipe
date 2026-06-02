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
    #[Route('/recipe', name: 'app_recipe', methods: ['GET'])]
    public function index(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // On récupère toutes les recettes et on les pagine (10 par page)
        $recipes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        // On envoie le tout à la vue Twig
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}