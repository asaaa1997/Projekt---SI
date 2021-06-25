<?php
/**
 * Tag entity.
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tags")
 *
 * @UniqueEntity(fields={"content"})
 */
class Tag
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Content.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=45
     *)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="2",
     *     max="45"
     * )
     */
    private $content;

    /**
     * Adverts.
     *
     * @var ArrayCollection|Advert[] Adverts
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Advert", mappedBy="tags")
     */
    private $adverts;

    /**
     * Updated at.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * Code.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=45
     * )
     *
     * @Gedmo\Slug(fields={"content"})
     */
    private $code;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->adverts = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for Content.
     *
     * @param string $content Content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * Getter for adverts.
     *
     * @return Collection|Advert[] Adverts collection
     */
    public function getAdverts(): Collection
    {
        return $this->adverts;
    }

    /**
     * Add advert to collection.
     *
     * @param Advert $advert Advert entity
     */
    public function addAdvert(Advert $advert): void
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts[] = $advert;
            $advert->addTag($this);
        }
    }

    /**
     * Remove advert from collection.
     *
     * @param Advert $advert Advert entity
     */
    public function removeAdvert(Advert $advert): void
    {
        if ($this->adverts->contains($advert)) {
            $this->adverts->removeElement($advert);
            $advert->removeTag($this);
        }
    }

    /**
     * Getter for Updated at.
     *
     * @return DateTimeInterface|null Updated at
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Setter for Updated at.
     *
     * @param DateTimeInterface $updatedAt Updated at
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for Code.
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Setter for Code.
     *
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}
