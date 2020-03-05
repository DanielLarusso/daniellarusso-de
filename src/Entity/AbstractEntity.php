<?php declare(strict_types=1);

namespace DanielLarusso\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractEntity
 * @package DanielLarusso\Entity
 */
abstract class AbstractEntity
{
    /**
     * @var null|string
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(
     *     type="guid"
     * )
     */
    protected ?string $id;

    /**
     * @var \DateTime
     * @ORM\Column(
     *     type="datetime",
     *     name="updated_at",
     *     nullable=false
     * )
     */
    protected \DateTime $updatedAt;

    /**
     * @var \DateTime
     * @ORM\Column(
     *     type="datetime",
     *     name="created_at",
     *     nullable=false
     * )
     */
    protected \DateTime $createdAt;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        /** @var \DateTime $now */
        $now = new \DateTime();

        $this->setUpdatedAt($now);
        $this->setCreatedAt($now);
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return AbstractEntity
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return AbstractEntity
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}