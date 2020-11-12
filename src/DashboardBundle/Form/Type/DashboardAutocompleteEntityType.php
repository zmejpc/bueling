<?php

namespace DashboardBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DashboardAutocompleteEntityType extends AbstractType
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = $options['attr'];
        $view->vars['divLg'] = $options['divLg'];
        $view->vars['labelLg'] = $options['labelLg'];
        $view->vars['helpBlock'] = $options['helpBlock'];
        $view->vars['maxLength'] = $options['maxLength'];
        $view->vars['useFormGroup'] = $options['useFormGroup'];
        $view->vars['choices'] = $options['query_builder']->getQuery()->getArrayResult();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'helpBlock' => null, 'maxLength' => null,  'useFormGroup' => true,
            'divLg' => 'col-lg-10', 'labelLg' => 'col-lg-2',
            'translation_domain' => 'DashboardBundle',
            'class' => null, 'query_builder' => null, 'choices' => []
        ]);

        parent::configureOptions($resolver);

        $queryBuilderNormalizer = function (Options $options, $queryBuilder) {
            if (\is_callable($queryBuilder)) {
                $queryBuilder = $queryBuilder($this->em->getRepository($options['class']));

                if (null !== $queryBuilder && !$queryBuilder instanceof QueryBuilder) {
                    throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder');
                }
            }

            return $queryBuilder;
        };

        $resolver->setNormalizer('query_builder', $queryBuilderNormalizer);
        $resolver->setAllowedTypes('query_builder', array('null', 'callable', 'Doctrine\ORM\QueryBuilder'));
    }
}