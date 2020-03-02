<?php

namespace App\Controller\Frontend;

use App\Form\ContactFormType;
use App\Repository\ProductRepository;
use App\Repository\ProducerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ProducerRepository $producerRepository, ProductRepository $productRepository )
    {
        $producers = $producerRepository->findAll();
        $lastProducts = $productRepository->lastRelease();
       
        return $this->render('frontend/main/index.html.twig', [
            'producers'     => $producers,
            'last_products' => $lastProducts,
        ]);
    }
    /**
     * @Route("/a-propos",
     *     name="company_page",
     *     methods={"GET"})
     *
     * @return Response
     */
    public function showCompanyPresentation()
    {
        return $this->render("frontend/main/company.html.twig");
    }

    /**
     * @Route("/faq",
     *     name="faq_page",
     *     methods={"GET"})
     *
     * @return Response
     */
    public function showFaq()
    {
        return $this->render("frontend/main/faq.html.twig");
    }
  
    /**
     * @Route("/cgv",
     *     name="cgv_page",
     *     methods={"GET"})
     *
     * @return Response
     */
    public function showCgv()
    {
        return $this->render("frontend/main/cgv.html.twig");
    }

    /**
     * @Route("/mentions-legales",
     *     name="legalsmentions_page",
     *     methods={"GET"})
     *
     * @return Response
     */
    public function showLegalsMentions()
    {
        return $this->render("frontend/main/legalsmentions.html.twig");
    }

    /**
     * @Route("/contact",
     *     name="contact",
     *     methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function contact(Request $request)
    {
        $contactForm = $this->createForm(ContactFormType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {

            $this->addFlash(
                'success',
                'L\'email a été envoyé avec succès !'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('frontend/main/contact.html.twig', [
            'contact_form' => $contactForm->createView(),
        ]);
    }
}
