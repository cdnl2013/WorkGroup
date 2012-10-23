<?php

namespace WG\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WG\ProjectBundle\Entity\Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WG\ProjectBundle\Entity\TagRepository")
 */
class Tag {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    /**
     * @ORM\Column(name="title_lower", type="string", length=255)
     */
    private $titleLower;
    

    /**
     * @ORM\ManyToMany(targetEntity="WG\FileBundle\Entity\File", inversedBy="tags")
     */
    private $files;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Tag
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add files
     *
     * @param WG\FileBundle\Entity\File $files
     * @return Tag
     */
    public function addFile(\WG\FileBundle\Entity\File $files) {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param WG\FileBundle\Entity\File $files
     */
    public function removeFile(\WG\FileBundle\Entity\File $files) {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFiles() {
        return $this->files;
    }
    
    public function __toString() {
        return $this->getTitle();
    }


    /**
     * Set titleLower
     *
     * @param string $titleLower
     * @return Tag
     */
    public function setTitleLower($titleLower)
    {
        $this->titleLower = $titleLower;
    
        return $this;
    }

    /**
     * Get titleLower
     *
     * @return string 
     */
    public function getTitleLower()
    {
        return $this->titleLower;
    }
}