<?php

declare(strict_types=1);

namespace App\Entity;

use App\Request\PostJobOffer;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobOfferRepository")
 */
class JobOffer
{
    const STATUS_PENDING_REVIEW = 'PENDING_REVIEW';
    const STATUS_APPROVED = 'APPROVED';

    const HIRING_TYPE_CLT = 'CLT';
    const HIRING_TYPE_PJ = 'PJ';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?int $id;

    /**
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     *
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?string $title;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"admin", "detail"})
     */
    private ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?Company $company;

    /**
     * @ORM\Column(type="string", length=10)
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?string $seniorityLevel;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":0})
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?int $minimumSalary;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":0})
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?int $maximumSalary;

    /**
     * @ORM\Column(type="string", length=14)
     *
     * @Groups({"admin"})
     */
    private ?string $status;

    /**
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime")
     */
    private DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?DateTime $publishedAt;

    /**
     * @ORM\Column(type="string", length=3)
     *
     * @Groups({"admin", "detail", "list"})
     */
    private ?string $hiringType;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     *
     * @Groups({"admin", "detail"})
     */
    private bool $allowRemote;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="jobOffers")
     *
     * @Groups({"admin", "detail"})
     */
    private Collection $tags;

    public function __construct()
    {
        $this->id = null;
        $this->slug = null;
        $this->title = null;
        $this->description = null;
        $this->company = null;
        $this->seniorityLevel = null;
        $this->minimumSalary = 0;
        $this->maximumSalary = 0;
        $this->status = self::STATUS_PENDING_REVIEW;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->publishedAt = null;
        $this->hiringType = null;
        $this->allowRemote = false;
        $this->tags = new ArrayCollection();
    }

    public function __toString(): string
    {
        return "#{$this->id} {$this->title}";
    }

    public static function createFromPost(PostJobOffer $data, Company $company): self
    {
        return (new static())
            ->setCompany($company)
            ->setTitle($data->title)
            ->setDescription($data->description)
            ->setSeniorityLevel($data->seniorityLevel)
            ->setMinimumSalary($data->minimumSalary)
            ->setMaximumSalary($data->maximumSalary)
            ->setStatus(JobOffer::STATUS_PENDING_REVIEW)
            ->setHiringType(JobOffer::HIRING_TYPE_CLT)
            ->setAllowRemote($data->allowRemote);
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getSeniorityLevel(): ?string
    {
        return $this->seniorityLevel;
    }

    public function setSeniorityLevel(string $seniorityLevel): self
    {
        $this->seniorityLevel = $seniorityLevel;

        return $this;
    }

    public function getMinimumSalary(): ?int
    {
        return $this->minimumSalary;
    }

    public function setMinimumSalary(int $minimumSalary): self
    {
        $this->minimumSalary = $minimumSalary;

        return $this;
    }

    public function getMaximumSalary(): ?int
    {
        return $this->maximumSalary;
    }

    public function setMaximumSalary(int $maximumSalary): self
    {
        $this->maximumSalary = $maximumSalary;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function isAllowRemote(): ?bool
    {
        return $this->allowRemote;
    }

    public function setAllowRemote(bool $allowRemote): self
    {
        $this->allowRemote = $allowRemote;

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addJobOffer($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeJobOffer($this);
        }

        return $this;
    }

    public function getHiringType(): ?string
    {
        return $this->hiringType;
    }

    public function setHiringType(string $hiringType): self
    {
        $this->hiringType = $hiringType;

        return $this;
    }
}
