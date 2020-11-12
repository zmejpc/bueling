<?php

namespace FrontendBundle\Controller;

use ComponentBundle\Utils\Mailer;
use Ecommerce\Entity\Product;
use FrontendBundle\Entity\Contacts;
use Knp\Component\Pager\PaginatorInterface;
use StaticBundle\Entity\StaticContent;
use StaticBundle\Entity\StaticPage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ProductCategory;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class DefaultController extends \ComponentBundle\Controller\Frontend\DefaultController
{
    public const FAIL_MESSAGE_TITLE = 'Упс... Что-то пошло не так';
    public const FAIL_MESSAGE_BODY = 'При отправке сообщения произошла ошибка. Попробуйте ещё раз.';

    public function initHeaderAction(EntityManagerInterface $em, Request $request)
    {
        $categories = $em->getRepository(ProductCategory::class)->getForFrontendMenu();

        foreach ($categories as $category) {
            $category->count = $em->getRepository(Product::class)->getCountForMenuForCategory($category);
        }

        return $this->render('default/_header.html.twig', [
            'request' => $request,
            'categories' => $categories,
        ]);
    }

    public function initFooterAction(EntityManagerInterface $em, Request $request)
    {

        $static = $em->getRepository(StaticContent::class)->getByPage('contact');

        $staticResult = [];
        foreach ($static as $item) {
            $staticResult[$item->getLinkName()]['text'] = $item->translate()->getDescription();
            $staticResult[$item->getLinkName()]['poster'] = $item->getImg();
        }

        return $this->render('default/_footer.html.twig', [
            'request' => $request,
            'staticContactContent' => $staticResult,
        ]);
    }

    public function ajaxDialogAction(bool $status, array $message, $data = [])
    {
        if (!$status && !$message)
            $message = [
                'title' => self::FAIL_MESSAGE_TITLE,
                'body' => self::FAIL_MESSAGE_BODY,
            ];

        $response = [
            'status' => $status,
            'dialog' => $this->renderView('default/dialog.html.twig', $message)
        ];

        foreach ($data as $key => $item)
            $response[$key] = $item;

        return new JsonResponse($response);
    }

    public function contactsAction(
        Request $request,
        BreadcrumbsGenerator $breadcrumbsGenerator,
        Mailer $mailer,
        ParameterBagInterface $params)
    {
        $sent_message = false;
        $breadcrumbsArr = $breadcrumbsGenerator->getBreadcrumbForHomePage();
        $breadcrumbsArr['frontend_contacts_page'][] = [
            'parameters' => [],
            'title' => 'КОНТАКТЫ'
        ];

        $static = $this->getDoctrine()->getRepository(StaticPage::class)
            ->getStaticPageBySystemName('contacts');

        if (empty($static)) {
            return $this->redirectToRoute('frontend_homepage');
        }

        $form = $this->createFormBuilder(new Contacts(), ['action' => $this->generateUrl('frontend_contacts_page')])
            ->add('name', TextType::class, ['required' => false])
            ->add('email', EmailType::class, ['required' => true])
//            ->add('subject', TextType::class, ['required' => false])
            ->add('text', TextareaType::class, ['required' => true])
            ->add('save', SubmitType::class, ['label' => 'Отправить сообщение'])
            ->getForm();

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $subject = !is_null($form->getData()->subject) ? $form->getData()->subject : 'Форма обратной связи';
                $renderedTemplate = $this->render('mail/feedback.html.twig', [
                    'data' => $form->getData(),
                ]);

                $mailer->sendEmailMessage($subject, $_ENV['FEEDBACK_EMAIL'], $renderedTemplate->getContent());
                $sent_message = true;
            }
        }

        return $this->render('default/contacts.html.twig', [
            'form' => $form->createView(),
            'page' => $static,
            'sent_message' => $sent_message,
            'breadcrumbs' => $breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }
}