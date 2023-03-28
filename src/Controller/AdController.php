<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use App\Repository\ImageRepository;
use App\Services\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/ads')]
class AdController extends AbstractController
{   // {page<\d+>?1} signifie que la page est un nombre et que sa valeur par défaut est 1
    #[Route('/{page<\d+>?1}', name: 'app_ads', methods: ['GET'])]
    public function index(AdRepository $adRepository,$page,Pagination $pagination): Response
    {

    /*   
        // Afficher 10 annonces par page
        $limit = 9;

        // Calculer l'offset
        $offset = $page * $limit - $limit; // 1 * 10 - 10 = 0 Ce qui est le premier élément de la page 1 2 * 10 - 10 = 10 Ce qui est le premier élément de la page 2

        // $page contient le nombre de la page actuelle
        $pages = ceil(count($adRepository->findAll()) / $limit);


     */

        $pagination->setEntityClass(Ad::class)
                    ->setPage($page)
                    ->setLimit(36);

        return $this->render('ad/index.html.twig', [
            'ads' => $pagination->getData(), // On envoie les annonces à la vue
           /*  'pages' => $pagination->getPages(), // On envoie le nombre de pages à la vue
            'page' => $page, // On envoie la page actuelle à la vue */
            'pagination' => $pagination
        ]);
    }


    #[Route('/new', name: 'app_ad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdRepository $adRepository,ImageRepository $imageRepository,EntityManagerInterface $entityManagerInterface): Response
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $ad->setAuthor($user);
            // On récupère les images
            $images = $form['images']->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '' . bin2hex(random_bytes(15)) . '' . time() . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setCaption($fichier);
                $imagesPath = '/assets/images/uploads/ad/' . $fichier;
                $img->setUrl($fichier);
                $ad->addImage($img);
            }

            $coverImage = $form['coverImage']->getData();

            if ($coverImage) {
                $fichierUrl = md5(uniqid()) . '' . bin2hex(random_bytes(15)) . '' . time() . '.' . $coverImage->guessExtension();

                // On copie le fichier dans le dossier uploads
                $coverImage->move(
                    $this->getParameter('images_directory'),
                    $fichierUrl
                );

                // On stocke l'image dans la base de données (son nom)
                $imagePath = '/assets/images/uploads/ad/' . $fichierUrl;
                $ad->setCoverImage($fichierUrl);
            }


            $adRepository->save($ad, true);

            // Message flash
            $this->addFlash('success', 'L\'annonce <strong>' . $ad->getTitle() . '</strong> a bien été enregistrée sur le site !');



            // Redirection vers la page de l'annonce
            return $this->redirectToRoute('app_ad_show', [
                'slug' => $ad->getSlug()
            ], Response::HTTP_SEE_OTHER);



            // vider le formulaire
            $ad = new Ad();
            $form = $this->createForm(AdType::class, $ad);
        }





        return $this->renderForm('ad/new.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_ad_show', methods: ['GET'])]
    public function show($slug, AdRepository $adRepository): Response
    {
        $ad = $adRepository->findOneBySlug($slug);
        if (!$ad) {
            // Redirection vers une page des annonces
            return $this->redirectToRoute('app_ads');
        }
        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    #[Security('is_granted("ROLE_USER") and user === ad.getAuthor()', message: "Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")]
    #[Route('/{id}/edit', name: 'app_ad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ad $ad, AdRepository $adRepository): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


                // On récupère les images
                $images = $form['images']->getData();

                // On boucle sur les images
                foreach ($images as $image) {
                    // On génère un nouveau nom de fichier
                    $fichier = md5(uniqid()) . '' . bin2hex(random_bytes(15)) . '' . time() . '.' . $image->guessExtension();
    
                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
    
                    // On stocke l'image dans la base de données (son nom)
                    $img = new Image();
                    $img->setCaption($fichier);
                    $imagesPath = '/assets/images/uploads/ad/' . $fichier;
                    $img->setUrl($fichier);
                    $ad->addImage($img);
                }
    
                $coverImage = $form['coverImage']->getData();
    
                if ($coverImage) {
                    $fichierUrl = md5(uniqid()) . '' . bin2hex(random_bytes(15)) . '' . time() . '.' . $coverImage->guessExtension();
    
                    // On copie le fichier dans le dossier uploads
                    $coverImage->move(
                        $this->getParameter('images_directory'),
                        $fichierUrl
                    );
    
                    // On stocke l'image dans la base de données (son nom)
                    $imagePath = '/assets/images/uploads/ad/' . $fichierUrl;
                    $ad->setCoverImage($fichierUrl);
                }


            $adRepository->save($ad, true);

            

            return $this->redirectToRoute('app_ads', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    #[Security('is_granted("ROLE_USER") and user === ad.getAuthor()', message: "Cette annonce ne vous appartient pas, vous ne pouvez pas la supprimer")]
    #[Route('/{slug}/delete', name: 'app_ad_delete')]
    public function delete(Request $request, Ad $ad, AdRepository $adRepository,EntityManagerInterface $entityManagerInterface): Response
    {
       /*  if ($this->isCsrfTokenValid('delete' . $ad->getId(), $request->request->get('_token'))) {
            $adRepository->remove($ad, true);

            $this->addFlash('success', 'L\'annonce <strong>' . $ad->getTitle() . '</strong> a bien été supprimée !');
        } */

        $entityManagerInterface->remove($ad);
        $entityManagerInterface->flush();



        return $this->redirectToRoute('app_ads', [], Response::HTTP_SEE_OTHER);
    }

    // Gestion suppression image d'une annonce avec ajax
    #[Route('/delete-image/{id}', name: 'app_ad_delete_image', methods: ['DELETE'])]
    public function deleteImages(Image $images,Request $request,EntityManagerInterface $entityManager){

        // On récupère les données envoyées en json par la requête ajax dans la variable $data dans un tableau associatif
        $data = json_decode($request->getContent(),true);

        // On verifie si le token est valide

        if($this->isCsrfTokenValid('delete'.$images->getId(),$data['_token'])){
            // On récupère le nom de l'image
            $name = $images->getUrl();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$name);
            // On supprime l'entrée de la base
           // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($images);
            $entityManager->flush();

            // On répond en json
            return new JsonResponse(['success'=>1]);
        }else{
            return new JsonResponse(['error'=>'Token Invalide'],400);
        }

    }
    




    
}
