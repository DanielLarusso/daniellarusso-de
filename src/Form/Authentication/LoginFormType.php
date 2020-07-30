<?php declare(strict_types = 1);

namespace DanielLarusso\Form\Authentication;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LoginForm
 * @package DanielLarusso\Form\Authentication
 */
class LoginFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('_username', TextType::class, [
                'label' => 'login.form.label.username',
                'attr' => [
                    'placeholder' => 'login.form.placeholder.username',
                ],
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'login.form.label.password',
                'attr' => [
                    'placeholder' => 'login.form.placeholder.password',
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'authentication',
        ]);
    }
}
