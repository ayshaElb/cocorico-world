<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\Backend\AddUserType;
use App\Form\Backend\EditUserType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Backend
 * @Route("/backend", name="backend_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user",
     *     name="user_list",
     *     methods={"GET", "POST"})
     */
    public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginator)
    {
        
        $queryBuilder = $userRepository->findAll();


        /** @var User[] $pagination */
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $user = new User();
        $addUserForm = $this->createForm(AddUserType::class, $user);
        $addUserForm->handleRequest($request);

        if ($addUserForm->isSubmitted() && $addUserForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a été créé avec succès !'
            );

            return $this->redirectToRoute('backend_user_list');
        }

        return $this->render('backend/user/index.html.twig', [
            'pagination' => $pagination,
            'addUserForm' => $addUserForm->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/{id}",
     *     name="user_delete",
     *     requirements={"id"="\d+"},
     *     methods={"POST"})
     */
    public function delete(Request $request, User $user = null)
    {
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a été supprimé avec succès !'
            );
        }
        else {

            $this->addFlash(
                'error',
                'Une erreur s\'est produite. Veuillez réessayer plus tard !'
            );
        }

        return $this->redirectToRoute('backend_user_list');
    }

    /**
     * @Route("/user/edit/{id}",
     *     name="user_edit",
     *     requirements={"id"="\d+"},
     *     methods={"POST", "GET"})
     */
    public function edit(Request $request,  User $user = null)
    {
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur que vous recherchez n\'existe pas !');
        }

        $editUserForm = $this->createForm(EditUserType::class, $user);
        $editUserForm->handleRequest($request);

        if ($editUserForm->isSubmitted() && $editUserForm->isValid()) {

            $user->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a bien été modifié !'
            );

            return $this->redirectToRoute('backend_user_list');
        }

        return $this->render('backend/user/edit.html.twig', [
            'user'         => $user,
            'editUserForm' => $editUserForm->createView(),
        ]);
    }
}
