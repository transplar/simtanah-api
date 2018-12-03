<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 */
class News
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published_on;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_update;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="news")
     */
    private $writer;

    public function __construct()
    {
        $this->writer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedOn(): ?\DateTimeInterface
    {
        return $this->published_on;
    }

    public function setPublishedOn(\DateTimeInterface $published_on): self
    {
        $this->published_on = $published_on;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->last_update;
    }

    public function setLastUpdate(\DateTimeInterface $last_update): self
    {
        $this->last_update = $last_update;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getWriter(): Collection
    {
        return $this->writer;
    }

    public function addWriter(User $writer): self
    {
        if (!$this->writer->contains($writer)) {
            $this->writer[] = $writer;
        }

        return $this;
    }

    public function removeWriter(User $writer): self
    {
        if ($this->writer->contains($writer)) {
            $this->writer->removeElement($writer);
        }

        return $this;
    }
}
