<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ad::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            SlugField::new('slug')->setTargetFieldName('title')->hideOnIndex(), // Le slug est généré automatiquement à partir du nom du produit
            TextEditorField::new('introduction', 'Introduction'),
            TextEditorField::new('content', 'Description'),
            // Afficher le prix et ajouter un symbole € en utilisant integerField
            IntegerField::new('price', 'Prix')->setNumberFormat('%d €'),
            IntegerField::new('rooms', 'Nb. pièces'),
            TextField::new('type', 'Type de bien'),
            TextField::new('adress', 'Adresse')->hideOnIndex(),
            TextField::new('city', 'Ville')->hideOnIndex(),
            TextField::new('zipCode', 'Code postal')->hideOnIndex(),
            TextField::new('country', 'Pays'),

            AssociationField::new('author', 'Auteur')->setFormTypeOption('by_reference', false)->hideOnForm(),
            ImageField::new('author.profilPicture', 'Profil')->hideOnForm(),
            AssociationField::new('bookings', 'Nb. Rés')->setFormTypeOption('by_reference', false)->hideOnForm(),
            AssociationField::new('comments', 'Nb. Comment')->setFormTypeOption('by_reference', false)->hideOnForm(),
            // Afficher la note moyenne des commentaires de l'annonce en utilisant la méthode getAvgRatings() de l'entité Ad et ajouter une étoile
            IntegerField::new('avgRatings', 'Note moy')->hideOnForm(),
            // Afficher la photo de l'auteur de l'annonce en utilisant la méthode getAvatarUrl() de l'entité User et afficher une image



        ];
    }

    public function formatValue($value, $user)
    {
        if ($user instanceof User) {
            return $user->getFirstName();
        }

        return '';
    }


    // Message flash pour confirmer la suppression d'une annonce ou interdire la suppression d'une annonce si elle a des réservations
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Vérifier si l'annonce possède des réservations
        if ($entityInstance->getBookings()->count() > 0) {
            // Récupérer le titre de l'annonce
            $title = $entityInstance->getTitle();
            $this->addFlash('warning', 'Vous ne pouvez pas supprimer l\'annonce <strong>' . $title . '</strong> car elle possède des réservations !');
        }else{ // Si l'annonce ne possède pas de réservations

            $title = $entityInstance->getTitle();
            $entityManager->remove($entityInstance);
            $entityManager->flush();

            $this->addFlash('success', 'L\'annonce <strong>' . $title . '</strong> a bien été supprimée !');

        }
    }

    // Permettre à l'admin de modifier une annonce

  /*   public function persistEntity(EntityManagerInterface $entityManager, $entityInstance){



    } 

 */

}
