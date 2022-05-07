<?php

namespace App\Entity;

use App\Entity\Traits\timestandable;
use App\Repository\CommentaireBlogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentaireBlogRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class CommentaireBlog
{
    use timestandable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Nombre d'etoile ne peut pas être vide et nombre maximum 5")
     * @Assert\Length(max=5)     
     */
    private $numberEtoile;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Commmentaire ne peut pas être vide ")
     * @Assert\Length(min=10,minMessage="Votre commentaire est trop court")
     */
    private $commentaire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublier;



    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentaireBlogs")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="commentaireBlogs")
     */
    private $blog;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getIsPublier(): ?bool
    {
        return $this->isPublier;
    }

    public function setIsPublier(bool $isPublier): self
    {
        $this->isPublier = $isPublier;

        return $this;
    }

    public function getNumberEtoile(): ?int
    {
        return $this->numberEtoile;
    }

    public function setNumberEtoile(int $numberEtoile): self
    {
        $this->numberEtoile = $numberEtoile;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }
}
