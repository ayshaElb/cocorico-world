<?php

namespace App\Controller\Frontend;

use App\Repository\SubcategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UniversMenusController extends AbstractController
{
    /**
     * @Route("/showlist/{id}",
     *     name="showlist",
     *     methods={"GET"},
     *     requirements={"id": "\d+"})
     *
     * @param $id
     *
     * @return Response
     */
    public function index($id, SubcategoryRepository $subcategoryRepository)
    {
        
        $subcategory = $subcategoryRepository->find($id);
        
        return $this->render('frontend/univers_menus/showlist.html.twig', [
            'subcategory' => $subcategory,
        ]);
    }
}