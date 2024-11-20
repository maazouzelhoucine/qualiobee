<?php

namespace App\Entity;

use App\Repository\IntervalDateRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: IntervalDateRepository::class)]
class IntervalDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $DateStarter = null;

    #[ORM\Column(length: 255)]
    private ?string $DateEnd = null;

    #[ORM\ManyToOne(inversedBy: 'intervalDate')]
    private ?Seance $seance = null;

    #[ORM\OneToOne(mappedBy: 'intervalDate', cascade: ['persist', 'remove'])]
    private ?CourseSession $courseSession = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStarter(): ?string
    {
        return $this->DateStarter;
    }

    public function setDateStarter(string $DateStarter): static
    {
        $this->DateStarter = $DateStarter;

        return $this;
    }

    public function getDateEnd(): ?string
    {
        return $this->DateEnd;
    }

    public function setDateEnd(string $DateEnd): static
    {
        $this->DateEnd = $DateEnd;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): static
    {
        $this->seance = $seance;

        return $this;
    }

    public function getCourseSession(): ?CourseSession
    {
        return $this->courseSession;
    }

    public function setCourseSession(CourseSession $courseSession): static
    {
        // set the owning side of the relation if necessary
        if ($courseSession->getIntervalDate() !== $this) {
            $courseSession->setIntervalDate($this);
        }

        $this->courseSession = $courseSession;

        return $this;
    }
}
