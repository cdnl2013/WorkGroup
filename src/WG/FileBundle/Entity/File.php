<?php

namespace WG\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="WG\FileBundle\Entity\FileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class File {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $editedAt;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $state;
    
    /**
     * @ORM\ManyToOne(targetEntity="WG\UserBundle\Entity\User", inversedBy="filesUploaded")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $author;
    
    /**
     * @ORM\Column(type="string")
     */    
    private $mimeType;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\ManyToMany(targetEntity="WG\ProjectBundle\Entity\Tag", mappedBy="files")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="WG\ProjectBundle\Entity\Project", inversedBy="files")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    private $project;
    
    /**
     * @ORM\ManyToMany(targetEntity="WG\UserBundle\Entity\UsersGroup", inversedBy="files")
     */
    private $usersgroups;

    /**
     * Constructor
     */
    public function __construct() {
        
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        
        $dateTime = new \DateTime();
        $this->setCreatedAt($dateTime);
        $this->setEditedAt($dateTime);
        
        $this->state = 1;
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
     * Set name
     *
     * @param string $name
     * @return File
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return File
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Add tag
     *
     * @param WG\ProjectBundle\Entity\Tag $tag
     * @return File
     */
    public function addTag(\WG\ProjectBundle\Entity\Tag $tag) {
        //$this->tags[] = $tag;
        $this->tags->add($tag); 
        return $this;
    }

    /**
     * Remove tags
     *
     * @param WG\ProjectBundle\Entity\Tag $tags
     */
    public function removeTag(\WG\ProjectBundle\Entity\Tag $tags) {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * Set project
     *
     * @param WG\ProjectBundle\Entity\Project $project
     * @return File
     */
    public function setProject(\WG\ProjectBundle\Entity\Project $project = null) {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return WG\ProjectBundle\Entity\Project 
     */
    public function getProject() {
        return $this->project;
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche le document/image dans la vue.
        return 'uploads';
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }

        // la méthode « move » prend comme arguments le répertoire cible et
        // le nom de fichier cible où le fichier doit être déplacé
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }
    
    public function setArchived(){
        if (null === $this->file) {
            return;
        }
        
        $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $this->file->guessExtension();
        
        $this->file->move($this->getUploadRootDir() . '/archives', $this->path);
        
        $this->setState(0);
        
        $this->file = null;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $this->file->guessExtension();
        }
        
        $this->setEditedAt(new \DateTime());
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


    /**
     * Set author
     *
     * @param WG\UserBundle\Entity\User $author
     * @return File
     */
    public function setAuthor(\WG\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return WG\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function getFile(){
        return $this->file;
    }
    
    public function setFile($file){
        $this->file = $file;
    }

    /**
     * Add usersgroups
     *
     * @param WG\UserBundle\Entity\UsersGroup $usersgroups
     * @return File
     */
    public function addUsersgroup(\WG\UserBundle\Entity\UsersGroup $usersgroups)
    {
        $this->usersgroups[] = $usersgroups;
    
        return $this;
    }

    /**
     * Remove usersgroups
     *
     * @param WG\UserBundle\Entity\UsersGroup $usersgroups
     */
    public function removeUsersgroup(\WG\UserBundle\Entity\UsersGroup $usersgroups)
    {
        $this->usersgroups->removeElement($usersgroups);
    }

    /**
     * Get usersgroups
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsersgroups()
    {
        return $this->usersgroups;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return File
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set editedAt
     *
     * @param \DateTime $editedAt
     * @return File
     */
    public function setEditedAt($editedAt)
    {
        $this->editedAt = $editedAt;
    
        return $this;
    }

    /**
     * Get editedAt
     *
     * @return \DateTime 
     */
    public function getEditedAt()
    {
        return $this->editedAt;
    }
    
    public function getDate(){
        return $this->getEditedAt();
    }
    
    public function getLabel(){
        return $this->getName();
    }
    
    public function getType(){
        return 'file';
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    
        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }
    
    public function isImage(){
        if(strpos($this->getMimeType(),'image') !== false){
            return true;
        }
        return false;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return File
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }
}