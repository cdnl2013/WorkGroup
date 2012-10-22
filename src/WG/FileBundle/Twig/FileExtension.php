<?php

namespace WG\FileBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;

class FileExtension extends Twig_Extension {

    public function getFilters() {
        return array(
            'render' => new Twig_Filter_Method($this, 'render'),
        );
    }

    public function render($input) {
        
        return '';
    }

    public function getName() {
        return 'file_ext';
    }

}

?>
