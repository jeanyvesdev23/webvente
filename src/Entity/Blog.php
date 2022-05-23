<?php

namespace App\Entity;

use App\Entity\Traits\timestandable;
use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Blog
{
    use timestandable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageBog;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titreBlog;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionBlog;



    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blogs")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=CommentaireBlog::class, mappedBy="blog")
     */
    private $commentaireBlogs;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublier;
    /**
     * @ORM\OneToMany(targetEntity=WishList::class, mappedBy="blog")
     */
    private $wishLists;

    public function __construct()
    {

        $this->commentaireBlogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageBog(): ?string
    {
        return $this->imageBog;
    }

    public function setImageBog(string $imageBog): self
    {
        $this->imageBog = $imageBog;

        return $this;
    }

    public function getTitreBlog(): ?string
    {
        return $this->titreBlog;
    }

    public function setTitreBlog(string $titreBlog): self
    {
        $this->titreBlog = $titreBlog;

        return $this;
    }

    public function getDescriptionBlog(): ?string
    {
        return $this->descriptionBlog;
    }

    public function setDescriptionBlog(string $descriptionBlog): self
    {
        $this->descriptionBlog = $descriptionBlog;

        return $this;
    }



    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, CommentaireBlog>
     */
    public function getCommentaireBlogs(): Collection
    {
        return $this->commentaireBlogs;
    }

    public function addCommentaireBlog(CommentaireBlog $commentaireBlog): self
    {
        if (!$this->commentaireBlogs->contains($commentaireBlog)) {
            $this->commentaireBlogs[] = $commentaireBlog;
            $commentaireBlog->setBlog($this);
        }

        return $this;
    }

    public function removeCommentaireBlog(CommentaireBlog $commentaireBlog): self
    {
        if ($this->commentaireBlogs->removeElement($commentaireBlog)) {
            // set the owning side to null (unless already changed)
            if ($commentaireBlog->getBlog() === $this) {
                $commentaireBlog->setBlog(null);
            }
        }

        return $this;
    }

    public function getIsPublier(): ?bool
    {
        return $this->isPublier;
    }

    public function setIsPublier(?bool $isPublier): self
    {
        $this->isPublier = $isPublier;

        return $this;
    }

    /**
     * @return Collection<int, WishList>
     */
    public function getWishLists(): Collection
    {
        return $this->wishLists;
    }
    /**
     * si il y a likes sur user
     */

    public function isWishlist(User $user): bool
    {
        foreach ($this->wishLists as $wishList) {
            if ($wishList->getUser() === $user) return true;
        }
        return false;
    }
}
