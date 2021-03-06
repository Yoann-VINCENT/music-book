<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function index(BookRepository $bookRepository): Response
    {
        $lastBook = $bookRepository->findBy(array(),array('id'=>'DESC'),1,0);
        return $this->render('default/index.html.twig', [
            'lastBook' => $lastBook,
        ]);
    }

    /**
     * @Route("/my-profile", name="app_profile")
     */
    public function myProfile()
    {
        if (!$this->getUser()) {
            throw new AccessDeniedException('You must be connected');
        }

        $user = $this->getUser();

        return $this->render('profile/myProfile.html.twig', [
            'user' => $user,
        ]);
    }
}