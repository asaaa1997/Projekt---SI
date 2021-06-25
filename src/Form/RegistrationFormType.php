<?php
/**
 * RegistrationFormType.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RegistrationFormType.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * Build Form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'label' => 'label_username',
                'required' => true,
            ]
        );
        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(
                        [
                            'min' => 6,
                            'max' => 180,
                        ]
                    ),
                ],
                'first_options' => ['label' => 'label_password'],
                'second_options' => ['label' => 'label_repeat_password'],
            ]
        );
        $builder->add(
            'userdata',
            UserDataType::class,
            [
                'label' => 'label_userdata',
                'required' => true,
            ]
        );
    }

    /**
     * Configure Options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
