<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientTypePhpType;
use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(
        IngredientRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/new', name: 'ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, \Doctrine\ORM\EntityManagerInterface $manager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientTypePhpType::class, $ingredient);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success', 'Ingrédient ajouté avec succès !');
            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/ingredient/{id}/edit', name: 'ingredient_edit', methods: ['GET', 'POST'])] 
    public function edit (
        request $request,
        Ingredient $ingredient,
        \Doctrine\ORM\EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(IngredientTypePhpType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->flush();
            
            $this->addFlash('success', 'Ingrédient modifié avec succès !');
            return $this->redirectToRoute('app_ingredient');
        }
        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form,
            'ingredient' => $ingredient,
        ]);
        }

        #[Route('/ingredient/{id}/delete', name: 'ingredient_delete', methods: ['POST', 'GET'])]
    public function delete(Ingredient $ingredient, \Doctrine\ORM\EntityManagerInterface $manager): Response
    {
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash('success', 'Ingrédient supprimé avec succès !');
        return $this->redirectToRoute('app_ingredient');
    }

    
}