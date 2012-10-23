<?php

namespace WG\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', null, array(
                'label' => 'Fichier :',
                'required' => true
            ))
            /**   
            ->add('name', null, array(
                'required' => false,
                'label' => 'Renommer le fichier'
            ))
             * 
             */
            
            //->add('path')
            //->add('tags')
            ->add('tags', new \WG\ProjectBundle\Form\Type\TagsType(), array(
                'class' => 'WGProjectBundle:Tag',
                //'entities' => $options['tags'],
                'label' => 'Tags :',
                'required' => false
            ))
            //->add('project')
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
