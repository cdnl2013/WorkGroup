<?php

namespace WG\DashboardBundle\Twig;

class PageExtension extends \Twig_Extension {

    protected $request;

    /**
     *
     * @var \Twig_Environment
     */
    protected $environment;

    public function __construct($container) {
        try {
            $this->request = $container->get('request');
        } catch (\Symfony\Component\DependencyInjection\Exception\InactiveScopeException $e){
            
        }
    }

    public function initRuntime(\Twig_Environment $environment) {
        $this->environment = $environment;
    }

    public function getFunctions() {
        return array(
            'getControllerName' => new \Twig_Function_Method($this, 'getControllerName'),
            'getActionName' => new \Twig_Function_Method($this, 'getActionName'),
            'getBundleName' => new \Twig_Function_Method($this, 'getBundleName'),
        );
    }

    /**
     * Get controller name
     */
    public function getControllerName() {
        $regexp = "#Controller\\\([a-zA-Z]*)Controller#";
        $results = array();
        preg_match($regexp, $this->request->get('_controller'), $results);
        return strtolower($results[1]);
    }

    /**
     * Get action name
     */
    public function getActionName() {
        $regexp = "#::([a-zA-Z]*)Action#";
        $results = array();
        preg_match($regexp, $this->request->get('_controller'), $results);
        return $results[1];
    }
    
    public function getBundleName(){
        $regexp = "#\\\([a-zA-Z]*)Bundle#";
        $results = array();
        preg_match($regexp, $this->request->get('_controller'), $results);
        return strtolower($results[1]);
    }

    public function getName() {
        return 'page_ext';
    }

}

?>
