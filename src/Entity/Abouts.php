<?php

namespace App\Entity;

use App\Repository\AboutsRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AboutsRepository::class)
 * @Vich\Uploadable
 */
class Abouts
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
    private $step1Title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $step1Img;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="contribute_step1", fileNameProperty="step1Img")
     *
     * @var File|null
     */
    private $step1ImgFile;

    /**
     * @ORM\Column(type="text")
     */
    private $step1Body;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTimeInterface|null
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStep1Title(): ?string
    {
        return $this->step1Title;
    }

    public function setStep1Title(string $step1Title): self
    {
        $this->step1Title = $step1Title;

        return $this;
    }

    public function getStep1Img(): ?string
    {
        return $this->step1Img;
    }

    public function setStep1Img(?string $step1Img): self
    {
        $this->step1Img = $step1Img;

        return $this;
    }

    public function getStep1Body(): ?string
    {
        return $this->step1Body;
    }

    public function setStep1Body(string $step1Body): self
    {
        $this->step1Body = $step1Body;

        return $this;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param File|UploadedFile|null $step1ImgFile
     */
    public function setStep1ImgFile(?File $step1ImgFile = null): void
    {
        $this->step1ImgFile = $step1ImgFile;

        if (null !== $step1ImgFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    /**
     * @return File|null
     */
    public function getStep1ImgFile(): ?File
    {
        return $this->step1ImgFile;
    }

}
