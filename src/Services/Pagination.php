<?php
namespace App\Services;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination
{
    private $entityClass;
    private $limit = 9;
    private $currentPage = 1;
    private $entityManagerInterface;
    private $twig;
    private $templatePath;
    private $route;
    private $route_params;

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
    public function __construct(EntityManagerInterface $entityManagerInterface, Environment $twig, $templatePath,RequestStack $requestStack)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
        $this->route = $requestStack->getCurrentRequest()->attributes->get('_route');
    }

    // Cette méthode permet de récupérer les éléments à afficher
    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route,
            'route_params' => $this->route_params
        ]);
    }


    public function getData()
    {

        // Lever une exception si l'entité n'est pas définie
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle vous voulez paginer ! Utilisez la méthode setEntityClass() de votre objet Pagination !");
        }


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
    



    /**
     * Get the value of route
     */ 
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set the value of route
     *
     * @return  self
     */ 
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get the value of route_params
     */ 
    public function getRoute_params()
    {
        return $this->route_params;
    }

    /**
     * Set the value of route_params
     *
     * @return  self
     */ 
    public function setRoute_params($route_params)
    {
        $this->route_params = $route_params;

        return $this;
    }
}