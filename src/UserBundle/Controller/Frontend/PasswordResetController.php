<?php

namespace UserBundle\Controller\Frontend;

use UserBundle\UserEvents;
use SeoBundle\Utils\SeoManager;
use ComponentBundle\Event\FormEvent;
use UserBundle\Mailer\MailerInterface;
use UserBundle\Event\GetResponseUserEvent;
use UserBundle\Utils\UserManagerInterface;
use UserBundle\Utils\TokenGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Event\FilterUserResponseEvent;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use UserBundle\Event\GetResponseNullableUserEvent;
use UserBundle\Form\Type\Frontend\PasswordResetType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use UserBundle\Form\Type\Frontend\PasswordResetEmailType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class PasswordResetController
 * @package UserBundle\Controller\Frontend
 */
class PasswordResetController extends AbstractController
{
    /**
     * @var BreadcrumbsGenerator
     */
    private $breadcrumbsGenerator;

    /**
     * @var SeoManager
     */
    private $seoManager;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authChecker;

    /**
     * PasswordResetController constructor.
     * @param BreadcrumbsGenerator $breadcrumbsGenerator
     * @param SeoManager $seoManager
     * @param UserManagerInterface $userManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param MailerInterface $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(
        BreadcrumbsGenerator $breadcrumbsGenerator, SeoManager $seoManager, UserManagerInterface $userManager,
        EventDispatcherInterface $eventDispatcher, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator,
        AuthorizationCheckerInterface $authChecker
    )
    {
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->seoManager = $seoManager;
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->authChecker = $authChecker;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function indexAction(Request $request)
    {
        // if ($this->authChecker->isGranted('ROLE_USER')) {
        //     return $this->redirect($this->generateUrl('frontend_homepage'));
        // }

        $form = $this->createForm(PasswordResetEmailType::class, null, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        $breadcrumbsArr = $this->breadcrumbsGenerator->getBreadcrumbForHomePage();

        if ($form->isSubmitted() and $form->isValid()) {
            $email = $form->getData()['email'];
            $user = $this->userManager->findUserByEmail($email);

            if (null === $user) {
                return new RedirectResponse($this->get('router')->generate('security_login'));
            }

            $event = new GetResponseNullableUserEvent($user, $request);
            $this->eventDispatcher->dispatch(UserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            if (null !== $user && !$user->isPasswordRequestNonExpired(7200)) {
                $event = new GetResponseUserEvent($user, $request);
                $this->eventDispatcher->dispatch(UserEvents::RESETTING_RESET_REQUEST, $event);

                if (null !== $event->getResponse()) {
                    return $event->getResponse();
                }

                if (null === $user->getPasswordResetToken()) {
                    $user->setPasswordResetToken($this->tokenGenerator->generateToken());
                }

                $event = new GetResponseUserEvent($user, $request);
                $this->eventDispatcher->dispatch(UserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);

                if (null !== $event->getResponse()) {
                    return $event->getResponse();
                }

                $this->mailer->sendResettingEmailMessage($user);
                $user->setPasswordRequestedAt(new \DateTime());
                $this->userManager->updateUser($user);

                $event = new GetResponseUserEvent($user, $request);
                $this->eventDispatcher->dispatch(UserEvents::RESETTING_SEND_EMAIL_COMPLETED, $event);

                if (null !== $event->getResponse()) {
                    return $event->getResponse();
                }
            }

            $seo = $this->seoManager->getSeoForPage('reset_check_email');
            $breadcrumbsArr['password_reset'][] = [
                'parameters' => [],
                'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
            ];

            $breadcrumbs = $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr);

            return $this->render('user/reset/check_email.html.twig', [
                'user' => $user,
                'seo' => $seo,
                'tokenLifetime' => ceil(7200 / 3600),
                'breadcrumbs' => $breadcrumbs,
            ]);
        }

        $seo = $this->seoManager->getSeoForPage('password_reset');

        $breadcrumbsArr['password_reset'][] = [
            'parameters' => [],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        $breadcrumbs = $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr);

        return $this->render('user/reset/password-reset.html.twig', [
            'form' => $form->createView(),
            'seo' => $seo,
            'breadcrumbs' => $breadcrumbs,
            'user' => $this->getUser()
        ]);
    }

    /**
     * @param Request $request
     * @param $token
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function confirmResetAction(Request $request, $token)
    {
        $user = $this->userManager->findUserByPasswordResetConfirmationToken($token);

        if (null === $user) {
            return new RedirectResponse($this->get('router')->generate('security_login'));
        }

        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(UserEvents::RESETTING_RESET_SUCCESS, $event);

            $this->userManager->updateUser($user);

            $response = $event->getResponse();

            if (null === $response) {
                $url = $this->generateUrl('frontend_homepage');
                $response = new RedirectResponse($url);
            }

            $this->eventDispatcher->dispatch(
                UserEvents::RESETTING_RESET_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $response;
        }

        $seo = $this->seoManager->getSeoForPage('password_reset');
        $breadcrumbsArr = $this->breadcrumbsGenerator->getBreadcrumbForHomePage();
        $breadcrumbsArr['password_reset'][] = [
            'parameters' => [],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        return $this->render('user/reset/reset.html.twig', [
            'seo' => $seo,
            'token' => $token,
            'form' => $form->createView(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }
}
