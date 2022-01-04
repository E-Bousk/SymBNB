<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Pagination
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }


    public function getNbrPages()
    {
       $totalPage = count($this->manager->getRepository($this->entityClass)->findAll());
       return $NbrPages = ceil($totalPage / $this->limit);
    }

    public function getData()
    {
        $offset = ($this->currentPage * $this->limit) - $this->limit;

        $repo = $this->manager->getRepository($this->entityClass);

        return $data = $repo->findBy([], [], $this->limit, $offset);
    }
    
    /**
     * Set the value of entityClass
     *
     * @return  self
     */ 
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        
        return $this;
    }
    
    /**
     * Get the value of entityClass
     */ 
    public function getEntityClass()
    {
        return $this->entityClass;
    }
    
    /**
     * Set the value of limit
     *
     * @return  self
     */ 
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the value of limit
     */ 
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the value of currentPage
     *
     * @return  self
     */ 
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Get the value of currentPage
     */ 
    public function getCurrentPage()
    {
        return $this->currentPage;
    }
}