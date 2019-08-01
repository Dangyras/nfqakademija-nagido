<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Laukas turi būti užpildytas!")
     * @Assert\Length(
     *      max = 130,
     *      maxMessage = "Simbolių limitas: {{ limit }}!"
     * )
     */
    private $documentName;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $documentDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThan("today UTC", message="Priminimas neturėtų būti praeityje!")
     */
    private $documentReminder;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $documentExpires;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      max = 130,
     *      maxMessage = "Simbolių limitas: {{ limit }}!"
     * )
     */
    private $documentNotes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="documents", cascade={"persist"})
     */
    private $tag;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Files", mappedBy="document", cascade={"persist"})
     */
    private $files;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $documentPath;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }

    public function setDocumentName(string $documentName): self
    {
        $this->documentName = $documentName;

        return $this;
    }

    public function getDocumentDate(): ?\DateTimeInterface
    {
        return $this->documentDate;
    }

    public function setDocumentDate(?\DateTimeInterface $documentDate): self
    {
        $this->documentDate = $documentDate;

        return $this;
    }

    public function getDocumentReminder(): ?\DateTimeInterface
    {
        return $this->documentReminder;
    }

    public function setDocumentReminder(?\DateTimeInterface $documentReminder): self
    {
        $this->documentReminder = $documentReminder;

        return $this;
    }

    public function getDocumentExpires(): ?\DateTimeInterface
    {
        return $this->documentExpires;
    }

    public function setDocumentExpires(?\DateTimeInterface $documentExpires): self
    {
        $this->documentExpires = $documentExpires;

        return $this;
    }

    public function getDocumentNotes(): ?string
    {
        return $this->documentNotes;
    }

    public function setDocumentNotes(?string $documentNotes): self
    {
        $this->documentNotes = $documentNotes;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|Files[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setDocument($this);
        }

        return $this;
    }

    public function removeFile(Files $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getDocument() === $this) {
                $file->setDocument(null);
            }
        }

        return $this;
    }

    public function getDocumentPath(): ?string
    {
        return $this->documentPath;
    }

    public function setDocumentPath(?string $documentPath): self
    {
        $this->documentPath = $documentPath;

        return $this;
    }
}
