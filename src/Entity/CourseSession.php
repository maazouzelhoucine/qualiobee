<?php

namespace App\Entity;

use App\Repository\CourseSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: CourseSessionRepository::class)]
class CourseSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'courseSession')]
    private Collection $formations;

    #[ORM\OneToOne(inversedBy: 'courseSession', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    #[ORM\Column(length: 255)]
    private ?string $start_at = null;

    #[ORM\Column(length: 255)]
    private ?string $end_at = null;

    #[ORM\OneToOne(inversedBy: 'courseSession', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?IntervalDate $intervalDate = null;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setCourseSession($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getCourseSession() === $this) {
                $formation->setCourseSession(null);
            }
        }

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(Formation $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    public function getStartAt(): ?string
    {
        return $this->start_at;
    }

    public function setStartAt(string $start_at): static
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?string
    {
        return $this->end_at;
    }

    public function setEndAt(string $end_at): static
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getIntervalDate(): ?IntervalDate
    {
        return $this->intervalDate;
    }

    public function setIntervalDate(IntervalDate $intervalDate): static
    {
        $this->intervalDate = $intervalDate;

        return $this;
    }
}
