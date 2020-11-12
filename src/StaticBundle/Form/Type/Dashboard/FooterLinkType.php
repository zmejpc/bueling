<?php

namespace StaticBundle\Form\Type\Dashboard;

use Ecommerce\Entity\ProductCategory;
use StaticBundle\Entity\FooterLink;
use Doctrine\ORM\EntityRepository;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardSelect2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Design studio origami <https://origami.ua>
 */
class FooterLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', DashboardTextType::class, [
                'label' => 'Название',
                'required' => false,
            ])
            ->add('link', DashboardTextType::class, [
                'label' => 'Ссылка',
                'required' => false,
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.priority',
            ])
            ->add('productCategory', DashboardSelect2EntityType::class, [
                'label' => 'Категория',
                'class' => ProductCategory::class,
                'choice_label' => 'translate.title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->getCategoriesForProductForm();
                },
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FooterLink::class,
            'grantedRoles' => null,
        ]);
    }
}