<?php

namespace Bourgelat\AppBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;

class StringExtension extends Twig_Extension{
    
    public function getFilters() {
        return array(
            'boolToString' => new Twig_Filter_Method($this, 'boolToString'),
        );
    }
    
    public function boolToString($input, $yes = 'Oui', $no = 'Non'){
        if(is_bool($input)){
            if($input===TRUE){
                return $yes;
            }else if($input===FALSE){
                return $no;
            }
        }
        return null;
    }
    
    public function getName() {
        return 'string_ext';
    }
}

?>
