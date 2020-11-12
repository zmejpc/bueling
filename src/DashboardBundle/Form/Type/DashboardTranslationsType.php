<?php

namespace DashboardBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DashboardTranslationsType extends AbstractType
{
    /**
     * @return string|null
     */
    public function getParent()
    {
        return TranslationsType::class;
    }
}
