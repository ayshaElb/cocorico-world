<?php


namespace App\Controller\Backend;


use App\Entity\Producer;
use App\Form\Backend\AddProducerType;
use App\Form\Backend\EditProducerType;
use App\Repository\ProducerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProducerController
 * @package App\Controller\Backend
 * @Route( name="backend_")
 * 
 */
class ProducerController extends AbstractController
{

     /**
     * @Route("/producers", name="producer_list", methods={"GET", "POST"})
     */
    public function indexProducer(ProducerRepository $producerRepository, Request $request, PaginatorInterface $paginator)
    {
        
        $queryBuilder = $producerRepository->findAll();

        /** @var Producer[] $pagination */
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $producer = new Producer();
        $addProducerForm = $this->createForm(AddProducerType::class, $producer);
        $addProducerForm->handleRequest($request);

        if ($addProducerForm->isSubmitted() && $addProducerForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($producer);
            $em->flush();

            $this->addFlash(
                'success',
                'Le producteur a été créée avec succès !'
            );

            return $this->redirectToRoute('backend_producer_list');
        }

        return $this->render('backend/producer/index.html.twig', [
            'pagination' => $pagination,
            'addProducerForm' => $addProducerForm->createView(),
        ]);
    }
        /**
     * @Route("/producer/delete/{id}", name="producer_delete",requirements={"id"="\d+"}, methods={"POST"})
     */
    public function deleteProducer(Request $request, Producer $producer = null)
    {
        if (!$producer) {
            throw $this->createNotFoundException('Le producteur que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($producer);
            $em->flush();

            $this->addFlash(
                'success',
                'Le producteur a été supprimé avec succès !'
            );
        }
        else {

            $this->addFlash(
                'error',
                'Une erreur s\'est produite. Veuillez réessayer plus tard !'
            );
        }

        return $this->redirectToRoute('backend_producer_list');
    }

    /**
     * @Route("/producer/edit/{id}", name="producer_edit", requirements={"id"="\d+"}, methods={"POST", "GET"})
     */
    public function editProducer(Request $request, Producer $producer = null)
    {
        if (!$producer) {
            throw $this->createNotFoundException('Le producteur que vous recherchez n\'existe pas !');
        }

        $editProducerForm = $this->createForm(EditProducerType::class, $producer);
        $editProducerForm->handleRequest($request);

        if ($editProducerForm->isSubmitted() && $editProducerForm->isValid()) {

            $producer->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                'Le producteur a bien été modifié !'
            );

            return $this->redirectToRoute('backend_producer_list');
        }

        return $this->render('backend/producer/edit.html.twig', [
            'producer'         => $producer,
            'editProducerForm' => $editProducerForm->createView(),
        ]);
    }
}