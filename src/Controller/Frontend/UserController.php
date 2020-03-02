<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserUpdateProfilType;

class UserController extends AbstractController
{
    /**
     * @Route("/profil",
     *     name="profil_user",
     *     methods={"GET"})
     *
     * @return Response
     *
     * @throws UnauthorizedHttpException when the user is not logged in
     */
    public function profile()
    {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }

        return $this->render('frontend/user/profil.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/profil/update",
     *     name="profil_update",
     *     methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws UnauthorizedHttpException when the user is not logged in
     */
    public function profileUpdate(Request $request)
    {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        $updateForm = $this->createForm(UserUpdateProfilType::class, $user);
        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash(
                    'success',
                    'Votre modification de profil a bien été enregistrée'
            );

            return $this->redirectToRoute('profil_user');
        }

        return $this->render('frontend/user/profil_update.html.twig', [
            'update_form' => $updateForm->createView()
        ]);
    }
}
