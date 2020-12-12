<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeVille;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeDepartement;

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



    /**
     * Get the value of codeDepartement
     */
    public function getCodeDepartement()
    {
        return $this->codeDepartement;
    }

    /**
     * Set the value of codeDepartement
     *
     * @return  self
     */
    public function setCodeDepartement($codeDepartement)
    {
        $this->codeDepartement = $codeDepartement;

        return $this;
    }

    /**
     * Get the value of codeVille
     */
    public function getCodeVille()
    {
        return $this->codeVille;
    }

    /**
     * Set the value of codeVille
     *
     * @return  self
     */
    public function setCodeVille($codeVille)
    {
        $this->codeVille = $codeVille;

        return $this;
    }
}
