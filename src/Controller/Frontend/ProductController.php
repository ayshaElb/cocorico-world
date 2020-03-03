<?php
namespace App\Controller\Frontend;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id<\d+>}",
     *     name="product_show",
     *     methods={"GET"})
     *
     * @param ProductRepository $productRepository
     * @param Product|null      $product
     *
     * @return Response
     *
     * @throws NotFoundHttpException when the desired product is not existing
     */
   public function show(ProductRepository $productRepository, Product $product = null)
   {
       if (!$product) {

           throw $this->createNotFoundException('La page que vous recherchez n\'existe pas !');
       }
       $products = $productRepository->getProductsWithoutTheOneDisplayed($product->getId());

       return $this->render('frontend/product/show.html.twig', [
           'product'          => $product,
           'product_producer' => $products,
       ]);
   }

    /**
     * @Route("/product/new",
     *     name="product_new",
     *     methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws AccessDeniedException when the user is not registered as a producer
     */
   public function new(Request $request)
   {
       if (!$producer = $this->getUser()->getProducer()) {

           throw $this->createAccessDeniedException('Vous devez être producteur pour accéder à cette page !');
       }
       $product = new Product();
       $productForm = $this->createForm(ProductType::class, $product);
       $productForm->handleRequest($request);

       if ($productForm->isSubmitted() && $productForm->isValid()) {
           $product->setProducer($producer);
           $manager = $this->getDoctrine()->getManager();
           $manager->persist($product);
           $manager->flush();

           $this->addFlash(
               'success',
               'Votre produit a bien été enregistré'
           );

           return $this->redirectToRoute('product_show', [
               'id' => $product->getId(),
           ]);
       }

       return $this->render('/frontend/product/new.html.twig', [
           'product' => $product,
           'form'    => $productForm->createView()
       ]);
   }

    /**
     * @Route("/product/{id<\d+>}/edit",
     *     name="product_edit",
     *     methods={"GET","POST"})
     *
     * @param Request      $request
     * @param Product|null $product
     *
     * @return RedirectResponse|Response
     *
     * @throws NotFoundHttpException when the desired product does not exist
     */
    public function edit(Request $request, Product $product = null)
    {
        if (!$product) {

            throw $this->createNotFoundException('La page que vous recherchez n\'existe pas !');
        }
        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash(
                'info',
                'Mise à jour effectuée'
            );

            return $this->redirectToRoute('product_show', [
                'id' => $product->getId()
            ]);
        }

        return $this->render('frontend/product/edit.html.twig', [
            'product' => $product,
            'form'    => $productForm->createView(),
        ]);
    }

    /**
     * @Route("product/disable/{id<\d+>}",
     *     name = "disable_product",
     *     methods={"POST"})
     *
     * @param ObjectManager $manager
     * @param Product|null  $product
     *
     * @return RedirectResponse
     *
     * @throws NotFoundHttpException when the desired product does not exist
     */
    public function toggleProduct(ObjectManager $manager, Product $product = null)
    {
        if (!$product) {

            throw $this->createNotFoundException('La page que vous recherchez n\'existe pas');
        }
        if ($product->getProducer() != $this->getUser()->getProducer()) {

            throw $this->createAccessDeniedException();
        }

        $toggle = !$product->getEnable();
        $product->setEnable($toggle);
        $manager->flush();

        $this->addFlash(
            $toggle ? 'success' : 'danger',
            $toggle ? 'Le produit a bien été debloquée' : 'La produit a bien été archivé'
        );

        return $this->redirectToRoute('producer_profil', [
            'id' => $product->getProducer()->getId()
        ]);
    }

    /**
     * @Route("/product/{id<\d+>}/delete",
     *     name="product_delete",
     *     methods={"POST"})
     *
     * @param Request      $request
     * @param Product|null $product
     *
     * @return RedirectResponse
     *
     * @throws NotFoundHttpException when the desired product does not exist
     */
    public function delete(Request $request, Product $product = null)
    {
        if (!$product) {
            throw $this->createNotFoundException('Le produit que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('_delete-item', $submittedToken)) {

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
                'danger',
                'Une erreur s\'est produite, veuillez réessayer ultérieurement'
            );
        }

        return $this->redirectToRoute('producer_profil');
    }
}