<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Form;

use Dige\TinypngPlugin\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', CheckboxType::class,[
                'required' => false,
                'label' => 'tinypng.ui.settings.form.enabled',
                'help' => 'tinypng.ui.settings.form.enabled_help',
            ])
            ->add('apiKey', TextType::class, [
                'required' => true,
                'label' => 'tinypng.ui.settings.form.api_key'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class
        ]);
    }
}
