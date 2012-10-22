<?php

namespace WG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WG\UserBundle\Entity\UsersGroup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WG\UserBundle\Entity\UsersGroupRepository")
 */
class UsersGroup {

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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="usersgroups")
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity="WG\FileBundle\Entity\File", mappedBy="usersgroups")
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
     * @return UsersGroup
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
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param WG\UserBundle\Entity\User $users
     * @return UsersGroup
     */
    public function addUser(\WG\UserBundle\Entity\User $users) {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param WG\UserBundle\Entity\User $users
     */
    public function removeUser(\WG\UserBundle\Entity\User $users) {
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
     * @return UsersGroup
     */
    public function addFile(\WG\FileBundle\Entity\File $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param WG\FileBundle\Entity\File $files
     */
    public function removeFile(\WG\FileBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }
}