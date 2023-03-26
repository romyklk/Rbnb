<?php
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;


class Pagination
{
    private $entityClass;
    private $limit = 9;
    private $currentPage = 1;
    private $entityManagerInterface;
    private $twig;
    private $templatePath;

    // Cette méthode permet de récupérer l'entité sur laquelle on veut paginer
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    // Cette méthode permet de récupérer le nombre d'éléments par page
    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    // Cette méthode permet de récupérer la page actuelle
    public function getPage()
    {
        return $this->currentPage;
    }

    public function setPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    // Cette méthode permet de récupérer le manager de Doctrine
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    


    public function getData()
    {
        // 1) Calculer l'offset(à partir de quel élément on veut récupérer les éléments)
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2) Demander au repository de trouver les éléments
        $repo = $this->entityManagerInterface->getRepository($this->entityClass);
        $data = $repo->findBy([],[],$this->limit,$offset);
    
        // 3) Renvoyer les éléments en question
        return $data;
    
    }

    // Cette méthode permet de récupérer le nombre total de pages pour une entité
    public function getPages()
    {
        $repo = $this->entityManagerInterface->getRepository($this->entityClass);
        $total = count($repo->findAll());
        $pages = ceil($total / $this->limit);
        return $pages;
    }
    


}