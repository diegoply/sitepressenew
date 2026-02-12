<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?float $average_note = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $published_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, ArticleNote>
     */
    #[ORM\OneToMany(targetEntity: ArticleNote::class, mappedBy: 'article', orphanRemoval: true, cascade: ['remove'])]
    private Collection $article_note;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?ArticleCategory $article_category = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article', orphanRemoval: true, cascade: ['remove'])]
    private Collection $comment;

    public function __construct()
    {
        $this->article_note = new ArrayCollection();
        $this->comment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAverageNote(): ?float
    {
        return $this->average_note;
    }

    public function setAverageNote(?float $average_note): static
    {
        $this->average_note = $average_note;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->published_at;
    }

    public function setPublishedAt(?\DateTimeImmutable $published_at): static
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, ArticleNote>
     */
    public function getArticleNote(): Collection
    {
        return $this->article_note;
    }

    public function addArticleNote(ArticleNote $articleNote): static
    {
        if (!$this->article_note->contains($articleNote)) {
            $this->article_note->add($articleNote);
            $articleNote->setArticle($this);
        }

        return $this;
    }

    public function removeArticleNote(ArticleNote $articleNote): static
    {
        if ($this->article_note->removeElement($articleNote)) {
            // set the owning side to null (unless already changed)
            if ($articleNote->getArticle() === $this) {
                $articleNote->setArticle(null);
            }
        }

        return $this;
    }

    public function getArticleCategory(): ?ArticleCategory
    {
        return $this->article_category;
    }

    public function setArticleCategory(?ArticleCategory $article_category): static
    {
        $this->article_category = $article_category;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getStatusLabel(): string
    {
    return match($this->status) {
        'DRAFT' => 'Brouillon',
        'PUBLISHED' => 'Publié',
        'ARCHIVED' => 'Archivé',
        default => 'Inconnu',
    };
    }
}
