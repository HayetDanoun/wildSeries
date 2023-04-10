<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert; //pour le composant Validator (#[Assert\TypeDeContrainte()])

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id] //=clé primaire
    #[ORM\GeneratedValue] //générée automatiquement par la bdd
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:'Le champ name doit être rempli')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La catégorie saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères',
    )]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Program::class)]
    private $programs;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program) : self
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
            $program->setCategory($this);
        }
        return $this;
    }

    public function  removeProgram(Program $program): self
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getCategory() === $this) {
                $program->setCategory(null);
            }
        }
        return $this;
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


}
/*
 * Symfony intègre par défaut des traductions dans différentes langues.
 * Si tu veux que tous les messages d'erreur soient automatiquement en français
 * (et non plus en anglais), tu peux modifier la langue "par défaut" de Symfony dans
 * le fichier config/packages/translation.yaml en modifiant la ligne default_locale:
 * en en default_locale: fr
 */