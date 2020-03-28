<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
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
     * @var Conference|null $conference
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Conference", mappedBy="city", cascade={"persist", "remove"})
     */
    private $conference;

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
     * @return Conference|null
     */
    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    /**
     * @param Conference $conference
     *
     * @return $this
     */
    public function setConference(Conference $conference): self
    {
        $this->conference = $conference;

        // set the owning side of the relation if necessary
        if ($conference->getCity() !== $this) {
            $conference->setCity($this);
        }

        return $this;
    }
}
