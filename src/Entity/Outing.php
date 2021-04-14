<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\GreaterThan("today", message="Choose a valid date because nobody can travel in the past ;)")
     */
    private $startingTime;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 30)
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Expression(
     *     "this.getMaxDateInscription() < this.getStartingTime()",
     *     message="Inscription's max date cannot be superior than outing's starting time"
     * )
     */
    private $maxDateInscription;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 1
     * )
     */
    private $maxNbInscriptions;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $outingDetails;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdOuting")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planner;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="outing")
     */
    private $inscriptions;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=OutingStatus::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="outingPlaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbOfRegistrations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cancelReason;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

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

    public function getStatus(): ?OutingStatus
    {
        return $this->status;
    }

    public function setStatus(OutingStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPlanner(): ?User
    {
        return $this->planner;
    }

    public function setPlanner(?User $planner): self
    {
        $this->planner = $planner;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setOuting($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getOuting() === $this) {
                $inscription->setOuting(null);
            }
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getNbOfRegistrations(): ?int
    {
        return $this->nbOfRegistrations;
    }

    public function setNbOfRegistrations(int $nbOfRegistrations): self
    {
        $this->nbOfRegistrations = $nbOfRegistrations;

        return $this;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(?string $cancelReason): self
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }
}
