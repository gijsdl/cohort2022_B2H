<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/', name: 'app_book')]
    public function index(EntityManagerInterface $em): Response
    {

        $books = $em->getRepository(Book::class)->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }

    #[Route('/create-book', name: 'create_book')]
    public function createBook(EntityManagerInterface $em, Request $request):Response
    {
        $form = $this->createForm(BookType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $book = $form->getData();
            $em->persist($book);
            $em->flush();
            $this->addFlash('success', 'Het boek is toegevoegd');
            return $this->redirectToRoute('app_book');
        }

        return $this->render('book/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update-book/{id}', name: 'update_book')]
    public function updateBook(EntityManagerInterface $em, Request $request, int $id):Response
    {
        $book = $em->getRepository(Book::class)->find($id);
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $book = $form->getData();
            $em->persist($book);
            $em->flush();
            $this->addFlash('success', 'Het boek is aangepast');
            return $this->redirectToRoute('app_book');
        }

        return $this->render('book/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete-book/{id}', name: 'delete_book')]
    public function deleteBook(EntityManagerInterface $em, int $id): Response
    {
        $book = $em->getRepository(Book::class)->find($id);

        $em->remove($book);
        $em->flush();
        $this->addFlash('danger', 'Het book is verwijderd');
        return $this->redirectToRoute('app_book');

    }
}
