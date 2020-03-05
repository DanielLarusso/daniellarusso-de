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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package DanielLarusso\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="security_register_action")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        /** @var User $user */
        $user = new User();

        /** @var FormInterface $form */
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

            /** @var RegistrationConfirmation $confirmation */
            $confirmation = new RegistrationConfirmation();
            $confirmation
                ->setUser($user)
                ->setHash(\bin2hex(\random_bytes(32)))
                ->setExpiresAt((new \DateTime())->add(\DateInterval::createFromDateString('1 day')))
            ;

            $user->addConfirmation($confirmation);

            /** @var ObjectManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($confirmation);
            $em->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('security_login_action');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="security_login_action")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout_action")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
