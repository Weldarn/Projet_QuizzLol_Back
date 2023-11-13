<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['gameScore:read']],
    denormalizationContext: ['groups' => ['gameScore:write']]
)]
class GameScore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'gameScores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['gameScore:read', 'gameScore:write'])]
    private int $scoreChampionEasy = 0;

    #[ORM\Column(type: 'integer')]
    #[Groups(['gameScore:read', 'gameScore:write'])]
    private int $scoreChampionHard = 0;

    #[ORM\Column(type: 'integer')]
    #[Groups(['gameScore:read', 'gameScore:write'])]
    private int $scoreObject = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getScoreChampionEasy(): int
    {
        return $this->scoreChampionEasy;
    }

    public function setScoreChampionEasy(int $scoreChampionEasy): self
    {
        $this->scoreChampionEasy = $scoreChampionEasy;
        return $this;
    }

    public function getScoreChampionHard(): int
    {
        return $this->scoreChampionHard;
    }

    public function setScoreChampionHard(int $scoreChampionHard): self
    {
        $this->scoreChampionHard = $scoreChampionHard;
        return $this;
    }

    public function getScoreObject(): int
    {
        return $this->scoreObject;
    }

    public function setScoreObject(int $scoreObject): self
    {
        $this->scoreObject = $scoreObject;
        return $this;
    }
}