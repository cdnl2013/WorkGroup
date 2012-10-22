<?php

namespace WG\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function resumeAction(){
        return $this->render('WGProjectBundle:Default:resume.html.twig');
    }
}
