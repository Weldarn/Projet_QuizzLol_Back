<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
  normalizationContext: ['groups' => ['user_get']]
)]
#[Get]
#[GetCollection(normalizationContext: ['groups' => ['user_getAll']])]
#[Put(normalizationContext: ['groups' => ['user_put']])]
#[Post(normalizationContext: ['groups' => ['user_post']])]
#[Patch(normalizationContext: ['groups' => ['user_patch']])]
#[Delete(normalizationContext: ['groups' => ['user_delete']])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length:180, unique:true)]
    #[Assert\NotBlank]
    #[Groups(['user_get', 'user_put', 'user_post'])]
    private ?string $username = null;

    
    #[ORM\Column(type:"json")]
    private array $roles = [];

   
    #[ORM\Column(type:"string")]
    #[Groups(['user_get', 'user_put', 'user_post'])]
    private ?string $password = null;

    
    #[ORM\Column(type:"integer")]
    private ?int $score = 0;

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
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
