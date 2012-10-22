<?php

namespace WG\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use WG\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="project")
 */
class Project {

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="WG\UserBundle\Entity\User", mappedBy="projects")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="WG\FileBundle\Entity\File", mappedBy="project")
     */
    private $files;

    /**
     * Constructor
     */
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Project
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
     * Add users
     *
     * @param User $users
     * @return Project
     */
    public function addUser(User $users) {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Add files
     *
     * @param WG\FileBundle\Entity\File $files
     * @return Project
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

}