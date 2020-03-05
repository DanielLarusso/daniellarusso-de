<?php declare(strict_types=1);

namespace DanielLarusso\Entity\User\Confirmation;

use DanielLarusso\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Confirmation
 * @package DanielLarusso\Entity\User\Confirmation
 * @ORM\Entity
 * @ORM\Table(
 *     name="user_confirmation_confirmation",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="unique_idx",
 *             columns={
 *                 "type",
 *                 "user_id"
 *             }
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="type_idx",
 *             columns={"type"}
 *         )
 *     }
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(
 *     name="type",
 *     type="string"
 * )
 * @ORM\DiscriminatorMap({
 *     "registrtion" = "RegistrationConfirmation"
 * })
 */
abstract class Confirmation extends AbstractEntity
{
    /**
     * @var string
     */
    private string $type = '';

    /**
     * @var UserInterface
     * @ORM\ManyToOne(
     *     targetEntity="DanielLarusso\Entity\User\User",
     *     inversedBy="confirmations"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     */
    protected UserInterface $user;

    /**
     * @var string
     * @ORM\Column(
     *     type="string",
     *     name="hash",
     *     nullable=false
     * )
     */
    protected string $hash = '';

    /**
     * @var \DateTime
     * @ORM\Column(
     *     type="datetime",
     *     name="expires_at"
     * )
     */
    protected \DateTime $expiresAt;

    /**
     * @var bool
     * @ORM\Column(
     *     type="string",
     *     name="used",
     *     nullable=false
     * )
     */
    protected bool $used = false;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Confirmation
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     * @return Confirmation
     */
    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return Confirmation
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $expiresAt
     * @return Confirmation
     */
    public function setExpiresAt(\DateTime $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->used;
    }

    /**
     * @param bool $used
     * @return Confirmation
     */
    public function setUsed(bool $used): self
    {
        $this->used = $used;

        return $this;
    }
}