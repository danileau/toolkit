<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=PAL::class, mappedBy="user_id", orphanRemoval=true)
     */
    private $PALS;

    /**
     * @ORM\OneToMany(targetEntity=Gewicht::class, mappedBy="user")
     */
    private $gewichts;

    /**
     * @ORM\OneToMany(targetEntity=Calipometrie::class, mappedBy="user", orphanRemoval=true)
     */
    private $calipometries;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gender;

    public function __construct()
    {
        $this->PALS = new ArrayCollection();
        $this->gewichts = new ArrayCollection();
        $this->calipometries = new ArrayCollection();
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

    /**
     * @return Collection|PAL[]
     */
    public function getPALS(): Collection
    {
        return $this->PALS;
    }

    public function addPAL(PAL $pAL): self
    {
        if (!$this->PALS->contains($pAL)) {
            $this->PALS[] = $pAL;
            $pAL->setUserId($this);
        }

        return $this;
    }

    public function removePAL(PAL $pAL): self
    {
        if ($this->PALS->removeElement($pAL)) {
            // set the owning side to null (unless already changed)
            if ($pAL->getUserId() === $this) {
                $pAL->setUserId(null);
            }
        }

        return $this;
    }
    public function __toString(): string {
        return $this->email;
    }

    /**
     * @return Collection|Calipometrie[]
     */
    public function getCalipometries(): Collection
    {
        return $this->calipometries;
    }

    public function addCalipometry(Calipometrie $calipometry): self
    {
        if (!$this->calipometries->contains($calipometry)) {
            $this->calipometries[] = $calipometry;
            $calipometry->setUser($this);
        }

        return $this;
    }

    public function removeCalipometry(Calipometrie $calipometry): self
    {
        if ($this->calipometries->removeElement($calipometry)) {
            // set the owning side to null (unless already changed)
            if ($calipometry->getUser() === $this) {
                $calipometry->setUser(null);
            }
        }

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }


}
