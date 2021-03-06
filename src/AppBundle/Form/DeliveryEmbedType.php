<?php

namespace AppBundle\Form;

use AppBundle\Service\RoutingInterface;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class DeliveryEmbedType extends DeliveryType
{
    public function __construct(
        RoutingInterface $routing,
        TranslatorInterface $translator,
        $countryCode)
    {
        parent::__construct($routing, $translator);

        $this->countryCode = $countryCode;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = array_merge($options, ['with_store' => false]);

        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'mapped' => false,
                'label' => 'form.delivery_embed.name.label',
            ])
            ->add('email', EmailType::class, [
                'mapped' => false,
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle'
            ])
            ->add('telephone', PhoneNumberType::class, [
                'mapped' => false,
                'format' => PhoneNumberFormat::NATIONAL,
                'default_region' => strtoupper($this->countryCode),
                'label' => 'form.delivery_embed.telephone.label',
            ])
            ->add('billingAddress', AddressType::class, [
                'mapped' => false,
                'extended' => true,
            ]);
    }
}
