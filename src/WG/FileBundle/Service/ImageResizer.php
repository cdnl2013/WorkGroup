<?php

/**
 * Tseho's image resizer library
 * quentin@wippix.org
 */

namespace WG\FileBundle\Service;

class ImageResizer {

    private $file;
    private $dir;
    private $name;
    private $ext;
    private $image_width;
    private $image_height;
    private $width;
    private $height;
    private $quality = 80;
    private $top = 0;
    private $left = 0;
    private $crop = false;
    private $type;
    private $loaded = false;
    private $newPath = '';
    private $cachePath = 'mini/';
    private static $types = array('', 'gif', 'jpeg', 'png', 'swf');

    function __construct($path) {
        $this->file = $path;

        try {
            $info = getimagesize($path);
            $this->loaded = true;
        } catch (\ErrorException $exc) {
            $this->loaded = false;
            return;
        }

        $this->image_width = $info[0];
        $this->width = $info[0];
        $this->image_height = $info[1];
        $this->height = $info[1];

        $this->type = static::$types[$info[2]];

        $info = pathinfo($path);

        $this->dir = $info['dirname'];
        $this->name = str_replace('.' . $info['extension'], '', $info['basename']);
        $this->ext = $info['extension'];
    }
    
    /**
     * Return the path for the resized image. If not already exists, this function
     * will create it.
     * @return null
     */
    public function getPath() {
        if ($this->loaded == false) {
            return null;
        }

        //Update real size before create path name
        $this->calculSize();

        //Create path name
        $this->newPath = $this->createNewPath();

        //If already exist
        if (file_exists($this->newPath)) {
            return $this->newPath;
        }

        if ($this->resize() == false) {
            return null;
        }

        return $this->newPath;
    }

    /**
     * Set the max width of the output image
     * @param int $width
     */
    public function setWidth($width) {
        $this->width = intval($width);
    }

    /**
     * Set the max height of the output image
     * @param int $height
     */
    public function setHeight($height) {
        $this->height = intval($height);
    }

    /**
     * If crop is true, the image will be cropped to render an exact fit image.
     * @param boolean $crop
     */
    public function setCrop($crop) {
        if (is_bool($crop)) {
            $this->crop = $crop;
        }
    }

    /**
     * Set the jpeg quality
     * @param int $quality between 0 and 100
     */
    public function setQuality($quality) {
        $quality = intval($quality);
        if ($quality >= 0 && $quality <= 100) {
            $this->quality = $quality;
        }
    }

    /**
     * Resize, create and save the image.
     * @return boolean true if success, false otherwise
     */
    private function resize() {
        $src_image_w = $this->image_width;
        $src_image_h = $this->image_height;

        switch ($this->type) {
            case 'jpeg':
                $image = imagecreatefromjpeg($this->file);
                break;
            case 'png':
                $image = imagecreatefrompng($this->file);
                break;
            case 'gif':
                $image = imagecreatefromgif($this->file);
                break;
            default:
                return false;
        }

        if ($this->crop == true) {
            //Crop mode
            $w_ratio = doubleval($this->image_width / $this->width);
            $h_ratio = doubleval($this->image_height / $this->height);

            if ($w_ratio > $h_ratio) {
                $this->top = ($this->image_width - $this->image_height) / 2;
            } else if ($w_ratio < $h_ratio) {
                $this->left = ($this->image_height - $this->image_width) / 2;
            }

            if ($w_ratio > $h_ratio) {
                //We crop the necessary width
                $src_image_w = $this->image_width - ($this->image_width - $this->image_height);
                $src_image_h = $this->image_height;
                $crop_image = imagecreatetruecolor($src_image_w, $src_image_h);
                imagecopy($crop_image, $image, 0, 0, $this->top, $this->left, $this->image_width, $this->image_height);
                $image = $crop_image;
            } else if ($w_ratio < $h_ratio) {
                //Or we crop the necessary height
                $src_image_w = $this->image_width;
                $src_image_h = $this->image_height - ($this->image_height - $this->image_width);
                $crop_image = imagecreatetruecolor($src_image_w, $src_image_h);
                imagecopy($crop_image, $image, 0, 0, $this->top, $this->left, $this->image_width, $this->image_height);
                $image = $crop_image;
            }
        }

        $new_image = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $this->width, $this->height, $src_image_w, $src_image_h);

        switch ($this->type) {
            case 'jpeg':
                imagejpeg($new_image, $this->newPath, $this->quality);
                break;
            case 'png':
                imagepng($new_image, $this->newPath);
                break;
            case 'gif':
                imagegif($new_image, $this->newPath);
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * Calcul the real output image size before resizing
     */
    private function calculSize() {
        if ($this->crop == false) {
            if ($this->width == $this->image_width && $this->height != $this->image_height) {
                //Max height
                $this->width = intval($this->image_width / ($this->image_height / $this->height));
            } else if ($this->width != $this->image_width && $this->height == $this->image_height) {
                //Max width
                $this->height = intval($this->image_height / ($this->image_width / $this->width));
            } else {
                //Max Height and Max Width
                $w_ratio = doubleval($this->image_width / $this->width);
                $h_ratio = doubleval($this->image_height / $this->height);

                if ($w_ratio > $h_ratio) {
                    $this->height = intval($this->image_height / ($this->image_width / $this->width));
                } else if ($w_ratio < $h_ratio) {
                    $this->width = intval($this->image_width / ($this->image_height / $this->height));
                }
            }
        }
    }
    
    /**
     * Create and return the path for the asked image (with the new Width and Height in the image name)
     * @return string
     * @throws FileException
     */
    private function createNewPath() {
        $newPath = $this->dir . '/' . $this->cachePath;
        if (!is_dir($newPath)) {
            if (false === @mkdir($newPath, 0777, true)) {
                throw new FileException(sprintf('Unable to create the "%s" directory', $newPath));
            }
        } elseif (!is_writable($newPath)) {
            throw new FileException(sprintf('Unable to write in the "%s" directory', $newPath));
        }
        $newPath .= $this->name . '_' . $this->width . 'x' . $this->height;
        $newPath .= '.' . $this->ext;
        return $newPath;
    }

}

?>
