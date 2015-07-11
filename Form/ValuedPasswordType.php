<?php

namespace Proyecto404\UtilBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class ValuedPasswordType
 */
class ValuedPasswordType extends PasswordType
{
    /**
     * Overrides parent behavior
     *
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }
}
