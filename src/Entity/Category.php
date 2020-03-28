<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var int $position
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @var bool $visible
     *
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @var Collection|Conference[] $conferences
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Conference", mappedBy="categories")
     */
    private $conferences;

    public function __construct()
    {
        $this->conferences = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     *
     * @return $this
     */
    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     *
     * @return $this
     */
    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return Collection|Conference[]
     */
    public function getConferences(): Collection
    {
        return $this->conferences;
    }

    /**
     * @param Conference $conference
     *
     * @return $this
     */
    public function addConference(Conference $conference): self
    {
        if (!$this->conferences->contains($conference)) {
            $this->conferences[] = $conference;
            $conference->addCategory($this);
        }

        return $this;
    }

    /**
     * @param Conference $conference
     *
     * @return $this
     */
    public function removeConference(Conference $conference): self
    {
        if ($this->conferences->contains($conference)) {
            $this->conferences->removeElement($conference);
            $conference->removeCategory($this);
        }

        return $this;
    }
}
