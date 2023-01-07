<?php

namespace App\Entity;

use App\Repository\CalipometrieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalipometrieRepository::class)
 */
class Calipometrie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bauch;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $brust;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $huefte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $trizeps;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bein;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $achsel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ruecken;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="calipometries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $bodyfat;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBauch(): ?int
    {
        return $this->bauch;
    }

    public function setBauch(?int $bauch): self
    {
        $this->bauch = $bauch;

        return $this;
    }

    public function getBrust(): ?int
    {
        return $this->brust;
    }

    public function setBrust(?int $brust): self
    {
        $this->brust = $brust;

        return $this;
    }

    public function getHuefte(): ?int
    {
        return $this->huefte;
    }

    public function setHuefte(?int $huefte): self
    {
        $this->huefte = $huefte;

        return $this;
    }

    public function getTrizeps(): ?int
    {
        return $this->trizeps;
    }

    public function setTrizeps(?int $trizeps): self
    {
        $this->trizeps = $trizeps;

        return $this;
    }

    public function getBein(): ?int
    {
        return $this->bein;
    }

    public function setBein(?int $bein): self
    {
        $this->bein = $bein;

        return $this;
    }

    public function getAchsel(): ?int
    {
        return $this->achsel;
    }

    public function setAchsel(?int $achsel): self
    {
        $this->achsel = $achsel;

        return $this;
    }

    public function getRuecken(): ?int
    {
        return $this->ruecken;
    }

    public function setRuecken(?int $ruecken): self
    {
        $this->ruecken = $ruecken;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

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

    public function getBodyfat(): ?float
    {
        return $this->bodyfat;
    }

    public function setBodyfat(?float $bodyfat): self
    {
        $this->bodyfat = $bodyfat;

        return $this;
    }
}
