<?php

namespace Ecommerce\Form\Type\Dashboard;

use SeoBundle\Form\Type\Dashboard\SeoType;
use UploadBundle\Form\Type\UploadType;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardSelect2EntityType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardCollectionType;
use Doctrine\ORM\EntityRepository;
use Ecommerce\Entity\ProductCategory;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use Symfony\Component\Security\Core\Security;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductCategoryType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                ],
                'excluded_fields' => ['createdAt', 'updatedAt', 'slug', 'treeTitle']
            ])
            ->add('poster', UploadType::class, [
                'file_type' => 'product_category_poster',
                'extensions' => '.jpg, .gif, .png, .svg',
                'label' => 'ui.poster',
                'required' => false,
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
            ])
            // ->add('parent', DashboardSelect2EntityType::class, [
            //     'label' => 'ui.parent',
            //     'class' => ProductCategory::class,
            //     'choice_label' => 'translate.title',
            //     'query_builder' => function (EntityRepository $er) use ($notArr) {
            //         return $er->getParentForProductCategoryNewForm($notArr);
            //     },
            //     'required' => false
            // ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ProductCategory::class, 'grantedRoles' => null]);
    }
}