<?php

/**
 * User nickname type.
 */

namespace App\Form\Type\User;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserNicknameType.
 */
class UserNicknameType extends AbstractType
{
    /**
     * Constructor.
     *
     * @param Security $security Security
     */
    public function __construct(private readonly Security $security)
    {
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'nickname',
            TextType::class,
            [
                'label' => 'label.nickname',
                'required' => true,
            ]
        );

        if (!$this->security->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'password',
                PasswordType::class,
                [
                    'mapped' => false,
                    'label' => 'label.authenticate_password',
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            );
        }
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'user_nickname';
    }
}
