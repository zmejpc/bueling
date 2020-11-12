<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\Currency;
use Ecommerce\Entity\LocaleCurrency;
use Ecommerce\Entity\ExchangeRate;
use Ecommerce\Form\Type\Dashboard\CurrencyType;
use DashboardBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CurrencyController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.currencies.currency', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CURRENCY_LIST', 'new' => 'ROLE_CURRENCY_CREATE',
            'edit' => 'ROLE_CURRENCY_EDIT', 'delete' => 'ROLE_CURRENCY_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_currency_index', 'new' => 'dashboard_currency_new',
            'edit' => 'dashboard_currency_edit', 'delete' => 'dashboard_currency_delete',
            'ajax_delete_group' => 'dashboard_currency_ajax_delete_group', 
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-dollar';
    }

    public function getFormType(): string
    {
        return CurrencyType::class;
    }

    public function getFormElement()
    {
        return new Currency();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Currency::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        $this->checkLocaleCurrency();

        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'translations-shortTitle' => $translator->trans('ui.short_title', [], 'DashboardBundle'),
            'systemName' => $translator->trans('form.system_name', [], 'DashboardBundle'),
            'position' => $translator->trans('ui.position', [], 'DashboardBundle'),
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
            'translations-shortTitle' => $item->translate()->getShortTitle(),
            'systemName' => $item->getSystemName(),
            'position' => $item->getPosition(),
        ];
    }

    private function helperForNewEditElement($object)
    {
        $repository = self::getRepository($this->em);
        $main = $repository->getMain();

        if (!$main->currencyContainsCurrency($object)) {
                $exchangeRate = new ExchangeRate();
                $exchangeRate->setRatio(1);
                $exchangeRate->setSourceCurrency($main);
                $exchangeRate->setTargetCurrency($object);
                $this->em->persist($exchangeRate);
                $this->em->flush();
            }

        return $object;
    }

    public function customActionInNewAction($object)
    {
        return self::helperForNewEditElement($object);
    }

    public function customActionInEditAction($object)
    {
        return self::helperForNewEditElement($object);
    }

    public function checkLocaleCurrency()
    {
        $locales = $this->getParameter('locale_supported');
        $currencies = $this->getRepository($this->em)->findAll();
        $currency = sizeof($currencies) ? reset($currencies) : null;

        foreach($locales as $locale) {
            $localeCurrency = $this->em->getRepository(LocaleCurrency::class)->findOneBy(['locale' => $locale]);

            if(!$localeCurrency) {
                $localeCurrency = new LocaleCurrency;
                $localeCurrency->setLocale($locale);

                if($currency)
                    $localeCurrency->setCurrency($currency);

                $this->em->persist($localeCurrency);
                $this->em->flush();
            }
        }
    }

    public function getHeadBlock()
    {
        return $this->renderView('@Ecommerce/dashboard/locale_currency/form.html.twig', [
            'localeCurrencies' => $this->em->getRepository(LocaleCurrency::class)->findAll(),
            'currencies' => $this->getRepository($this->em)->findAll(),
        ]);
    }

    public function setLocaleCurrencyAction(Request $request)
    {
        $localeCurrency = $this->em->getRepository(LocaleCurrency::class)->find($request->request->get('locale_currency_id'));
        $currency = $this->getRepository($this->em)->find($request->request->get('currency_id'));

        $localeCurrency->setCurrency($currency);

        $this->em->persist($localeCurrency);
        $this->em->flush();

        exit;
    }
}