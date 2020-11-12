<?php

namespace Ecommerce\Form\Type\Dashboard;

use SeoBundle\Form\Type\Dashboard\SeoType;
use DashboardBundle\Form\Type\DashboardCollectionType;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardSelect2EntityType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardNumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Ecommerce\Entity\Product;
use Ecommerce\Entity\Currency;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use Ecommerce\Entity\ProductCategory;
use Ecommerce\Entity\ProductCapsule;
use Ecommerce\Entity\ProductCollaboration;
use Ecommerce\Entity\ProductSize;
use Ecommerce\Entity\ProductDiscount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = null;
        if ($builder->getData()) {
            $id = $builder->getData()->getId();
        }

        $builder
            ->add('seo', SeoType::class)
            ->add('translations', DashboardTranslationsType::class, [
                'label' => false,
                'fields' => [
                    'title' => [
                        'field_type' => DashboardTextType::class,
                        'label' => 'ui.title',
                        'helpBlock' => null,
                        'maxLength' => 255
                    ],
                    'description' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'medium'
                        ],
                        'label' => 'ui.description',
                        'required' => false,
                    ],
                    'composition' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'medium'
                        ],
                        'label' => 'ui.composition',
                        'required' => false,
                    ],
                    'sizes' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'medium'
                        ],
                        'label' => 'ui.sizes',
                        'required' => false,
                    ],
                ],
                'excluded_fields' => ['createdAt', 'updatedAt', 'slug']
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
            ])
             ->add('topSale', DashboardYesNoType::class, [
                 'label' => 'ui.top_sale',
                 'translation_domain' => 'DashboardBundle',
                 'required' => false,
                 'labelLg' => 'col-md-6',
                 'divLg' => 'col-md-6',
             ])
            // ->add('isNova', DashboardYesNoType::class, [
            //     'label' => 'Новинка',
            //     'translation_domain' => 'DashboardBundle',
            //     'required' => false,
            //     'labelLg' => 'col-md-6',
            //     'divLg' => 'col-md-6',
            // ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
                'labelLg' => 'col-md-6',
                'divLg' => 'col-md-6',
            ])
            ->add('price', DashboardNumberType::class, [
                'label' => 'ui.price',
                'attr' => [
                    'step' => 0.01
                ]
            ])
            ->add('currency', DashboardSelect2EntityType::class, [
                'label' => 'ui.currency',
                'required' => false,
                'class' => Currency::class,
                'choice_label' => 'translate.title',
                'disabled' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->getCurrencyForProductVariantForm();
                },
            ])
            ->add('categories', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'ui.categories',
                'class' => ProductCategory::class,
                'choice_label' => 'translate.title',
            ])
            ->add('capsules', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'Капсулы',
                'class' => ProductCapsule::class,
                'choice_label' => 'translate.title',
            ])
            ->add('collaborations', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'Коллаборации',
                'class' => ProductCollaboration::class,
                'choice_label' => 'translate.title',
            ])
            ->add('status', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => false,
                'label' => 'ui.status',
                'class' => \Ecommerce\Entity\ProductStatus::class,
                'choice_label' => 'translate.title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->getProductStatusesForOrderForm();
                },
            ])
            // ->add('residual', DashboardTextType::class, [
            //     'label' => 'Остаток',
            //     'translation_domain' => 'DashboardBundle',
            //     'disabled' => true,
            // ])
            ->add('associations', DashboardCollectionType::class, [
                'prototype_template' => '@Ecommerce/dashboard/product/form/_product_associations_prototype.html.twig',
                'entry_type' => ProductAssociatedType::class,
                'entry_options' => [
                    'product' => $id
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('galleryImages', DashboardCollectionType::class, [
                'prototype_template' => '@Ecommerce/dashboard/product/form/_product_gallery_images_prototype.html.twig',
                'entry_type' => ProductGalleryImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('sizes', DashboardCollectionType::class, [
                'prototype_template' => '@Ecommerce/dashboard/product/form/_product_sizes_prototype.html.twig',
                'entry_type' => ProductHasSizeType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('discount', DashboardSelect2EntityType::class, [
                'label' => 'ui.discount',
                'class' => ProductDiscount::class,
                'choice_label' => 'translate.title',
                'required' => false,
                'allow_clear' => true,
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Product::class, 'grantedRoles' => null]);
    }
}