<?php

namespace App\Controller\Backend;

use App\Entity\Product;
use App\Form\Backend\AddProductType;
use App\Form\Backend\EditProductType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller\Backend
 * @Route("/backend", name="backend_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/product",
     *     name="product_list",
     *     methods={"GET", "POST"})
     */
    public function index(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator)
    {
        
        $queryBuilder = $productRepository->findAll();


        /** @var Product[] $pagination */
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $product = new Product();
        $addProductForm = $this->createForm(AddProductType::class, $product);
        $addProductForm->handleRequest($request);

        if ($addProductForm->isSubmitted() && $addProductForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'success',
                'Le produit a été créé avec succès !'
            );

            return $this->redirectToRoute('backend_product_list');
        }

        return $this->render('backend/product/index.html.twig', [
            'pagination' => $pagination,
            'addProductForm' => $addProductForm->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}",
     *     name="product_delete",
     *     requirements={"id"="\d+"},
     *     methods={"POST"})
     */
    public function delete(Request $request, Product $product = null)
    {
        if (!$product) {
            throw $this->createNotFoundException('Le produit que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

            $this->addFlash(
                'success',
                'Le produit a été supprimé avec succès !'
            );
        }
        else {

            $this->addFlash(
                'error',
                'Une erreur s\'est produite. Veuillez réessayer plus tard !'
            );
        }

        return $this->redirectToRoute('backend_product_list');
    }

    /**
     * @Route("/product/edit/{id}",
     *     name="product_edit",
     *     requirements={"id"="\d+"},
     *     methods={"POST", "GET"})
     */
    public function edit(Request $request, Product $product = null)
    {
        if (!$product) {
            throw $this->createNotFoundException('Le produit que vous recherchez n\'existe pas !');
        }

        $editProductForm = $this->createForm(EditProductType::class, $product);
        $editProductForm->handleRequest($request);

        if ($editProductForm->isSubmitted() && $editProductForm->isValid()) {

            $product->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                'Le produit a bien été modifié !'
            );

            return $this->redirectToRoute('backend_product_list');
        }

        return $this->render('backend/product/edit.html.twig', [
            'product'         => $product,
            'editProductForm' => $editProductForm->createView(),
        ]);
    }
}

