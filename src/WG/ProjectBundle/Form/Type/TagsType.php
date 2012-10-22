<?php
namespace WG\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TagsType extends AbstractType {
    
    public function getDefaultOptions(array $options){
        $defaultOptions = array(
            'em' => null,
            'multiple' => true,
            'tags' => array()
        );
        
        return $defaultOptions;
    }
    
    public function getParent(){
        return 'entity';
    }

    public function getName(){
        return 'Tags';
    }
    
    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options) {
        parent::buildView($view, $form, $options);
        //$view->set('btn_label', $form->getAttribute('btn_label'));
        //$view->set('popup_content', $form->getAttribute('popup_content'));
        //$view->set('popup_generate_input', $form->getAttribute('popup_generate_input'));
        $view->set('tags', $form->getAttribute('tags'));
        //$view->set('twig_block_name', $form->getAttribute('twig_block_name'));
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {

        $builder
            //->setAttribute('btn_label', $options['btn_label'])
            //->setAttribute('popup_content', $options['popup_content'])
            //->setAttribute('popup_generate_input', $options['popup_generate_input'])
            ->setAttribute('tags', $options['tags'])
            //->setAttribute('twig_block_name', $options['twig_block_name'])
        ;
    }
    
    
    
}

?>
