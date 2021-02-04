<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     * @param Request $request
     * @param Slugify $slugify
     * @return Response
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($book->getTitle());
            $book->setSlug($slug)
                ->setCreatedAt(new \DateTime('now'))
                ->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="book_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function edit(Request $request, Book $book): Response
    {
        if (!($this->getUser() == $book->getAuthor())) {
            throw new AccessDeniedException('Only the author can edit the book!');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/share", name="book_share", methods={"GET","POST"})
     * @param Book $book
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function share(Book $book, MailerInterface $mailer): Response
    {
        if (!$this->getUser()) {
            throw new AccessDeniedException('You must be connected');
        }

        $user = $this->getUser();

        if (!empty($_POST)) {
            $email = (new Email())
                ->from('contact@music-book.com')
                ->to(trim($_POST['email']))
                ->subject($user->getPseudo() . ' send you a new book !')
                ->html($this->renderView('book/shareBookEmail.html.twig', [
                    'book' => $book,
                    'user' => $user,
                    ]));
            $mailer->send($email);
            $this->addFlash('success', 'The link has been send');

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/share.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/fav", name="book_fav")
     * @param Book $book
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function addToFav(Book $book, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user->getFav()->contains($book)) {
            $user->removeFav($book);
        } else {
            $user->addFav($book);
        }
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'isInFav' => $user->isInFav($book)
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function delete(Request $request, Book $book): Response
    {
        if (!($this->getUser() == $book->getAuthor())) {
            throw new AccessDeniedException('Only the author can delete the book!');
        }

        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }
}
