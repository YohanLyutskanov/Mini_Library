<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{

    const NUM_RESULTS = 5;

    /**
     * @Route("/book/add", name="add_book_form")
     * @Method("GET")
     * @return Response
     */
    public function add()
    {
//         Is authenticated to add book
        if (!$this->isGranted('ROLE_ADMIN', $this->getUser())) {
            $this->get('session')->getFlashBag()->add('error', 'You are not allowed to add new books');

            return $this->redirectToRoute('show_books');
        }
        $form = $this->createForm(BookType::class);
        return $this->render("book/add",
            [
                'bookForm' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/book/add", name="add_book_process")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function addBookAction(Request $request)
    {
        // Is authenticated to add book
        if (!$this->isGranted('ROLE_ADMIN', $this->getUser())) {
            $this->get('session')->getFlashBag()->add('error', 'You are not allowed to add new books');

            return $this->redirectToRoute('show_books');
        }
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $book->getImage();

            if (!$file) {
                $form->get('image')->addError(new FormError('Image is required'));
            } else {
                $filename = md5($book->getTitle() . "" . $book->getPublishDate()->format("Y-m-d"));
                $file->move($this->get("kernel")->getRootDir() . '/../web/images/bookCovers/', $filename);

                $book->setImage($filename);

                $entityManager = $this->getDoctrine()
                    ->getManager();
                $entityManager->persist($book);
                $entityManager->flush();

                $this->addFlash("info", "Book with title \"" . $book->getTitle() . "\" was added successfully");

                return $this->redirectToRoute("show_books");

            }
        }

        return $this->render("/book/add",
            [
                'bookForm' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/", name="show_books")
     * @param Request $request
     * @return Response
     * @internal param Book[] $books
     *
     */
    public function viewAllBooks(Request $request)
    {
        $paginator = $this->get('knp_paginator');


        $query = $this->getDoctrine()
            ->getRepository("AppBundle:Book")
            ->createQueryBuilder('b')
            ->select('b');

        $pagination = $paginator->paginate(
            $query->getQuery(),
            $request->query->get('page', 1),
            self::NUM_RESULTS
        );

        return $this->render('book/showAll.html.twig',
            [
                "pagination" => $pagination,
            ]);
    }

    /**
     *
     * @Route("/book/show/{id}", name="view_single_book")
     * @Method("GET")
     * @param Book $book
     * @return Response
     */
    public function showAction(Book $book)
    {
        return $this->render('book/single.html.twig', array(
            'book' => $book,
        ));
    }
}
