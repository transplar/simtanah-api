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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="news")
     * @ORM\JoinColumn(nullable=false)
     */
    private $writer;

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

    public function getPublishedOn(): ?string
    {
        return $this->published_on->format('Y-m-d H:i:s');
    }

    public function setPublishedOn(\DateTimeInterface $published_on): self
    {
        $this->published_on = $published_on;

        return $this;
    }

    public function getLastUpdate(): ?string
    {
        return $this->last_update->format('Y-m-d H:i:s');
    }

    public function setLastUpdate(\DateTimeInterface $last_update): self
    {
        $this->last_update = $last_update;

        return $this;
    }

    public function getWriter(): ?string
    {
        return $this->writer->getUsername();
    }

    public function setWriter(?User $writer): self
    {
        $this->writer = $writer;

        return $this;
    }
}
