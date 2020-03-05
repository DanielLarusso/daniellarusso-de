<?php declare(strict_types=1);

namespace DanielLarusso\Entity\User\Confirmation;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RegistrationConfirmation
 * @package DanielLarusso\Entity\User\Confirmation
 *
 * Represent a confirmation item.
 * Note that this class extends Confirmation
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class RegistrationConfirmation extends Confirmation
{

}