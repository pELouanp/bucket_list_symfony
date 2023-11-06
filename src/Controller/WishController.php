<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wish")
 */
class WishController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);
        return $this->render('wish/list.html.twig', ["wishes" => $wish]);
    }

    /**
     * @Route("/list/{id}", name="detail", requirements={"id"="\d+"})
     */
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        if ($wish == null) {
            throw $this->createNotFoundException('Souhait non trouvé');
        }
        return $this->render('wish/detail.html.twig', ['wish' => $wish]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newIdea(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wish = new Wish();
        $wish->setDateCreated(new \DateTime());
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Idea successfully added!');

            return $this->redirectToRoute('detail', ['id' => $wish->getId()]);
        }
        return $this->render('wish/newIdea.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }
}
