<?php

namespace App\Entity;

use App\Entity\Traits\timestandable;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use timestandable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nomUser;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $prenomUser;



    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Addres::class, mappedBy="user",cascade={"persist"})
     */
    private $addres;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="users")
     */
    private $commander;

    /**
     * @ORM\Column(type="string", length=255, nullable=true,options={"default":"children.jpg"})
     
     */
    private $imageUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\NumberPhone
     */
    private $numberPhone;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="users")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="user")
     */
    private $blogs;

    /**
     * @ORM\OneToMany(targetEntity=CommentaireBlog::class, mappedBy="users")
     */
    private $commentaireBlogs;



    /**
     * @ORM\OneToMany(targetEntity=WishList::class, mappedBy="user")
     */
    private $wishLists;



    public function __construct()
    {
        $this->addres = new ArrayCollection();
        $this->commander = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->commentaireBlogs = new ArrayCollection();
        $this->wishLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): self
    {
        $this->nomUser = $nomUser;

        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): self
    {
        $this->prenomUser = $prenomUser;

        return $this;
    }
    public function getfullName()
    {
        return $this->getNomUser() . ' ' . $this->getPrenomUser();
    }



    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Addres>
     */
    public function getAddres(): Collection
    {
        return $this->addres;
    }

    public function addAddre(Addres $addre): self
    {
        if (!$this->addres->contains($addre)) {
            $this->addres[] = $addre;
            $addre->setUser($this);
        }

        return $this;
    }

    public function removeAddre(Addres $addre): self
    {
        if ($this->addres->removeElement($addre)) {
            // set the owning side to null (unless already changed)
            if ($addre->getUser() === $this) {
                $addre->setUser(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getNomUser();
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommander(): Collection
    {
        return $this->commander;
    }

    public function addCommander(Commande $commander): self
    {
        if (!$this->commander->contains($commander)) {
            $this->commander[] = $commander;
            $commander->setUsers($this);
        }

        return $this;
    }

    public function removeCommander(Commande $commander): self
    {
        if ($this->commander->removeElement($commander)) {
            // set the owning side to null (unless already changed)
            if ($commander->getUsers() === $this) {
                $commander->setUsers(null);
            }
        }

        return $this;
    }

    public function getImageUser(): ?string
    {
        return $this->imageUser ? $this->imageUser : "children.jpg";
    }

    public function setImageUser(?string $imageUser): self
    {
        $this->imageUser = $imageUser;

        return $this;
    }

    public function getNumberPhone(): ?string
    {
        return $this->numberPhone;
    }

    public function setNumberPhone(?string $numberPhone): self
    {
        $this->numberPhone = $numberPhone;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUsers($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUsers() === $this) {
                $commentaire->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setUser($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getUser() === $this) {
                $blog->setUser(null);
            }
        }

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
            $commentaireBlog->setUsers($this);
        }

        return $this;
    }

    public function removeCommentaireBlog(CommentaireBlog $commentaireBlog): self
    {
        if ($this->commentaireBlogs->removeElement($commentaireBlog)) {
            // set the owning side to null (unless already changed)
            if ($commentaireBlog->getUsers() === $this) {
                $commentaireBlog->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, wishlist>
     */
    public function getWishlists(): Collection
    {
        return $this->wishLists;
    }
}
