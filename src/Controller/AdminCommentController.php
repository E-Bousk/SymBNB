<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\Pagination;
use App\Form\AdminCommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher tous les commentaires
     * 
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     * 
     * @param $page
     * @param Pagination $pagination 
     * @return Response
     */
    public function index($page, Pagination $pagination): Response
    {
        $pagination->setEntityClass(Comment::class)
            ->setCurrentPage($page)
            ->setLimit(5)
            // ->setRouteName('admin_comments_index') // La route est récupérée avec « RequestStack » dans le service « Pagination.php »
            // ->setTemplatePath('admin/partials/pagination.html.twig') // Le chemin du template est définit dans « services.yaml »

        ;

        return $this->render('admin/comment/index.html.twig', compact('pagination'));
    }
    
    /**
     * Permet d'afficher le formulaire d'édition d'un commentaire
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * 
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $manager 
     * @return Response 
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager): Response
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', "Le commentaire numéro <strong>{$comment->getId()}</strong> a bien été modifié");
            return $this->redirectToRoute('admin_comments_index');
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * 
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response 
     */
    public function delete(Comment $comment, ObjectManager $manager): Response
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash('success', "Le commentaire de <strong>{$comment->getAuthor()->GetFullName()}</strong> a bien été supprimé !");

        return $this->redirectToRoute('admin_comments_index');
    }
}
