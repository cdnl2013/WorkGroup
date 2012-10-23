<?php

namespace WG\DashboardBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;

class WorkGroupExtension extends Twig_Extension{
    
    private $container = null;
    
    public function __construct($container) {
        $this->container = $container;
    }
    
    public function getGlobals() {
        $globals = parent::getGlobals();
        $globals['wg']['name'] = $this->container->getParameter('workgroup_name');
        return $globals;
    }
    
    public function getName() {
        return 'wg_ext';
    }
}

?>
