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
 *     name="user_confirmation_confirmations",
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
     *     name="token",
     *     nullable=false
     * )
     */
    protected string $token = '';

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
     *     type="boolean",
     *     name="confirmed",
     *     nullable=false
     * )
     */
    protected bool $confirmed = false;

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
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return Confirmation
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

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
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     * @return Confirmation
     */
    public function setConfirmed(bool $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }
}