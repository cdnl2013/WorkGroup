<?php

namespace WG\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller {

    public function dashboardAction() {

        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $data = array(
            'activities' => $this->getActivities(10)
        );
        return $this->render('WGDashboardBundle:Dashboard:index.html.twig', $data);
    }

    public function activitiesAction() {
        $session = $this->getRequest()->getSession();

        if (!$session->has('tags')) {
            $session->set('tags', array());
        }
        
        $tagGet = $this->getRequest()->get('tag');
        if($tagGet != null){
            $this->addTag($tagGet);
        }
        
        $tags = $session->get('tags');
        
        $tagsEntities = array();
        
        foreach($tags as $tag){
            $tagEntity = $this->getDoctrine()->getRepository('WGProjectBundle:Tag')->find($tag);
            if($tagEntity != NULL){
                $tagsEntities[] = $tagEntity;
            }
        }

        $data = array(
            'activities' => $this->getActivities(20, $tags),
            'tags' => $tagsEntities
        );

        return $this->render('WGDashboardBundle:Activities:list.html.twig', $data);
    }

    private function getActivities($num, $tags = array()) {
        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->getUser();

        $activities = array();

        if (sizeof($tags) > 0) {
            $files = $em->getRepository('WGFileBundle:File')->getFilesWithTags($user, $tags, $num);
        } else {
            $files = $em->getRepository('WGFileBundle:File')->getFiles($user, $num);
        }

        $activities = array_merge($activities, $files);

        return $this->usort_activitiesOnDate($activities, $num);
    }
    
    private function addTag($id){
         $session = $this->getRequest()->getSession();

        if (!$session->has('tags')) {
            $session->set('tags', array());
        }
        
        $tags = $session->get('tags');
        
        if(!in_array($id, $tags)){
            $tagEntity = $this->getDoctrine()->getRepository('WGProjectBundle:Tag')->find($id);
            if($tagEntity != NULL){
                $tags[] = $id;
                $session->set('tags', $tags);
            }
        }
    }
    
    private function removeTag($id){
        $session = $this->getRequest()->getSession();

        if (!$session->has('tags')) {
            $session->set('tags', array());
        }
        
        $tags = $session->get('tags');
        
        if(in_array($id, $tags)){
            $keys = array_keys($tags, $id);
            foreach($keys as $key){
                unset($tags[$key]);
                $session->set('tags', $tags);
            }
        }
    }
    
    public function ajaxRemoveTagFromSessionAction(){
        $id = $this->getRequest()->get('id');
        
        $this->removeTag($id);
        
        $tags = $this->getRequest()->getSession()->get('tags');
        
        $tagsEntities = array();
        
        foreach($tags as $tag){
            $tagEntity = $this->getDoctrine()->getRepository('WGProjectBundle:Tag')->find($tag);
            if($tagEntity != NULL){
                $tagsEntities[] = $tagEntity;
            }
        }

        $data = array(
            'activities' => $this->getActivities(20, $tags),
            'tags' => $tagsEntities
        );

        return $this->render('WGDashboardBundle:Activities:list_content.html.twig', $data);
    }
    
    public function ajaxAddTagInSessionAction(){
        
        $id = $this->getRequest()->get('id');
        
        $this->addTag($id);
        
        $tags = $this->getRequest()->getSession()->get('tags');
        
        $tagsEntities = array();
        
        foreach($tags as $tag){
            $tagEntity = $this->getDoctrine()->getRepository('WGProjectBundle:Tag')->find($tag);
            if($tagEntity != NULL){
                $tagsEntities[] = $tagEntity;
            }
        }

        $data = array(
            'activities' => $this->getActivities(20, $tags),
            'tags' => $tagsEntities
        );

        return $this->render('WGDashboardBundle:Activities:list_content.html.twig', $data);
    }

    /**
     * The most recent first.
     * @param type $files
     * @return type
     */
    private function usort_activitiesOnDate($activities, $num) {
        usort($activities, array('WG\DashboardBundle\Controller\DashboardController', 'cmp_activity_date'));
        return array_slice($activities, 0, $num);
    }

    public static function cmp_activity_date($a, $b) {
        if ($a->getDate() === $b->getDate()) {
            return 0;
        }
        return ($a->getDate() > $b->getDate()) ? -1 : 1;
    }

}
