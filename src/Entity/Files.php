<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilesRepository")
 */
class Files
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $fileAttach;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    public function getId()
    {
        return $this->id;
    }

    public function getFileAttach(): ?string
    {
        return $this->fileAttach;
    }

    public function setFileAttach(string $fileAttach): self
    {
        $this->fileAttach = $fileAttach;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }
}
