<?php
declare(strict_types=1);

namespace App\Form;

use App\Document\Report;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ReportType
 * @package App\Form
 */
final class ReportType extends AbstractType
{
    /** @var DateTransformer  */
    private $transformer;

    /**
     * ReportType constructor.
     * @param DateTransformer $transformer
     */
    public function __construct(DateTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('generatorId', NumberType::class)
            ->add('power', NumberType::class)
            ->add('measurementTime', NumberType::class);

        $builder->get('measurementTime')
            ->addModelTransformer($this->transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
            'csrf_protection' => false,
            'method' => 'POST',
        ]);
    }
}
