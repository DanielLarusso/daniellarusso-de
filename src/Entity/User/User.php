<?php

namespace DanielLarusso\Entity\User;

use DanielLarusso\Entity\AbstractEntity;
use DanielLarusso\Entity\User\Confirmation\Confirmation;
use DanielLarusso\Entity\User\Security\Group as SecurityGroup;
use DanielLarusso\Entity\User\Security\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="DanielLarusso\Repository\User\UserRepository")
 * @ORM\Table(name="user_users")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User extends AbstractEntity implements UserInterface
{
    /**
     * @var string
     * @ORM\Column(
     *     type="string",
     *     name="email",
     *     length=180,
     *     nullable=false,
     *     unique=true
     * )
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private string $email = '';

    /**
     * @var string The hashed password
     * @ORM\Column(
     *     type="string",
     *     name="password",
     *     nullable=false
     * )
     */
    private string $password;

    /**
     * @var SecurityGroup[]|Collection
     * @ORM\ManyToMany(
     *     targetEntity="DanielLarusso\Entity\User\Security\Group",
     *     inversedBy="users"
     * )
     * @ORM\JoinTable(
     *     name="user_user_security_group_relations",
     *     joinColumns={
     *         @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *     }
     * )
     */
    private Collection $securityGroups;

    /**
     * @var Collection
     * @ORM\OneToMany(
     *     targetEntity="DanielLarusso\Entity\User\Confirmation\Confirmation",
     *     mappedBy="user",
     *     cascade={"remove", "persist"},
     *     orphanRemoval=true
     * )
     */
    private Collection $confirmations;

    /**
     * @var bool
     * @ORM\Column(
     *     type="boolean",
     *     name="active",
     *     nullable=false
     * )
     */
    private bool $active = false;

    /**
     * @var \DateTime|null
     * @ORM\Column(
     *     type="string",
     *     name="last_login",
     *     nullable=true
     * )
     */
    private ?\DateTime $lastLogin;

    public function __construct()
    {
        $this->securityGroups = new ArrayCollection();
        $this->confirmations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        /** @var array $roles */
        $roles = ['ROLE_USER'];

        /** @var SecurityGroup $group */
        foreach ($this->getSecurityGroups() as $group) {
            /** @var Role $role */
            foreach ($group->getRoles() as $role) {
                $roles[] = $role->getName();
            }
        }

        return \array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return SecurityGroup[]|Collection
     */
    public function getSecurityGroups()
    {
        return $this->securityGroups;
    }

    /**
     * @param SecurityGroup $group
     * @return $this
     */
    public function addSecurityGroup(SecurityGroup $group): self
    {
        if ($this->getSecurityGroups()->contains($group)) {
            return $this;
        }

        $this->getSecurityGroups()->add($group);
        $group->addUser($this);

        return $this;
    }

    /**
     * @param SecurityGroup $group
     * @return $this
     */
    public function removeSecurityGroup(SecurityGroup $group): self
    {
        if (! $this->getSecurityGroups()->contains($group)) {
            return $this;
        }

        $this->getSecurityGroups()->removeElement($group);
        $group->removeUser($this);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getConfirmations(): Collection
    {
        return $this->confirmations;
    }

    /**
     * @param Confirmation $confirmation
     * @return User
     */
    public function addConfirmation(Confirmation $confirmation): self
    {
        if ($this->getConfirmations()->contains($confirmation)) {
            return $this;
        }

        $this->getConfirmations()->add($confirmation);
        $confirmation->setUser($this);

        return $this;
    }

    /**
     * @param Confirmation $confirmation
     * @return User
     */
    public function removeConfirmation(Confirmation $confirmation): self
    {
        if (! $this->getConfirmations()->contains($confirmation)) {
            return $this;
        }

        $this->getConfirmations()->removeElement($confirmation);
        $confirmation->setUser(null);

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     */
    public function setLastLogin(\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
