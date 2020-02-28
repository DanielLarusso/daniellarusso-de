<?php declare(strict_types=1);

namespace DanielLarusso\Entity\User\Security;

use DanielLarusso\Entity\AbstractEntity;
use DanielLarusso\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Group
 * @package DanielLarusso\Entity\User\Security
 * @ORM\Entity(repositoryClass="DanielLarusso\Repository\User\Security\GroupRepository")
 * @ORM\Table(name="user_security_groups")
 */
class Group extends AbstractEntity
{
    /**
     * @var string
     * @ORM\Column(
     *     type="string",
     *     name="name",
     *     nullable=false,
     *     unique=true
     * )
     */
    private string $name = '';

    /**
     * @var Collection|User[]
     * @ORM\ManyToMany(
     *     targetEntity="DanielLarusso\Entity\User\User",
     *     mappedBy="securityGroups"
     * )
     */
    private Collection $users;

    /**
     * @var Collection|Role[]
     * @ORM\ManyToMany(
     *     targetEntity="Role",
     *     inversedBy="groups"
     * )
     * @ORM\JoinTable(
     *     name="user_security_group_role_relations",
     *     joinColumns={
     *         @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     *     }
     * )
     */
    private Collection $roles;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return User[]|Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user): self
    {
        if ($this->getUsers()->contains($user)) {
            return $this;
        }

        $this->getUsers()->add($user);
        $user->addSecurityGroup($this);

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeUser(User $user): self
    {
        if (! $this->getUsers()->contains($user)) {
            return $this;
        }

        $this->getUsers()->removeElement($user);
        $user->removeSecurityGroup($this);

        return $this;
    }

    /**
     * @return Role[]|Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role): self
    {
        if ($this->getRoles()->contains($role)) {
            return $this;
        }

        $this->getRoles()->add($role);
        $role->addGroup($this);

        return $this;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function removeRole(Role $role): self
    {
        if (! $this->getRoles()->contains($role)) {
            return $this;
        }

        $this->getRoles()->removeElement($role);
        $role->removeGroup($this);

        return $this;
    }
}
