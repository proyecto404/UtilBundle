<?php

namespace Proyecto404\UtilBundle\Model\Form;

use Proyecto404\UtilBundle\Model\Builder\EntityBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorInterface as LegacyValidatorInterface;

/**
 * Listener that validates the associated entity after the form is validated.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class EntityBuilderValidationListener implements EventSubscriberInterface
{
    private $validator;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::POST_SUBMIT => 'validateForm');
    }

    /**
     * Constructor.
     *
     * @param ValidatorInterface|LegacyValidatorInterface $validator
     */
    public function __construct($validator)
    {
        if (!$validator instanceof ValidatorInterface && !$validator instanceof LegacyValidatorInterface) {
            throw new \InvalidArgumentException(
                'Validator must be instance of Symfony\Component\Validator\Validator\ValidatorInterface '.
                'or Symfony\Component\Validator\ValidatorInterface'
            );
        }

        $this->validator = $validator;
    }

    /**
     * Validates the form and its domain object.
     *
     * @param FormEvent $event The event object
     */
    public function validateForm(FormEvent $event)
    {
        $form = $event->getForm();
        $builder = $form->getData();

        if ($form->isRoot() && $form->isValid() && $builder instanceof EntityBuilder) {
            $builder->build();
            $violations = $this->validator->validate($builder->getEntity());

            foreach ($violations as $violation) {
                $form->addError(new FormError(
                    $violation->getMessage(),
                    $violation->getMessageTemplate(),
                    $violation->getMessageParameters(),
                    $violation->getMessagePluralization(),
                    $violation
                ));
            }
        }
    }
}
