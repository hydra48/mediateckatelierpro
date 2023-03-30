<?php


/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formation;
use App\Form\FormationsType;
/**
 * Description of FormationController
 *
 * @author joueu
 */
class AdminFormationController extends AbstractController{
    //put your code here
    private $formationRepository;
    
    
    private const DIRE = "admin/adminformations.html.twig";
    
    /**
     * 
     * @var CategorieRepository
     */
    
    
    private $categorieRepository;
    
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("admin/formations", name="adminformations")
     * @return Response
     */
    
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::DIRE, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/formations/tri/{champ}/{ordre}/{table}", name="adminformations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::DIRE, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     
    
    /**
     * @Route("admin/formations/recherche/{champ}/{table}", name="adminformations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::DIRE, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("admin/formations/formation/{id}", name="adminformations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render("admin/formation.html.twig", [
            'formation' => $formation
        ]);        
    }
        /**
     * @Route("admin/formations/suppr/{id}", name="adminformations.suppr")
     * @param Formation $entity
     * @return Response
     */
    public function suppr(Formation $entity): Response{
        $this->formationRepository->remove($entity, true);
        return $this->redirectToRoute('adminformations');
        
    }
     /**
     * @Route("admin/formations/edit/{id}", name="adminformations.edit")
     * @param Formation $entity
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $entity ,Request $request): Response{
        $formformation = $this->createForm(FormationsType::class, $entity);
        $formformation->handleRequest($request);
        if($formformation->isSubmitted() && $formformation->isValid()){
            $this->formationRepository->add($entity, true);
            return $this->redirectToRoute('adminformations');
        }
        return $this->render("admin/adminformation.edit.html.twig", [ 'formation' => $entity, 'formformation' => $formformation->createView()]);
    }
    /**
     * @Route("admin/formations/ajout", name="adminformations.ajout")ad
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $entity = new Formation();
        $formadminformations = $this->createForm(FormationsType::class, $entity);
        $formadminformations->handleRequest($request);
        if($formadminformations->isSubmitted() && $formadminformations->isValid()){
            $this->formationRepository->add($entity, true);
            return $this->redirectToRoute('adminformations');
        }
        return $this->render("admin/adminformation.ajout.html.twig", [ 'adminformations' => $entity, 'formadminformations' => $formadminformations->createView()]);
    }
}
