<?php declare(strict_types=1);

namespace DanielLarusso\Controller;

use DanielLarusso\Entity\User\Confirmation\RegistrationConfirmation;
use DanielLarusso\Entity\User\User;
use DanielLarusso\Form\RegistrationFormType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class RegistrationController
 * @package DanielLarusso\Controller
 * @Route("/registration")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/", name="registration_index_action")
     * @Route("/register", name="registration_register_action")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $confirmation = new RegistrationConfirmation();
            $confirmation
                ->setUser($user)
                ->setToken(\bin2hex(\random_bytes(32)))
                ->setExpiresAt((new \DateTime())->add(\DateInterval::createFromDateString('1 day')))
            ;

            $user->addConfirmation($confirmation);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($confirmation);
            $em->flush();

            // todo: do anything else here, like send an email

            return $this->redirectToRoute('authentication_login_action');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation/{token}", name="registration_confirmation_action")
     * @param RegistrationConfirmation $confirmation
     * @return Response
     * @throws \Exception
     */
    public function confirmation(RegistrationConfirmation $confirmation): Response
    {
        if ($confirmation->getExpiresAt() < new \DateTime()) {
            throw new \Exception('CONFIRMATION IS EXPIRED!');
        }

        if ($confirmation->isConfirmed()) {
            throw new \Exception('YOU\'VE ALREADY CONFIRMED YOUR EMAIL ADDRESS.');
        }

        return $this->redirectToRoute('authentication_login_action');
    }
}
