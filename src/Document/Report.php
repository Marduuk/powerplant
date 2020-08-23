<?php
declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @MongoDB\Document
 */
class Report
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\NotNull
     * @Assert\NotBlank(message="Value cant be empty")
     * @Assert\Type("int")
     * @MongoDB\Field(type="float")
     */
    protected $power;

    /**
     * @Assert\NotNull
     * @Assert\NotBlank(message="Value cant be empty")
     * @Assert\Type("int")
     * @MongoDB\Field(type="int")
     */
    protected $generatorId;

    /**
     * @Assert\NotNull
     * @Assert\NotBlank(message="Value cant be empty")
     * @Assert\Type("DateTime")
     * @MongoDB\Field(type="date")
     */
    protected $measurementTime;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime|null
     */
    public function getMeasurementTime(): ?DateTime
    {
        return $this->measurementTime;
    }


    /**
     * @param DateTime $measurementTime
     * @return $this
     */
    public function setMeasurementTime(DateTime $measurementTime): self
    {
        $this->measurementTime = $measurementTime;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGeneratorId(): ?int
    {
        return $this->generatorId;
    }

    /**
     * @param int $power
     * @return self
     */
    public function setPower(int $power): self
    {
        $this->power = $power;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPower(): ?int
    {
        return $this->power;
    }

    /**
     * @param int $generatorId
     * @return self
     */
    public function setGeneratorId(int $generatorId): self
    {
        $this->generatorId = $generatorId;
        return $this;
    }

}