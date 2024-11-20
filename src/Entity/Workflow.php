<?php

namespace App\Entity;

use App\Repository\WorkflowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: WorkflowRepository::class)]
class Workflow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Seance>
     */
    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'workflow')]
    private Collection $seances;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToOne(mappedBy: 'workflow', cascade: ['persist', 'remove'])]
    private ?CourseSession $courseSession = null;

    #[ORM\OneToOne(mappedBy: 'workflow', cascade: ['persist', 'remove'])]
    private ?Formation $formation = null;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setWorkflow($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getWorkflow() === $this) {
                $seance->setWorkflow(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCourseSession(): ?CourseSession
    {
        return $this->courseSession;
    }

    public function setCourseSession(?CourseSession $courseSession): static
    {
        // unset the owning side of the relation if necessary
        if ($courseSession === null && $this->courseSession !== null) {
            $this->courseSession->setWorkflow(null);
        }

        // set the owning side of the relation if necessary
        if ($courseSession !== null && $courseSession->getWorkflow() !== $this) {
            $courseSession->setWorkflow($this);
        }

        $this->courseSession = $courseSession;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        // unset the owning side of the relation if necessary
        if ($formation === null && $this->formation !== null) {
            $this->formation->setWorkflow(null);
        }

        // set the owning side of the relation if necessary
        if ($formation !== null && $formation->getWorkflow() !== $this) {
            $formation->setWorkflow($this);
        }

        $this->formation = $formation;

        return $this;
    }
}
