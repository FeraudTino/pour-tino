<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ApiResource(
    attributes: [
        "pagination_items_per_page" => 3
    ],
    collectionOperations: [
        "get", "post"
    ],
    itemOperations: ["get", "put", "delete"],
    normalizationContext: ['groups' => "personne:read"],
    denormalizationContext: ['groups' => "personne:write"],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: ["adresse.id" => "exact"]
)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["personne:read", "adresse:read", "personne:write"])]
    private $id;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    #[Groups(["personne:read", "adresse:read", "personne:write"])]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom doit contenir au plus {{ limit }} caractères",
    )]
    private $nom;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    #[Groups(["personne:read", "adresse:read", "personne:write"])]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Le prénom ne peut contenir de chiffres',
    )]
    private $prenom;

    #[ORM\ManyToMany(targetEntity: Adresse::class, inversedBy: 'personnes',  cascade: ['persist'])]
    #[Groups(["personne:read", "personne:write"])]
    #[ApiSubresource()]
    private $adresses;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Groups(["personne:read", "personne:write"])]
    private $dateEnregistrement;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): self
    {
        $this->adresses->removeElement($adress);

        return $this;
    }
    public function setAdresses(array $adresses)
    {
        $this->adresses = $adresses;
        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->dateEnregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $dateEnregistrement): self
    {
        $this->dateEnregistrement = $dateEnregistrement;

        return $this;
    }
}
