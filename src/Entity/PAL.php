<?php

namespace App\Entity;

use App\Repository\PALRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PALRepository::class)
 */
class PAL
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $schlaf;

    /**
     * @ORM\Column(type="integer")
     */
    private $liegend;

    /**
     * @ORM\Column(type="integer")
     */
    private $sitzend;

    /**
     * @ORM\Column(type="integer")
     */
    private $gehend;

    /**
     * @ORM\Column(type="integer")
     */
    private $stehend;

    /**
     * @ORM\Column(type="integer")
     */
    private $sport;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="PALS")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchlaf(): ?int
    {
        return $this->schlaf;
    }

    public function setSchlaf(int $schlaf): self
    {
        $this->schlaf = $schlaf;

        return $this;
    }

    public function getLiegend(): ?int
    {
        return $this->liegend;
    }

    public function setLiegend(int $liegend): self
    {
        $this->liegend = $liegend;

        return $this;
    }

    public function getSitzend(): ?int
    {
        return $this->sitzend;
    }

    public function setSitzend(int $sitzend): self
    {
        $this->sitzend = $sitzend;

        return $this;
    }

    public function getGehend(): ?int
    {
        return $this->gehend;
    }

    public function setGehend(int $gehend): self
    {
        $this->gehend = $gehend;

        return $this;
    }

    public function getStehend(): ?int
    {
        return $this->stehend;
    }

    public function setStehend(int $stehend): self
    {
        $this->stehend = $stehend;

        return $this;
    }

    public function getSport(): ?int
    {
        return $this->sport;
    }

    public function setSport(int $sport): self
    {
        $this->sport = $sport;

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

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
