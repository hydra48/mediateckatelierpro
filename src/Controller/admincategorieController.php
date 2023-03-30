<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller;

use App\Form\CategoriesType;
use App\Repository\FormationRepository;
use App\Entity\Formation;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of admincategorieController
 *
 * @author joueu
 */
class admincategorieController extends AbstractController {
    //put your code here
    
    /**
     * 
     * @var CategorieRepository
     */
     private $categorieRepository;  
     
     /**
     * 
     * @var Direr
     */
    
     /**
     * 
     * @var FormationRepository
     */
     private $formationRepository;
     
    private  $direr = "admin/admincategories.html.twig";
    
    function __construct(CategorieRepository $categorieRepository , FormationRepository $formationRepository) {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
         }
    /**
     * @Route("admin/categories", name="admincategories")
     * @return Response
     */
    public function index(): Response{
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->direr, [
            'categories' => $categories            
        ]);
    }
    /**
     * @Route("admin/categories/suppr/{id}", name="admincategories.suppr")
     * @param Categorie $entity
     * @return Response
     */
    public function suppr(Categorie $entity): Response{
        $this->categorieRepository->remove($entity, true);
        return $this->redirectToRoute('admincategories');
    }
    /**
     * @Route("admin/categories/ajout", name="admincategories.ajout")ad
     * @param Request $requestCa
     * @return Response
     */
    public function ajout(Request $request): Response{
        $entity = new Categorie();
        $formadmincategories = $this->createForm(CategoriesType::class, $entity);
        $formadmincategories->handleRequest($request);
        if($formadmincategories->isSubmitted() && $formadmincategories->isValid()){
            $this->categorieRepository->add($entity, true);
            return $this->redirectToRoute('admincategories');
        }
        return $this->render("admin/admincategories.ajout.html.twig", [ 'admincategories' => $entity, 'formadmincategories' => $formadmincategories->createView()]);
    }
}
