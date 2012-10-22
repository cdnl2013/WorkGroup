<?php

namespace WG\FileBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;

class ImageExtension extends Twig_Extension {

    public function getFilters() {
        return array(
            'imageResizer' => new Twig_Filter_Method($this, 'imageResizer'),
            'imageMaxWidth' => new Twig_Filter_Method($this, 'imageMaxWidth'),
            'imageMaxHeight' => new Twig_Filter_Method($this, 'imageMaxHeight'),
            'imageCroper' => new Twig_Filter_Method($this, 'imageCroper'),
        );
    }

    public function imageResizer($input, $width, $height) {
        $imageResizer = new \WG\FileBundle\Service\ImageResizer($input);
        $imageResizer->setHeight($height);
        $imageResizer->setWidth($width);
        return $imageResizer->getPath();
    }

    public function imageMaxWidth($input, $width) {
        $imageResizer = new \WG\FileBundle\Service\ImageResizer($input);
        $imageResizer->setWidth($width);
        return $imageResizer->getPath();
    }

    public function imageMaxHeight($input, $height) {
        $imageResizer = new \WG\FileBundle\Service\ImageResizer($input);
        $imageResizer->setHeight($height);
        return $imageResizer->getPath();
    }

    public function imageCroper($input, $width, $height) {
        $imageResizer = new \WG\FileBundle\Service\ImageResizer($input);
        $imageResizer->setHeight($height);
        $imageResizer->setWidth($width);
        $imageResizer->setCrop(true);
        return $imageResizer->getPath();
    }

    public function getName() {
        return 'image_ext';
    }

}

?>
