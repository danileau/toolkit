<?php

namespace App\Entity;

use App\Repository\GewichtRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GewichtRepository::class)
 */
class Gewicht
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    public function __construct()
    {
        $this->timestamp = new \DateTime();
    }
    /**
     * @ORM\Column(type="float")
     */
    private $gewicht;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gewichts")
     */
    private $user;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $bf;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $calculate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $floating_weight;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getGewicht(): ?float
    {
        return $this->gewicht;
    }

    public function setGewicht(float $gewicht): self
    {
        $this->gewicht = $gewicht;

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

    public function getBf(): ?float
    {
        return $this->bf;
    }

    public function setBf(?float $bf): self
    {
        $this->bf = $bf;

        return $this;
    }

    public function getCalculate(): ?bool
    {
        return $this->calculate;
    }

    public function setCalculate(?bool $calculate): self
    {
        $this->calculate = $calculate;

        return $this;
    }

    public function getFloatingWeight(): ?float
    {
        return $this->floating_weight;
    }

    public function setFloatingWeight(?float $floating_weight): self
    {
        $this->floating_weight = $floating_weight;

        return $this;
    }

}
