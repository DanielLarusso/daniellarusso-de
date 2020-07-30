<?php declare(strict_types=1);

namespace DanielLarusso\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MeController
 * @package DanielLarusso\Controller\User
 * @Route("/me")
 */
class MeController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", name="user_me_index_action")
     */
    public function index(): Response
    {
        if (! $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirect($this->generateUrl('authentication_login_action'));
        }

        return $this->render('user/me/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/edit", name="user_me_edit_action")
     * Sec
     */
    public function edit(Request $request): Response
    {
        return $this->render('user/me/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}