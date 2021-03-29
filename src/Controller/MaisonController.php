<?php

namespace App\Controller;

use App\Entity\Maison;
use App\Form\MaisonType;
use App\Repository\MaisonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaisonController extends AbstractController
{
    /**
     * @Route("/admin/maisons", name="admin_maisons")
     */
    public function index(MaisonRepository $maisonRepository): Response
    {
        $maisons = $maisonRepository->findAll();
        return $this->render('admin/maisons.html.twig', [
            'maisons' => $maisons,
        ]);
    }

    /**
     * @Route("/admin/maisons/create", name="admin_maisons_create")
     */
    public function createMaison(Request $request)
    {
        $maison = new Maison(); // création d'une nouvelle maison
        $form = $this->createForm(MaisonType::class, $maison); // création du formulaire avec en paramètre la nouvelle maison
        $form->handleRequest($request); // gestionnaire de requêtes HTTP
        if ($form->isSubmitted()) { // vérifie si le formulaire à été envoyé
            if ($form->isValid()) { // vérifie si le formulaire est valide
                $infoImg1 = $form['img1']->getData(); // récupère les informations de l'image 1
                $extensionImg1 = $infoImg1->guessExtension(); // récupère l'extension de l'image 1
                $nomImg1 = time() . '-1.' . $extensionImg1; // crée un nom unique pour l'image 1
                $infoImg1->move($this->getParameter('dossier_photos_maisons'), $nomImg1); // déplace l'image dans le dossier adéquat
                $maison->setImg1($nomImg1); // définit le nom de l'image à mettre en bdd
                $infoImg2 = $form['img2']->getData(); // récupère les informations de l'image 2
                if ($infoImg2 !== null) {
                    $extensionImg2 = $infoImg2->guessExtension(); // récupère l'extension de l'image 2
                    $nomImg2 = time() . '-2.' . $extensionImg2; // crée un nom unique pour l'image 2
                    $infoImg2->move($this->getParameter('dossier_photos_maisons'), $nomImg2); // déplace l'image dans le dossier adéquat
                    $maison->setImg2($nomImg2); // définit le nom de l'image à mettre en bdd
                } else {
                    $maison->setImg2(null); // définit le nom de l'image à mettre en bdd si pas d'image 2
                }
                $manager = $this->getDoctrine()->getManager(); // récupère le manager de Doctrine
                $manager->persist($maison); // dit à Doctrine qu'on va vouloir sauvegarder en bdd
                $manager->flush(); // exécute la requête
                $this->addFlash('success', 'La maison a bien été ajoutée');
                return $this->redirectToRoute('admin_maisons');
            } else {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout de la maison');
            }
        }
        return $this->render('admin/maisonForm.html.twig', [
            'maisonForm' => $form->createView() // création de la vue du formulaire et envoi à la vue (fichier)
        ]);
    }

    /**
     * @Route("/admin/maisons/update-{id}", name="admin_maisons_update")
     */
    public function updateMaison(MaisonRepository $maisonRepository, $id, Request $request)
    {
        $maison = $maisonRepository->find($id); // récupère la maison grâce à l'id
        $form = $this->createForm(MaisonType::class, $maison); // crée le formualire prérempli avec la maison récupérée jsute avant
        $form->handleRequest($request); // gestionnaire de requêtes HTTP
        if ($form->isSubmitted() && $form->isValid()) { // vérifie si le formulaire a été soumis et s'il est valide
            $infoImg1 = $form['img1']->getData();
            $nomOldImg1 = $maison->getImg1(); // récupère le nom de l'ancienne image 1
            if ($infoImg1 !== null) {
                $cheminOldImg1 = $this->getParameter('dossier_photos_maisons') . '/' . $nomOldImg1; // reconstitue le chemin de l'ancienne image 1
                if (file_exists($cheminOldImg1)) { // vérifie si le fichier de l'image 1 existe
                    unlink($cheminOldImg1); // supprimer le fichier image 1
                }
                $extensionImg1 = $infoImg1->guessExtension(); // récupère l'extension de l'image 1
                $nomImg1 = time() . '-1.' . $extensionImg1; // crée un nom unique pour l'image 1
                $infoImg1->move($this->getParameter('dossier_photos_maisons'), $nomImg1); // déplace l'image dans le dossier adéquat
                $maison->setImg1($nomImg1); // définit le nom de l'image à mettre en bdd
            } else {
                $maison->setImg1($nomOldImg1); // re-définit le nom de l'image à mettre en bdd
            }
            $infoImg2 = $form['img2']->getData();
            $nomOldImg2 = $maison->getImg2();
            if ($infoImg2 !== null) { // si on met une img2 dans le formulaire
                if ($nomOldImg2 !== null) { // si on a déjà une img2 en bdd
                    $cheminOldImg2 = $this->getParameter('dossier_photos_maisons') . '/' . $nomOldImg2;
                    if (file_exists($cheminOldImg2)) {
                        unlink($cheminOldImg2);
                    }
                }
                $extensionImg2 = $infoImg2->guessExtension();
                $nomImg2 = time() . '-2.' . $extensionImg2;
                $infoImg2->move($this->getParameter('dossier_photos_maisons'), $nomImg2);
                $maison->setImg2($nomImg2);
            } else { // si on ne met pas une img2 dans le formulaire, on garde l'ancien nom d'img2
                $maison->setImg2($nomOldImg2);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($maison);
            $manager->flush();
            $this->addFlash('success', 'La maison a bien été modifiée');
            return $this->redirectToRoute('admin_maisons');
        }
        return $this->render('admin/maisonForm.html.twig', [
            'maisonForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/maisons/delete-{id}", name="admin_maisons_delete")
     */
    public function deleteMaison(MaisonRepository $maisonRepository, $id)
    {
        $maison = $maisonRepository->find($id); // récupère la maison grâce à l'id
        $nomImg1 = $maison->getImg1(); // récupère le nom de l'image 1
        if ($nomImg1 !== null) { // vérifie qu'il y a bien un nom d'image
            $cheminImg1 = $this->getParameter('dossier_photos_maisons') . '/' . $nomImg1; // reconstitue le chemin de l'image 1
            if (file_exists($cheminImg1)) { // vérifie si le fichier existe
                unlink($cheminImg1); // supprime le fichier
            }
        }
        $nomImg2 = $maison->getImg2(); // récupère le nom de l'image 2
        if ($nomImg2 !== null) { // vérifie qu'il y a bien un nom d'image
            $cheminImg2 = $this->getParameter('dossier_photos_maisons') . '/' . $nomImg2; // reconstitue le chemin de l'image 2
            if (file_exists($cheminImg2)) { // vérifie si le fichier existe
                unlink($cheminImg2); // supprime le fichier
            }
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($maison); // précise qu'on va vouloir supprimer la maison
        $manager->flush(); // exécute la suppression
        $this->addFlash('success', 'La maison a bien été supprimée');
        return $this->redirectToRoute('admin_maisons');
    }
}
