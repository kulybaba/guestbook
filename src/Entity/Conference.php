<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConferenceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Conference
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
     * @var string $title
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string $body
     *
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @var \DateTimeInterface $date
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var bool $visible
     *
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @var City $city
     *
     * @ORM\OneToOne(targetEntity="App\Entity\City", inversedBy="conference", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @var Collection|Comment[] $comments
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="conference", orphanRemoval=true)
     */
    private $comments;

    /**
     * @var Collection|Category[] $categories
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="conferences")
     */
    private $categories;

    /**
     * @var Speaker|Speaker[] $speakers
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Speaker", inversedBy="conferences")
     */
    private $speakers;

    /**
     * @var \DateTimeInterface $created
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTimeInterface $updated
     *
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->speakers = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->city . ', ' . $this->title;
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return $this
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City $city
     *
     * @return $this
     */
    public function setCity(City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     *
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setConference($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getConference() === $this) {
                $comment->setConference(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Speaker[]
     */
    public function getSpeakers(): Collection
    {
        return $this->speakers;
    }

    /**
     * @param Speaker $speaker
     *
     * @return $this
     */
    public function addSpeaker(Speaker $speaker): self
    {
        if (!$this->speakers->contains($speaker)) {
            $this->speakers[] = $speaker;
        }

        return $this;
    }

    /**
     * @param Speaker $speaker
     *
     * @return $this
     */
    public function removeSpeaker(Speaker $speaker): self
    {
        if ($this->speakers->contains($speaker)) {
            $this->speakers->removeElement($speaker);
        }

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @param \DateTimeInterface $created
     *
     * @return $this
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * @param \DateTimeInterface $updated
     *
     * @return $this
     */
    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedValue()
    {
        $this->setCreated(new \DateTime());
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedValue()
    {
        $this->setUpdated(new \DateTime());
    }
}
