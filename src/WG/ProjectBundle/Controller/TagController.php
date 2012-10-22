<?php

namespace WG\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagController extends Controller {
    
    public function ajaxSearchAction(){
        
        $search = trim($this->getRequest()->get('search'));
        
        $unknownTag = true;
        
        if(strlen($search)>0){
            $tags = $this->getDoctrine()->getRepository('WGProjectBundle:Tag')->search($search, 10);
            foreach($tags as $tag){
                if($tag->getTitleLower() == strtolower($search)){
                    $unknownTag = false;
                }
            }
        }else{
            $tags = array();
        }
        
        
        
        $data = array(
            'tags' => $tags,
            'search' => $search,
            'unknown' => $unknownTag
        );
        
        return $this->render('WGProjectBundle:Tag:ajax_search.html.twig', $data);
    }
}
