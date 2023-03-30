<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Playlist;
use App\Form\PlaylistType;
/**
 * Description of PlaylistsController
 *
 * @author emds
 */
class AdminPlaylistController extends AbstractController {
    
    /**
     * 
     * @var Direr
     */
    
    private  $direr = "admin/adminplaylists.html.twig";
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;    
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("admin/playlists", name="adminplaylists")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->direr, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }

       /**
 * @Route("admin/playlists/tri/{champ}/{ordre}", name="adminplaylists.sort")
 * @param type $champ
 * @param type $ordre
 * @return Response
 */
 public function sort($champ, $ordre): Response{
    switch($champ){
       case "name":
         $playlists = $this->playlistRepository->findAllOrderByName($ordre);
       break;
      case "nbformations":
         $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
       break;
    }
    $categories = $this->categorieRepository->findAll();
    return $this->render($this->direr, [
    'playlists' => $playlists,
    'categories' => $categories
     ]);
 }         
    
    /**
     * @Route("admin/playlists/recherche/{champ}/{table}", name="adminplaylists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->direr, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("admin/playlists/playlist/{id}", name="adminplaylists.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("admin/adminplaylist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);        
    } 
    
       /**
     * @Route("admin/playlists/suppr/{id}", name="adminplaylist.suppr")
     * @param Formation $entity
     * @return Response
     */
    public function suppr(Playlist $entity): Response{
        $this->playlistRepository->remove($entity, true);
        return $this->redirectToRoute('adminplaylists');
        
    }
     /**
     * @Route("admin/playlists/edit/{id}", name="adminplaylist.edit")
     * @param Playlist $entity
     * @param Request $request
     * @return Response
     */
    public function edit(Playlist $entity ,Request $request): Response{
        $formplaylist = $this->createForm(PlaylistType::class, $entity);
        $formplaylist->handleRequest($request);
        if($formplaylist->isSubmitted() && $formplaylist->isValid()){
            $this->playlistRepository->add($entity, true);
            return $this->redirectToRoute('adminplaylists');
        }
        return $this->render("admin/adminplaylist.edit.html.twig", [ 'playlist' => $entity, 'formplaylist' => $formplaylist->createView()]);
    }
    
     /**
     * @Route("admin/playlists/ajout/", name="adminplaylist.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $entity = new Playlist;
        $formplaylist = $this->createForm(PlaylistType::class, $entity);
        $formplaylist->handleRequest($request);
        if($formplaylist->isSubmitted() && $formplaylist->isValid()){
            $this->playlistRepository->add($entity, true);
            return $this->redirectToRoute('adminplaylists');
        }
        return $this->render("admin/adminplaylist.edit.html.twig", [ 'playlist' => $entity, 'formplaylist' => $formplaylist->createView()]);
    }
    
    
    
}