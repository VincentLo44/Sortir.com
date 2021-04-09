<?php


namespace App\Entity;


use DateTime;
use Symfony\Component\Validator\Constraints\Date;

class Search
{
    /**
     * @var int|null
     */
    private $campus;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var datetime|null
     */
    private $dateMin;

    /**
     * @var datetime|null
     */
    private $dateMax;

    /**
     * @var User|null
     */
    private $planner;

    /**
     * @var boolean|null
     */
    private $registered;

    /**
     * @var boolean|null
     */
    private $notRegistered;

    /**
     * @var boolean|null
     */
    private $pastOuting;

    /**
     * @return int|null
     */
    public function getCampus(): ?int
    {
        return $this->campus;
    }

    /**
     * @param int|null $campus
     */
    public function setCampus(int $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTime|null
     */
    public function getDateMin(): ?DateTime
    {
        return $this->dateMin;
    }

    /**
     * @param DateTime|null $dateMin
     */
    public function setDateMin(DateTime $dateMin): void
    {
        $this->dateMin = $dateMin;
    }

    /**
     * @return DateTime|null
     */
    public function getDateMax(): ?DateTime
    {
        return $this->dateMax;
    }

    /**
     * @param DateTime|null $dateMax
     */
    public function setDateMax(DateTime $dateMax): void
    {
        $this->dateMax = $dateMax;
    }

    /**
     * @return User|null
     */
    public function getPlanner(): ?User
    {
        return $this->planner;
    }

    /**
     * @param User $planner
     */
    public function setPlanner(User $planner): void
    {
        $this->planner = $planner;
    }

    /**
     * @return bool|null
     */
    public function getRegistered(): ?bool
    {
        return $this->registered;
    }

    /**
     * @param bool|null $registered
     */
    public function setRegistered(bool $registered): void
    {
        $this->registered = $registered;
    }

    /**
     * @return bool|null
     */
    public function getNotRegistered(): ?bool
    {
        return $this->notRegistered;
    }

    /**
     * @param bool|null $notRegistered
     */
    public function setNotRegistered(bool $notRegistered): void
    {
        $this->notRegistered = $notRegistered;
    }

    /**
     * @return bool|null
     */
    public function getPastOuting(): ?bool
    {
        return $this->pastOuting;
    }

    /**
     * @param bool|null $pastOuting
     */
    public function setPastOuting(bool $pastOuting): void
    {
        $this->pastOuting = $pastOuting;
    }



}