<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $minsalary = null;

    #[ORM\Column]
    private ?int $maxsalary = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    /**
     * @var Collection<int, Applications>
     */
    #[ORM\OneToMany(targetEntity: Applications::class, mappedBy: 'job', orphanRemoval: true)]
    private Collection $applications;

    #[ORM\Column(nullable: true)]
    private ?bool $available = null;

    /**
     * @var Collection<int, Saved>
     */
    #[ORM\OneToMany(targetEntity: Saved::class, mappedBy: 'job', orphanRemoval: true)]
    private Collection $saveds;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $recruiter = null;

    #[ORM\Column(nullable: true)]
    private ?array $Requirements = null;

    #[ORM\Column(nullable: true)]
    private ?array $Experiences = [];

    #[ORM\Column(nullable: true)]
    private ?array $Missions = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $posted_date = null;


    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->saveds = new ArrayCollection();
        $this->Experiences = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMinsalary(): ?int
    {
        return $this->minsalary;
    }

    public function setMinsalary(int $minsalary): static
    {
        $this->minsalary = $minsalary;

        return $this;
    }

    public function getMaxsalary(): ?int
    {
        return $this->maxsalary;
    }

    public function setMaxsalary(int $maxsalary): static
    {
        $this->maxsalary = $maxsalary;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Applications>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Applications $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setJob($this);
        }

        return $this;
    }

    public function removeApplication(Applications $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getJob() === $this) {
                $application->setJob(null);
            }
        }

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return Collection<int, Saved>
     */
    public function getSaveds(): Collection
    {
        return $this->saveds;
    }

    public function addSaved(Saved $saved): static
    {
        if (!$this->saveds->contains($saved)) {
            $this->saveds->add($saved);
            $saved->setJob($this);
        }

        return $this;
    }

    public function removeSaved(Saved $saved): static
    {
        if ($this->saveds->removeElement($saved)) {
            // set the owning side to null (unless already changed)
            if ($saved->getJob() === $this) {
                $saved->setJob(null);
            }
        }

        return $this;
    }

    public function getRecruiter(): ?user
    {
        return $this->recruiter;
    }

    public function setRecruiter(?user $recruiter): static
    {
        $this->recruiter = $recruiter;

        return $this;
    }

    public function getRequirements(): ?array
    {
        return $this->Requirements;
    }

    public function setRequirements(?array $Requirements): static
    {
        $this->Requirements = $Requirements;

        return $this;
    }

    public function getExperiences(): ?array
    {
        return $this->Experiences;
    }

    public function setExperiences(?array $Experiences): static
    {
        $this->Experiences = $Experiences;

        return $this;
    }

    public function getMissions(): ?array
    {
        return $this->Missions;
    }

    public function setMissions(?array $Missions): static
    {
        $this->Missions = $Missions;

        return $this;
    }

    public function getPostedDate(): ?\DateTimeInterface
    {
        return $this->posted_date;
    }

    public function setPostedDate(?\DateTimeInterface $posted_date): static
    {
        $this->posted_date = $posted_date;

        return $this;
    }
}
