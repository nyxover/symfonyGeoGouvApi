<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/pages', name: 'pages')]
    public function ListRegion(SerializerInterface $serializer)
    {
        $mesRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        // $mesRegionsTab=$serializer->decode($mesRegions, 'json');
        // $mesRegionsObjet=$serializer->denormalize($mesRegionsTab,'App\Entity\Region[]');
        $mesRegions=$serializer->deserialize($mesRegions,'App\Entity\Region[]','json');
       /*  dump($mesRegionsObjet);
        die(); */
        return $this->render('api/index.html.twig',[
            'mesRegions'=>$mesRegions
        ]);
    }
    #[Route('/listDepsParRegion', name: 'listDepsParRegion')]
    public function listDepsParRegion(Request $request, SerializerInterface $serializer)
    {
        //je recup la region selectionner dans le formulaire
        $codeRegion=$request->query->get('region');
        //je recup les region
        $mesRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegions=$serializer->deserialize($mesRegions,'App\Entity\Region[]','json');

        //je recup la liste des departement
        if($codeRegion == null || $codeRegion == "Toutes") {
            $mesDeps=file_get_contents('https://geo.api.gouv.fr/departements');
        }else{
            $mesDeps=file_get_contents('https://geo.api.gouv.fr/regions/'.$codeRegion.'/departements');
        }
        //decodage du format json en tableau
        $mesDeps=$serializer->decode($mesDeps,'json');


       
        return $this->render('api/listDepsParRegion.html.twig',[
            'mesRegions'=>$mesRegions,
            'mesDeps'=>$mesDeps
        ]);
    }
}

