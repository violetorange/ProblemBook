<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $user_owner = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projects $project = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?float $cost_estimation = null;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'task')]
    private Collection $comments;

    /**
     * @var Collection<int, TimeCosts>
     */
    #[ORM\OneToMany(targetEntity: TimeCosts::class, mappedBy: 'task')]
    private Collection $timeCosts;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->timeCosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserOwner(): ?User
    {
        return $this->user_owner;
    }

    public function setUserOwner(?User $user_owner): static
    {
        $this->user_owner = $user_owner;

        return $this;
    }

    public function getProject(): ?Projects
    {
        return $this->project;
    }

    public function setProject(?Projects $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCostEstimation(): ?float
    {
        return $this->cost_estimation;
    }

    public function setCostEstimation(?float $cost_estimation): static
    {
        $this->cost_estimation = $cost_estimation;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTask($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTask() === $this) {
                $comment->setTask(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TimeCosts>
     */
    public function getTimeCosts(): Collection
    {
        return $this->timeCosts;
    }

    public function addTimeCost(TimeCosts $timeCost): static
    {
        if (!$this->timeCosts->contains($timeCost)) {
            $this->timeCosts->add($timeCost);
            $timeCost->setTask($this);
        }

        return $this;
    }

    public function removeTimeCost(TimeCosts $timeCost): static
    {
        if ($this->timeCosts->removeElement($timeCost)) {
            // set the owning side to null (unless already changed)
            if ($timeCost->getTask() === $this) {
                $timeCost->setTask(null);
            }
        }

        return $this;
    }
}
