<?php

namespace Proyecto404\UtilBundle\Model\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Class EntityBuilderType
 */
abstract class EntityBuilderType extends AbstractType
{
    /**
     * @var ValidatorInterface
     */
    protected $validation;

    /**
     * @param ValidatorInterface $validation
     */
    public function __construct(ValidatorInterface $validation)
    {
        $this->validation = $validation;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new EntityBuilderValidationListener($this->validation));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['class'] = 'form-horizontal';
        $view->vars['attr']['novalidate'] = 'novalidate';
    }
}
