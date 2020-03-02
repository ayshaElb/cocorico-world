<?php

namespace App\Controller\Backend;

use App\Entity\Univers;
use App\Form\Backend\AddUniversType;
use App\Form\Backend\EditUniversType;
use App\Repository\UniversRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UniversController
 * @package App\Controller\Backend
 * @Route("/backend", name="backend_")
 */
class UniversController extends AbstractController
{
    /**
     * @Route("/univers",
     *     name="univers_list",
     *     methods={"GET", "POST"})
     */
    public function index(UniversRepository $universRepository, Request $request, PaginatorInterface $paginator)
    {
       
        $queryBuilder = $universRepository->findAll();


        /** @var Univers[] $pagination */
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $univers = new Univers();
        $addUniversForm = $this->createForm(AddUniversType::class, $univers);
        $addUniversForm->handleRequest($request);

        if ($addUniversForm->isSubmitted() && $addUniversForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($univers);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'univers a été créée avec succès !'
            );

            return $this->redirectToRoute('backend_univers_list');
        }

        return $this->render('backend/univers/index.html.twig', [
            'pagination' => $pagination,
            'addUniversForm' => $addUniversForm->createView(),
        ]);
    }

    /**
     * @Route("/univers/delete/{id}",
     *     name="univers_delete",
     *     requirements={"id"="\d+"},
     *     methods={"POST"})
     */
    public function delete(Request $request, Univers $univers = null)
    {
        if (!$univers) {
            throw $this->createNotFoundException('L\'univers que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($univers);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'univers a été supprimé avec succès !'
            );
        }
        else {

            $this->addFlash(
                'error',
                'Une erreur s\'est produite. Veuillez réessayer plus tard !'
            );
        }

        return $this->redirectToRoute('backend_univers_list');
    }

    /**
     * @Route("/univers/edit/{id}",
     *     name="univers_edit",
     *     requirements={"id"="\d+"},
     *     methods={"POST", "GET"})
     */
    public function edit(Request $request, Univers $univers = null)
    {
        if (!$univers) {
            throw $this->createNotFoundException('L\'univers que vous recherchez n\'existe pas !');
        }

        $editUniversForm = $this->createForm(EditUniversType::class, $univers);
        $editUniversForm->handleRequest($request);

        if ($editUniversForm->isSubmitted() && $editUniversForm->isValid()) {

            $univers->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                'L\'univers a bien été modifié !'
            );

            return $this->redirectToRoute('backend_univers_list');
        }

        return $this->render('backend/univers/edit.html.twig', [
            'univers'         => $univers,
            'editUniversForm' => $editUniversForm->createView(),
        ]);
    }
}
