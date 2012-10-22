<?php

namespace WG\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WG\FileBundle\Entity;

class FileController extends Controller {
    
    public function listAction(){
        $em = $this->getDoctrine()->getManager();
        $fileRepo = $em->getRepository('WGFileBundle:File');
        $user = $this->getUser();
        
        $files = $fileRepo->getFiles($user);
        
        $data = array(
            'files' => $files
        );
        
        return $this->render('WGFileBundle:File:list.html.twig', $data);
    }
    
    public function showAction($id){
        $em = $this->getDoctrine()->getManager();
        
        $file = $em->getRepository('WGFileBundle:File')->find($id);
        
        if($file == null){
            throw $this->createNotFoundException('Unable to find this file.');
        }
        
        $data = array(
            'file' => $file
        );
        
        return $this->render('WGFileBundle:File:show.html.twig', $data);
    }
    
    public function addTagAction($id){
        $em = $this->getDoctrine()->getManager();
        $tagRepo = $em->getRepository('WGProjectBundle:Tag');
        $req = $this->getRequest();
        
        $file = $em->getRepository('WGFileBundle:File')->find($id);
        
        if($file == null){
            throw $this->createNotFoundException('Unable to find this file.');
        }
        
        $form = $this->createTagsForm($file);
        
        if ($req->getMethod() === 'POST') {
            
            //On va gérer manuellement l'insertion des tags.
            //Donc on les garde en mémoire avant de les retirer de $_POST
            $tagsPOST = array();
            $formPOST = $req->get('form');
            if(isset($formPOST['tags'])){
                $tagsPOST = $formPOST['tags'];
                $formPOST['tags'] = array();
            }
            $req->request->set('form', $formPOST);
            
            
            $form->bind($this->getRequest());
            
            if ($form->isValid()) {
                foreach($tagsPOST as $tagPost){
                    $tag = $tagRepo->findOneBy(array('titleLower' => strtolower($tagPost)));
                    if($tag == null){
                        $tag = new \WG\ProjectBundle\Entity\Tag();
                        $tag->setTitle($tagPost);
                        $tag->setTitleLower(strtolower($tagPost));
                        $em->persist($tag);
                    }
                    $tag->addFile($file);
                    $file->addTag($tag);
                }
                
                $em->persist($file);
                $em->flush();
                
                return $this->redirect($this->generateUrl('wg_files_show', array('id' => $id)));
            }
        }
        
        $data = array(
            'file' => $file,
            'form' => $form->createView()
        );
        
        return $this->render('WGFileBundle:File:addTag.html.twig', $data);
    }

    public function uploadAction() {
        
        $req = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $tagRepo = $em->getRepository('WGProjectBundle:Tag');
        
        $file = new Entity\File();

        $form = $this->createUploadForm($file);

        if ($req->getMethod() === 'POST') {
            
            //On va gérer manuellement l'insertion des tags.
            //Donc on les garde en mémoire avant de les retirer de $_POST
            $tagsPOST = array();
            $formPOST = $req->get('form');
            if(isset($formPOST['tags'])){
                $tagsPOST = $formPOST['tags'];
                $formPOST['tags'] = array();
            }
            $req->request->set('form', $formPOST);
            
            
            $form->bind($this->getRequest());
            
            if ($form->isValid()) {
                
                $file->setMimeType($file->getFile()->getClientMimeType());
                
                if(strlen($file->getName()) == 0){
                    $file->setName($file->getFile()->getClientOriginalName());
                }
                
                $file->setAuthor($this->getUser());
                
                foreach($tagsPOST as $tagPost){
                    $tag = $tagRepo->findOneBy(array('titleLower' => strtolower($tagPost)));
                    if($tag == null){
                        $tag = new \WG\ProjectBundle\Entity\Tag();
                        $tag->setTitle($tagPost);
                        $tag->setTitleLower(strtolower($tagPost));
                        $em->persist($tag);
                    }
                    $tag->addFile($file);
                    $file->addTag($tag);
                }
                
                $em->persist($file);
                $em->flush();
                
                return $this->redirect($this->generateUrl('wg_files'));
            }
        }
        
        $data = array(
            'form' => $form->createView()
        );

        return $this->render('WGFileBundle:File:upload.html.twig', $data);
    }
    
    public function removeAction($id){
        $em = $this->getDoctrine()->getManager();
        $fileRepo = $em->getRepository('WGFileBundle:File');
        $user = $this->getUser();
        
        $fileEntity = $fileRepo->find($id);
        
        if($fileEntity === NULL){
            throw $this->createNotFoundException('Unable to find this file.');
        }
        
        if($fileEntity->getAuthor()->getId() !== $user->getId()){
            throw $this->createNotFoundException('You can\'t access to this page.');
        }
        
        $file = new \Symfony\Component\HttpFoundation\File\File($fileEntity->getAbsolutePath());
        
        $fileEntity->setFile($file);
        
        $fileEntity->setArchived();
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('wg_files'));
    }
    
    protected function createTagsForm($entity){
        $formBuilder = $this->createFormBuilder($entity);
        $formType = new \WG\FileBundle\Form\FileTagsType();
        $options = array(
            'tags' => $entity->getTags()
        );
        $formType->buildForm($formBuilder, $options);
        return $formBuilder->getForm();
    }

    protected function createUploadForm($entity) {
        /*
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('WGProjectBundle:Tag')->findAll();
        $tags_array = array();
        
        foreach($tags as $tag){
            $tags_array[$tag->getId()] = $tag->__toString();
        }
         * 
         */

        $formBuilder = $this->createFormBuilder($entity);

        $formType = new \WG\FileBundle\Form\FileType();

        $options = array(
            //'tags' => $tags
        );

        $formType->buildForm($formBuilder, $options);

        return $formBuilder->getForm();
    }

}
