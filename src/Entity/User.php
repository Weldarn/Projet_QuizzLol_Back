<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Controller\RegisterController;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['username'], message: 'Un compte existe déjà avec ce nom d’utilisateur.')]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    collectionOperations: [
        'get',
        'register' => [
            'method' => 'POST',
            'path' => '/register',
            'controller' => RegisterController::class,
            'openapi_context' => [
                'summary' => 'Register a new user',
                'description' => 'Create a new user account.',
            ],
            'denormalization_context' => ['groups' => ['user:write']],
            'validation_groups' => ['Default', 'creation'],
        ],
    ],
    itemOperations: [
        'get',
        'put',
        'patch',
        'delete',
    ],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:write'])]
    private ?string $username = null;

    #[ORM\Column(type: "json")]
    #[Groups(['user:read'])]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: GameScore::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $gameScores;

    public function __construct()
    {
        $this->gameScores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getGameScores(): Collection
    {
        return $this->gameScores;
    }

    public function addGameScore(GameScore $gameScore): self
    {
        if (!$this->gameScores->contains($gameScore)) {
            $this->gameScores[] = $gameScore;
            $gameScore->setUser($this);
        }

        return $this;
    }

    public function removeGameScore(GameScore $gameScore): self
    {
        if ($this->gameScores->removeElement($gameScore)) {
            if ($gameScore->getUser() === $this) {
                $gameScore->setUser(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
