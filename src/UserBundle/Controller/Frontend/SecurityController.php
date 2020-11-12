<?php

namespace UserBundle\Controller\Frontend;

use UserBundle\UserEvents;
use UserBundle\Entity\User;
use SeoBundle\Utils\SeoManager;
use ComponentBundle\Event\FormEvent;
use Doctrine\ORM\EntityManagerInterface;
use UserBundle\Form\Type\Frontend\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use UserBundle\Form\Type\Frontend\UserLoginType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SecurityController extends AbstractController
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authChecker;

    /**
     * @var BreadcrumbsGenerator
     */
    protected $breadcrumbsGenerator;

    /**
     * @var AuthenticationUtils
     */
    protected $helper;

    /**
     * @var SeoManager
     */
    protected $seoManager;

    protected $eventDispatcher;

    protected $translator;

    protected $em;

    /**
     * SecurityController constructor.
     * @param AuthorizationCheckerInterface $authChecker
     * @param BreadcrumbsGenerator $breadcrumbsGenerator
     * @param AuthenticationUtils $helper
     * @param SeoManager $seoManager
     */
    public function __construct(
        AuthorizationCheckerInterface $authChecker, BreadcrumbsGenerator $breadcrumbsGenerator,
        AuthenticationUtils $helper, SeoManager $seoManager, EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator, EntityManagerInterface $em
    )
    {
        $this->authChecker = $authChecker;
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->helper = $helper;
        $this->seoManager = $seoManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->em = $em;
    }

    /**
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loginAction(Request $request): Response
    {
        if ($this->authChecker->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('frontend_homepage');
        }

        $referer = $this->get('request_stack')->getMasterRequest()->headers->get('referer');
        $referer_path = Request::create($referer)->getPathInfo();
        $router = $this->get('router');
        $last_route = $router->match($referer_path)['_route'];

        if($last_route == 'frontend_cart_summary') {
            $_target_path = $referer_path;
            $this->get('session')->set('login._target_path', $last_route);
        } else {
            $_target_path = '';
            $this->get('session')->remove('login._target_path');
        }

        // last authentication error (if any)
        $error = $this->helper->getLastAuthenticationError();
        // last username entered by the user (if any)
        $lastUsername = $this->helper->getLastUsername();
        $form = $this->createForm(UserLoginType::class, null, ['_target_path' => $_target_path]);
        $seo = $this->seoManager->getSeoForPage('login');
        $confirmation_mail_sent = '';

        $breadcrumbsArr = $this->breadcrumbsGenerator->getBreadcrumbForHomePage();

        $breadcrumbsArr['security_login'][] = [
            'parameters' => [],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        if($error && method_exists($error, 'getUser') &&  $error->getUser() && !$error->getUser()->isEnabled()) {
            $user = $this->em->getRepository(User::class)->find($error->getUser()->getId());
            $user->setEmailVerificationToken(null);
            $eventForm = $this->createForm(UserType::class, $user);
            $event = new FormEvent($eventForm, $request);
            $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_SUCCESS, $event);

            $this->em->flush();
            $confirmation_mail_sent = $this->translator->trans('form.email_disabled_confirmation_mail_sent', [], 'UserBundle');
        }

        return $this->render('/user/security/login.html.twig', [
            'last_username' => $lastUsername,
            'last_error' => $error,
            'form' => $form->createView(),
            'seo' => $seo,
            'confirmation_mail_sent' => $confirmation_mail_sent,
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically.
     */
    public function logout(): void
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
