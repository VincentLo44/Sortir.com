<?php


namespace App\Entity;


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
     * @var date|null
     */
    private $dateMin;

    /**
     * @var date|null
     */
    private $dateMax;

    /**
     * @var boolean|null
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
     * @return Date|null
     */
    public function getDateMin(): ?Date
    {
        return $this->dateMin;
    }

    /**
     * @param Date|null $dateMin
     */
    public function setDateMin(Date $dateMin): void
    {
        $this->dateMin = $dateMin;
    }

    /**
     * @return Date|null
     */
    public function getDateMax(): ?Date
    {
        return $this->dateMax;
    }

    /**
     * @param Date|null $dateMax
     */
    public function setDateMax(Date $dateMax): void
    {
        $this->dateMax = $dateMax;
    }

    /**
     * @return bool|null
     */
    public function getPlanner(): ?bool
    {
        return $this->planner;
    }

    /**
     * @param bool|null $planner
     */
    public function setPlanner(bool $planner): void
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