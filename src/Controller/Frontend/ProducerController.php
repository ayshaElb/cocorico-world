<?php

namespace App\Controller\Frontend;


use App\Entity\Producer;
use App\Form\ProducerType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ProducerController extends AbstractController
{
    /**
     * @Route("/producer/profil",
     *     name="producer_profil",
     *     methods={"GET"})
     *
     * @return Response
     *
     * @throws UnauthorizedHttpException when the user is not registered as a producer
     */
    public function index()
    {
        $producer = $this->getUser()->getProducer();

        if (!$producer) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous inscrire en tant que producteur pour accéder à cette page !');
        }

        return $this->render('frontend/producer/profil.html.twig', [
            'producer' => $producer,
        ]);
    }

    /**
     * @Route("/producer/registration",
     *     name="producer_registration",
     *     methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $producer = new Producer();
        $producerForm = $this->createForm(ProducerType::class, $producer);
        $producerForm->handleRequest($request);

        if ($producerForm->isSubmitted() && $producerForm->isValid()) {

            // on récupère l'image du formaulaire
            $avatar = $producerForm->get('avatar')->getData();
            if ($avatar) {
                // on récupère le nom de l'avatar saisi dans le formulaire
                $avatarName = $fileUploader->upload($avatar);
                 // on envoi le nom de l'avatar à la bdd
                $producer->setAvatar($avatarName);
            }

            $producer->setUser($this->getUser());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($producer);
            $manager->flush();

            $this->addFlash(
                'success',
                'Enregistrement effectué'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('frontend/producer/registration.html.twig', [
            'producer' => $producer,
            'form'     => $producerForm->createView(),
        ]);
    }

    /**
     * @Route("/producer/{id<\d+>}",
     *     name="producer_show",
     *     methods={"GET"})
     *
     * @param Producer|null $producer
     *
     * @return Response
     *
     * @throws NotFoundHttpException when the desired producer is not existing
     */
    public function show(Producer $producer = null)
    {
        if (!$producer) {

            throw $this->createNotFoundException('La page que vous recherchez n\'existe pas');
        }

        return $this->render('frontend/producer/show.html.twig', [
            'producer' => $producer,
        ]);
    }

    /**
     * @Route("/producer/edit",
     *     name="producer_edit",
     *     methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws UnauthorizedHttpException when the user is not registered as a producer
     */
    public function edit(Request $request, FileUploader $fileUploader)
    {
        if (!$producer = $this->getUser()->getProducer()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous inscrire en tant que producteur pour accéder à cette page !');
        }
        $producerForm = $this->createForm(ProducerType::class, $producer);
        $producerForm->handleRequest($request);

        if ($producerForm->isSubmitted() && $producerForm->isValid()) {
            
             // on récupère l'image du formaulaire
             $avatar = $producerForm->get('avatar')->getData();
             if ($avatar) {
                 // on récupère le nom de l'avatar saisi dans le formulaire
                 $avatarName = $fileUploader->upload($avatar);
                  // on envoi le nom de l'avatar à la bdd
                 $producer->setAvatar($avatarName);
             }
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash(
                'success',
                'Mise à jour effectuée'
            );

            return $this->redirectToRoute('producer_profil');
        }
    
        return $this->render('frontend/producer/edit.html.twig', [
            'producer' => $producer,
            'form'     => $producerForm->createView(),
        ]);
    }
}