<?php

namespace App\Entity;

use App\Repository\MoreRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoreRepository::class)
 */
class More
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
    private $linkcgu;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cgu;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titlecgu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $politicstitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $politics;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkcgu(): ?string
    {
        return $this->linkcgu;
    }

    public function setLinkcgu(?string $linkcgu): self
    {
        $this->linkcgu = $linkcgu;

        return $this;
    }

    public function getCgu(): ?string
    {
        return $this->cgu;
    }

    public function setCgu(?string $cgu): self
    {
        $this->cgu = $cgu;

        return $this;
    }

    public function getTitlecgu(): ?string
    {
        return $this->titlecgu;
    }

    public function setTitlecgu(?string $titlecgu): self
    {
        $this->titlecgu = $titlecgu;

        return $this;
    }

    public function getPoliticstitle(): ?string
    {
        return $this->politicstitle;
    }

    public function setPoliticstitle(?string $politicstitle): self
    {
        $this->politicstitle = $politicstitle;

        return $this;
    }

    public function getPolitics(): ?string
    {
        return $this->politics;
    }

    public function setPolitics(?string $politics): self
    {
        $this->politics = $politics;

        return $this;
    }
}
