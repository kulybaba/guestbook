<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
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
     * @var string $text
     *
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @var bool $visible
     *
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @var Conference|null $conference
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Conference", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conference;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->text;
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
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText(string $text): self
    {
        $this->text = $text;

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
     * @param Conference|null $conference
     *
     * @return $this
     */
    public function setConference(?Conference $conference): self
    {
        $this->conference = $conference;

        return $this;
    }
}
