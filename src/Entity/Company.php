<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ItemOperation;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
/**
 * ...
 * @ApiResource(
 *     itemOperations={
 *          "get" = { "security" = "is_granted('POST_VIEW', object)" }
 *     },
 * )
 */
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'company')]
    private Collection $projects;

    /**
     * @var Collection<int, UserCompany>
     */
    #[ORM\OneToMany(targetEntity: UserCompany::class, mappedBy: 'company')]
    private Collection $userCompanies;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->userCompanies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setCompany($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getCompany() === $this) {
                $project->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserCompany>
     */
    public function getUserCompanies(): Collection
    {
        return $this->userCompanies;
    }

    public function addUserCompany(UserCompany $userCompany): static
    {
        if (!$this->userCompanies->contains($userCompany)) {
            $this->userCompanies->add($userCompany);
            $userCompany->setCompany($this);
        }

        return $this;
    }

    public function removeUserCompany(UserCompany $userCompany): static
    {
        if ($this->userCompanies->removeElement($userCompany)) {
            // set the owning side to null (unless already changed)
            if ($userCompany->getCompany() === $this) {
                $userCompany->setCompany(null);
            }
        }

        return $this;
    }
}
