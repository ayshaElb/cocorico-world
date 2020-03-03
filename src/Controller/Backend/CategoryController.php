<?php


namespace App\Controller\Backend;


use App\Entity\Category;
use App\Form\Backend\AddCategoryType;
use App\Form\Backend\EditCategoryType;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CategoryController
 * @package App\Controller\Backend
 * @Route("/backend", name="backend_")
 * 
 */
class CategoryController extends AbstractController
{

     /**
     * @Route("/category", name="category_list", methods={"GET", "POST"})
     */
    public function indexCategory(CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator)
    {
        $q = $request->query->get('q');
        $queryBuilder = $categoryRepository->getAllWithSearchQueryBuilder($q);

        /** @var Category[] $pagination */
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $category = new Category();
        $addCategoryForm = $this->createForm(AddCategoryType::class, $category);
        $addCategoryForm->handleRequest($request);

        if ($addCategoryForm->isSubmitted() && $addCategoryForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'success',
                'La catégorie a été créée avec succès !'
            );

            return $this->redirectToRoute('backend_category_list');
        }

        return $this->render('backend/category/index.html.twig', [
            'pagination' => $pagination,
            'addCategoryForm' => $addCategoryForm->createView(),
        ]);
    }
        /**
     * @Route("/category/delete/{id}", name="category_delete",requirements={"id"="\d+"}, methods={"POST"})
     */
    public function deleteCategory(Request $request, Category $category = null)
    {
        if (!$category) {
            throw $this->createNotFoundException('La catégorie que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            $this->addFlash(
                'success',
                'La catégorie a été supprimé avec succès !'
            );
        }
        else {

            $this->addFlash(
                'error',
                'Une erreur s\'est produite. Veuillez réessayer plus tard !'
            );
        }

        return $this->redirectToRoute('backend_category_list');
    }

    /**
     * @Route("/category/edit/{id}", name="category_edit", requirements={"id"="\d+"}, methods={"POST", "GET"})
     */
    public function editCategory(Request $request, Category $category = null)
    {
        if (!$category) {
            throw $this->createNotFoundException('La catégorie que vous recherchez n\'existe pas !');
        }

        $editCategoryForm = $this->createForm(EditCategoryType::class, $category);
        $editCategoryForm->handleRequest($request);

        if ($editCategoryForm->isSubmitted() && $editCategoryForm->isValid()) {

            $category->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                'La catégorie a bien été modifié !'
            );

            return $this->redirectToRoute('backend_category_list');
        }

        return $this->render('backend/category/edit.html.twig', [
            'category'         => $category,
            'editCategoryForm' => $editCategoryForm->createView(),
        ]);
    }
}