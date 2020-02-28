<?php declare(strict_types=1);

namespace DanielLarusso\Entity\User\Security;

use DanielLarusso\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 * @package DanielLarusso\Entity\User\Security
 * @ORM\Entity(repositoryClass="DanielLarusso\Repository\User\Security\RoleRepository")
 * @ORM\Table(name="user_security_roles")
 */
class Role extends AbstractEntity
{
    /**
     * @ORM\Column(
     *     type="string",
     *     name="name",
     *     nullable=false,
     *     unique=true
     * )
     */
    private string $name = '';

    /**
     * @var Collection|Group[]
     * @ORM\ManyToMany(
     *     targetEntity="Group",
     *     mappedBy="roles"
     * )
     */
    private Collection $groups;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
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
     * @return Group[]|Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param Group $group
     * @return $this
     */
    public function addGroup(Group $group): self
    {
        if ($this->getGroups()->contains($group)) {
            return $this;
        }

        $this->groups->add($group);
        $group->addRole($this);

        return $this;
    }

    /**
     * @param Group $group
     * @return $this
     */
    public function removeGroup(Group $group): self
    {
        if (! $this->getGroups()->contains($group)) {
            return $this;
        }

        $this->groups->removeElement($group);
        $group->removeRole($this);

        return $this;
    }
}
