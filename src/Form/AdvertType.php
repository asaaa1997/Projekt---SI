<?php
/**
 * Advert type.
 */

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Status;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

/**
 * Class AdvertType.
 */
class AdvertType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var TagsDataTransformer
     */
    private $tagsDataTransformer;

    /**
     * Security.
     *
     * @var Security
     */
    private $security;

    /**
     * AdvertType constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer
     * @param Security            $security
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer, Security $security)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
        $this->security = $security;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'label_title',
                'required' => true,
                'attr' => ['max_length' => 150],
            ]
        );
        $builder->add(
            'content',
            TextType::class,
            [
                'label' => 'label_content',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => 'name',
            ]
        );
        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label_tag',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );

        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );

        $advert = $builder->getData();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            if (!is_null($advert) && null !== $advert->getId()) {
                $builder->add(
                    'status',
                    EntityType::class,
                    [
                        'class' => Status::class,
                        'choice_label' => function ($status) {
                            return $status->getTitle();
                        },
                        'label' => 'label_choose_status',
                        'required' => true,
                    ]
                );
            }
        }
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Advert::class]);
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
        return 'advert';
    }
}
