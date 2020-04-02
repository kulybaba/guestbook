<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
{
    const DIR_NAME = '/uploads/photos/';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $fileName
     *
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @var string $extension
     *
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @var string $url
     *
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @var Conference|null $conference
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Conference", mappedBy="photo", cascade={"persist", "remove"})
     */
    private $conference;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", mappedBy="photo", cascade={"persist", "remove"})
     */
    private $comment;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->fileName . '.' . $this->extension;
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
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     *
     * @return $this
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     *
     * @return $this
     */
    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

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

        // set (or unset) the owning side of the relation if necessary
        $newPhoto = null === $conference ? null : $this;
        if ($conference->getPhoto() !== $newPhoto) {
            $conference->setPhoto($newPhoto);
        }

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        // set (or unset) the owning side of the relation if necessary
        $newPhoto = null === $comment ? null : $this;
        if ($comment->getPhoto() !== $newPhoto) {
            $comment->setPhoto($newPhoto);
        }

        return $this;
    }
}
