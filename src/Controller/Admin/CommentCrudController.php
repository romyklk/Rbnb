<?php

namespace App\Controller\Admin;

use IntlChar;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextEditorField::new('content', 'Commentaire'),
            TextField::new('author', 'Auteur')->hideOnForm(),
            AssociationField::new('ad', 'Annonce')->setFormTypeOption('by_reference', false)->hideOnForm(),
            IntegerField::new('rating', 'Note/5'),

        ];
    }

    //Modifier et  Rediriger après la modification d'un commentaire
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $commentAuthor = $entityInstance->getAuthor($this->getUser());
        $entityInstance->setAuthor($this->getUser());
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    
        $this->addFlash('success', 'Le commentaire de ' . $commentAuthor . ' a bien été modifié');
    }
    

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance):void
    {
        // récupérer l'auteur du commentaire
        $commentAuthor  = $entityInstance->getAuthor()->getFirstName() .' ' .$entityInstance->getAuthor()->getLastName();

            $entityManager->remove($entityInstance);
            $entityManager->flush();
            $this->addFlash('success', 'LE commentaire de <strong>' . $commentAuthor. '</strong> a bien été supprimée !');


    }
}
