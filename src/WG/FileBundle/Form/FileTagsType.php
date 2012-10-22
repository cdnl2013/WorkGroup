<?php

namespace WG\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileTagsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', new \WG\ProjectBundle\Form\Type\TagsType(), array(
                'class' => 'WGProjectBundle:Tag',
                'tags' => $options['tags'],
                'label' => 'Tags :',
                'required' => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WG\FileBundle\Entity\File'
        ));
    }

    public function getName()
    {
        return 'wg_filebundle_filetype';
    }

}
