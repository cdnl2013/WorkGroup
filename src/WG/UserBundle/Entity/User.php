<?php

namespace WG\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use WG\ProjectBundle\Entity\Project;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     */
    private $prenom;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $formation;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $promotion;

    /**
     * @ORM\Column(name="formation_option", type="string", nullable=true)
     */
    private $formationOption;

    /**
     * @ORM\ManyToMany(targetEntity="WG\ProjectBundle\Entity\Project", inversedBy="users")
     */
    private $projects;

    /**
     * @ORM\ManyToMany(targetEntity="UsersGroup", inversedBy="users")
     */
    private $usersgroups;
    
    /**
     * @ORM\OneToMany(targetEntity="WG\FileBundle\Entity\File", mappedBy="author")
     */
    private $filesUploaded;

    public function __construct() {
        parent::__construct();
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
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * Set option
     *
     * @param string $option
     * @return User
     */
    public function setFormationOption($option) {
        $this->formationOption = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return string 
     */
    public function getFormationOption() {
        return $this->formationOption;
    }

    /**
     * Add projects
     *
     * @param Project $projects
     * @return User
     */
    public function addProject(Project $projects) {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param Project $projects
     */
    public function removeProject(Project $projects) {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProjects() {
        return $this->projects;
    }

    /**
     * Add usersgroups
     *
     * @param WG\UserBundle\Entity\UsersGroup $usersgroups
     * @return User
     */
    public function addUsersgroup(\WG\UserBundle\Entity\UsersGroup $usersgroups) {
        $this->usersgroups[] = $usersgroups;

        return $this;
    }

    /**
     * Remove usersgroups
     *
     * @param WG\UserBundle\Entity\UsersGroup $usersgroups
     */
    public function removeUsersgroup(\WG\UserBundle\Entity\UsersGroup $usersgroups) {
        $this->usersgroups->removeElement($usersgroups);
    }

    /**
     * Get usersgroups
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsersgroups() {
        return $this->usersgroups;
    }


    /**
     * Add filesUploaded
     *
     * @param WG\FileBundle\Entity\File $filesUploaded
     * @return User
     */
    public function addFilesUploaded(\WG\FileBundle\Entity\File $filesUploaded)
    {
        $this->filesUploaded[] = $filesUploaded;
    
        return $this;
    }

    /**
     * Remove filesUploaded
     *
     * @param WG\FileBundle\Entity\File $filesUploaded
     */
    public function removeFilesUploaded(\WG\FileBundle\Entity\File $filesUploaded)
    {
        $this->filesUploaded->removeElement($filesUploaded);
    }

    /**
     * Get filesUploaded
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFilesUploaded()
    {
        return $this->filesUploaded;
    }

    /**
     * Set formation
     *
     * @param string $formation
     * @return User
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;
    
        return $this;
    }

    /**
     * Get formation
     *
     * @return string 
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * Set promotion
     *
     * @param string $promotion
     * @return User
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    
        return $this;
    }

    /**
     * Get promotion
     *
     * @return string 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }
    
    public function getUsername(){
        return ucfirst(strtolower($this->getPrenom())) . ' ' . strtoupper(substr($this->getNom(), 0, 1)) . '.';
    }
    
    public function getRealUsername(){
        return ucfirst(strtolower($this->getPrenom())) . ' ' . strtoupper(substr($this->getNom(), 0, 1)) . '.';
    }
}