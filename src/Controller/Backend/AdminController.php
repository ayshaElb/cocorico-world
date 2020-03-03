<?php


namespace App\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AdminController
 * @package App\Controller\Backend
 * @Route("/backend", name="backend_")
 * 
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_home")
     */
    public function index()
    {
        return $this->render("backend/index.html.twig");
    }

   

}