<?php

namespace UserBundle\Controller\Dashboard;

use Twig\Environment;
use UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\DiscountMailSetting;
use Ecommerce\Entity\Order;
use UserBundle\Form\Type\Dashboard\UserType;
use DashboardBundle\Controller\CRUDController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UserController extends CRUDController
{
    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_DIRECTOR', 'new' => null,
            'edit' => 'ROLE_DIRECTOR', 'delete' => null
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_user_index', 'new' => null,
            'edit' => 'dashboard_user_edit', 'delete' => null,
        ];
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return
            $this->translator->trans('sidebar.configuration.configuration', [], 'DashboardBundle')
            . ' > ' .
            $this->translator->trans('sidebar.customers.users', [], 'UserBundle');
    }


    public function getHeadTitleForEdit($object)
    {
        return ' > ' . $object->getDisplayName();
    }
    
    /**
     * @return array
     */
    public function getPortletHeadIcon(): array
    {
        return [
            'icon' => 'flaticon-users',
            'useSvg' => true,
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" id="Path-50" fill="#000000" opacity="0.3"/>
        <path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" id="Mask" fill="#000000" opacity="0.3"/>
        <path d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" id="Mask-Copy" fill="#000000" opacity="0.3"/>
    </g>
</svg>'
        ];
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\UserBundle\Entity\Repository\UserRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        return $this->em->getRepository(User::class);
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'email' => $this->translator->trans('ui.email', [], 'DashboardBundle'),
            'name' => $this->translator->trans('ui.name_first', [], 'DashboardBundle'),
            'surname' => $this->translator->trans('ui.lastname', [], 'DashboardBundle'),
            'phoneNumber' => $this->translator->trans('ui.phone', [], 'DashboardBundle'),
            'notinsearch_alias_ordersSum' => 'Сумма заказов',
            'lastLogin' => $this->translator->trans('ui.last_login', [], 'DashboardBundle'),
            'enabled' => $this->translator->trans('form.user.enabled', [], 'UserMessages'),
            'locked' => $this->translator->trans('ui.locked', [], 'DashboardBundle'),
            'verifiedAt' => $this->translator->trans('ui.email_verified', [], 'DashboardBundle'),
        ];
    }

    /**
     * @param $item
     * @return array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createDataForList($item, Environment $twig): array
    {
        return [
            'email' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getEmail(),
            ]),
            'name' => $item->getName(),
            'surname' => $item->getSurname(),
            'phoneNumber' => $item->getPhoneNumber(),
            'notinsearch_alias_ordersSum' => $this->em->getRepository(Order::class)->getTotalPriceByUser($item),
            'lastLogin' => $this->twig->render('@Dashboard/default/crud/list/element/_data.html.twig', [
                'element' => $item->getLastLogin(),
            ]),
            'enabled' => $this->twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->isEnabled(),
            ]),
            'locked' => $this->twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => !$item->isAccountNonLocked(),
            ]),
            'verifiedAt' => $this->twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->isVerified(),
            ])
        ];
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return UserType::class;
    }

    /**
     * @return User
     */
    public function getFormElement()
    {
        return new User();
    }

    public function customActionInEditAction($object)
    {
        if($object->getDiscount() && filter_var($object->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $uow = $this->em->getUnitOfWork();
            $originalData = $uow->getOriginalEntityData($object);

            if($originalData['discount_id'] != $object->getDiscount()->getId()) {
                if($discountMailSetting = $this->em->getRepository(DiscountMailSetting::class)->getElement()) {

                    try {

                        $transport = (new \Swift_SmtpTransport(
                            $discountMailSetting->getSmtpHost(), $discountMailSetting->getSmtpPort()
                        ));
                        $transport
                            ->setUsername($discountMailSetting->getSmtpUsername())
                            ->setPassword($discountMailSetting->getSmtpPassword());

                        $mailer = new \Swift_Mailer($transport);

                        $body = $this->twig->render('mail/user/discount/index.html.twig', [
                            'message' => str_replace('[username]', $object->getDisplayName(), $discountMailSetting->getMessageBody()),
                            'discount' => $object->getDiscount(),
                            'footerMessage' => $discountMailSetting->getMessageFooter(),
                        ]);

                        $subject = str_replace('[discount]', $object->getDiscount()->getTitle(), $discountMailSetting->getMessageSubject());

                        $message = (new \Swift_Message($subject))
                            ->setFrom([$discountMailSetting->getSmtpUsername() => $discountMailSetting->getSenderName()])
                            ->setTo($object->getEmail())
                            ->setBody($body, 'text/html; charset=utf-8');
                        $mailer->send($message);

                    } catch (\Throwable $e) {
                        // dump($e->getMessage());exit;
                    }

                }
            }
        }
        
        return $object;
    }

    public function getPortletBodyTemplateForForm(): string
    {
        return '@User/dashboard/form/_portlet_body.html.twig';
    }
}