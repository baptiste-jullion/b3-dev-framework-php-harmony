<?php

namespace App\Form;


use App\Entity\Article;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\HeaderGroupField;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\ListField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BlockQuoteField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BoldField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\CleanField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ItalicField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\StrikeField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\UnderlineField;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content', QuillType::class, [
                'attr' => [
                    'class' => 'prose',
                ],
                'quill_extra_options' => [
                    'height' => '500px',
                ],
                'quill_options' => [
                    QuillGroup::build(
                        new BoldField(),
                        new ItalicField(),
                        new UnderlineField(),
                        new StrikeField()
                    ),
                    QuillGroup::build(
                        new BlockQuoteField(),
                    ),
                    QuillGroup::build(
                        new HeaderGroupField(HeaderGroupField::HEADER_OPTION_2,
                                             HeaderGroupField::HEADER_OPTION_3,
                                             HeaderGroupField::HEADER_OPTION_4,
                                             HeaderGroupField::HEADER_OPTION_NORMAL),
                    ),
                    QuillGroup::build(
                        new ListField(ListField::LIST_FIELD_OPTION_ORDERED),
                        new ListField(ListField::LIST_FIELD_OPTION_BULLET)
                    ),
                    QuillGroup::build(
                        new CleanField()
                    ),
                ],
            ])
            ->add('save', SubmitType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'data_class' => Article::class,
                               ]);
    }
}
