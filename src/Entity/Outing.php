<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutingRepository::class)
 */
class Outing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startingTime;

    /**
     * @ORM\Column(type="float")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $maxDateInscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxNbInscriptions;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $outingDetails;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartingTime(): ?\DateTimeInterface
    {
        return $this->startingTime;
    }

    public function setStartingTime(\DateTimeInterface $startingTime): self
    {
        $this->startingTime = $startingTime;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getMaxDateInscription(): ?\DateTimeInterface
    {
        return $this->maxDateInscription;
    }

    public function setMaxDateInscription(\DateTimeInterface $maxDateInscription): self
    {
        $this->maxDateInscription = $maxDateInscription;

        return $this;
    }

    public function getMaxNbInscriptions(): ?int
    {
        return $this->maxNbInscriptions;
    }

    public function setMaxNbInscriptions(int $maxNbInscriptions): self
    {
        $this->maxNbInscriptions = $maxNbInscriptions;

        return $this;
    }

    public function getOutingDetails(): ?string
    {
        return $this->outingDetails;
    }

    public function setOutingDetails(?string $outingDetails): self
    {
        $this->outingDetails = $outingDetails;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
