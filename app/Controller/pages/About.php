<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
class About extends Page{

   /**
    * Método responsável por retornar o conteúdo (view) da nossa Página sobre
    * @return string 
    */ 
    public static function getAbout(){
      $obOrganization = new Organization;

       $content = View::render('pages/about',[
         'name' => $obOrganization->name,
         'description' => $obOrganization->description,
         'contato' => $obOrganization->contato
       ]);

       return parent::getPage('SOBRE -> MVC - project', $content);

    }

}