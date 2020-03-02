<?php

namespace App\Controller\Backend;

use App\Entity\Subcategory;
use App\Form\Backend\AddSubcategoryType;
use App\Form\Backend\EditSubcategoryType;
use App\Repository\SubcategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubCategoryController
 * @package App\Controller\Backend
 * @Route("/backend", name="backend_")
 */
class SubCategoryController extends AbstractController
{
    /**
     * @Route("/subcategory",
     *     name="subcategory_list",
     *     methods={"GET", "POST"})
     */
    public function index(SubcategoryRepository $subcategoryRepository, Request $request, PaginatorInterface $paginator)
    {
       
        $queryBuilder = $subcategoryRepository->findAll();

        /** @var Subcategory[] $pagination */
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $subcategory = new Subcategory();
        $addSubcategoryForm = $this->createForm(AddSubcategoryType::class, $subcategory);
        $addSubcategoryForm->handleRequest($request);

        if ($addSubcategoryForm->isSubmitted() && $addSubcategoryForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($subcategory);
            $em->flush();

            $this->addFlash(
                'success',
                'La sous-catégorie a été créée avec succès !'
            );

            return $this->redirectToRoute('backend_subcategory_list');
        }

        return $this->render('backend/sub_category/index.html.twig', [
            'pagination' => $pagination,
            'addSubcategoryForm' => $addSubcategoryForm->createView(),
        ]);
    }

    /**
     * @Route("/subcategory/delete/{id}",
     *     name="subcategory_delete",
     *     requirements={"id"="\d+"},
     *     methods={"POST"})
     */
    public function delete(Request $request, Subcategory $subcategory = null)
    {
        if (!$subcategory) {
            throw $this->createNotFoundException('La sous-catégorie que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($subcategory);
            $em->flush();

            $this->addFlash(
                'success',
                'La sous-catégorie a été supprimé avec succès !'
            );
        }
        else {

            $this->addFlash(
                'error',
                'Une erreur s\'est produite. Veuillez réessayer plus tard !'
            );
        }

        return $this->redirectToRoute('backend_subcategory_list');
    }

    /**
     * @Route("/subcategory/edit/{id}",
     *     name="subcategory_edit",
     *     requirements={"id"="\d+"},
     *     methods={"POST", "GET"})
     */
    public function edit(Request $request, Subcategory $subcategory = null)
    {
        if (!$subcategory) {
            throw $this->createNotFoundException('La sous-catégorie que vous recherchez n\'existe pas !');
        }

        $editSubcategoryForm = $this->createForm(EditSubcategoryType::class, $subcategory);
        $editSubcategoryForm->handleRequest($request);

        if ($editSubcategoryForm->isSubmitted() && $editSubcategoryForm->isValid()) {

            $subcategory->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                'La sous-catégorie a bien été modifié !'
            );

            return $this->redirectToRoute('backend_subcategory_list');
        }

        return $this->render('backend/sub_category/edit.html.twig', [
            'subcategory'         => $subcategory,
            'editSubcategoryForm' => $editSubcategoryForm->createView(),
        ]);
    }
}
