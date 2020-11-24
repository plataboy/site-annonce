<?php

namespace App\Entity;

use App\Repository\ResetPasswordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRepository::class)
 */
class ResetPassword
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passResetKey;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passResetVerify;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassResetKey(): ?string
    {
        return $this->passResetKey;
    }

    public function setPassResetKey(?string $passResetKey): self
    {
        $this->passResetKey = $passResetKey;

        return $this;
    }

    public function getPassResetVerify(): ?string
    {
        return $this->passResetVerify;
    }

    public function setPassResetVerify(?string $passResetVerify): self
    {
        $this->passResetVerify = $passResetVerify;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
