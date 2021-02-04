<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("book/{slug}/page")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/new", name="page_new", methods={"GET","POST"})
     * @param Book $book
     * @param Request $request
     * @param Slugify $slugify
     * @return Response
     */
    public function new(Book $book, Request $request, Slugify $slugify): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($page->getTitle());
            $page->setSlug($slug)
                ->setBook($book)
                ->setPageNumber(count($book->getPages()) + 1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('book_show', [
                'slug' => $book->getSlug(),
            ]);
        }

        return $this->render('page/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{page_slug}", name="page_show", methods={"GET"})
     * @ParamConverter("page", class="App\Entity\Page", options={"mapping": {"page_slug": "slug"}})
     * @param Book $book
     * @param Page $page
     * @return Response
     */
    public function show(Book $book, Page $page): Response
    {
        return $this->render('book/pageShow.html.twig', [
            'book' => $book,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/{page_slug}/edit", name="page_edit", methods={"GET","POST"})
     * @ParamConverter("page", class="App\Entity\Page", options={"mapping": {"page_slug": "slug"}})
     * @param Book $book
     * @param Request $request
     * @param Page $page
     * @return Response
     */
    public function edit(Book $book, Request $request, Page $page): Response
    {
        if (!($this->getUser() == $page->getBook()->getAuthor())) {
            throw new AccessDeniedException('Only the author can edit this page!');
        }

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_show', [
                'slug' => $book->getSlug(),
            ]);
        }

        return $this->render('page/edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}", name="page_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Page $page): Response
    {
        if (!($this->getUser() == $page->getBook()->getAuthor())) {
            throw new AccessDeniedException('Only the author can delete this page!');
        }

        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }
}
