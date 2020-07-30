<?php declare(strict_types=1);

namespace DanielLarusso\Controller;

use DanielLarusso\Form\Authentication\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class AuthenticationController
 * @package DanielLarusso\Controller
 */
class AuthenticationController extends AbstractController
{
    /**
     * @Route("/login", name="authentication_login_action")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('default_action');
        }

        /**
         * get the login error if there is one
         * @var null|AuthenticationException
         */
        $error = $authenticationUtils->getLastAuthenticationError();

        /**
         * last username entered by the user
         */
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginFormType::class, [
            '_username' => $lastUsername,
        ]);

        return $this->render('authentication/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="authentication_logout_action")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
