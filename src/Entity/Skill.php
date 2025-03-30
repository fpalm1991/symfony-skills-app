<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, UserSkillLevel>
     */
    #[ORM\OneToMany(targetEntity: UserSkillLevel::class, mappedBy: 'skill', orphanRemoval: true, cascade: ['remove'])]
    private Collection $userSkillLevels;

    public function __construct()
    {
        $this->userSkillLevels = new ArrayCollection();
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

    /**
     * @return Collection<int, UserSkillLevel>
     */
    public function getUserSkillLevels(): Collection
    {
        return $this->userSkillLevels;
    }

    public function addUserSkillLevel(UserSkillLevel $userSkillLevel): static
    {
        if (!$this->userSkillLevels->contains($userSkillLevel)) {
            $this->userSkillLevels->add($userSkillLevel);
            $userSkillLevel->setSkill($this);
        }

        return $this;
    }

    public function removeUserSkillLevel(UserSkillLevel $userSkillLevel): static
    {
        if ($this->userSkillLevels->removeElement($userSkillLevel)) {
            // set the owning side to null (unless already changed)
            if ($userSkillLevel->getSkill() === $this) {
                $userSkillLevel->setSkill(null);
            }
        }

        return $this;
    }
}
