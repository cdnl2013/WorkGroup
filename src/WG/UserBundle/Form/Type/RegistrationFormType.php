<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WG\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {

    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class) {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('prenom', 'text', array(
                    'label' => 'Prénom',
                    'attr' => array(
                        'class' => 'span4'
                    )
                ))
                ->add('nom', 'text', array(
                    'label' => 'Nom',
                    'attr' => array(
                        'class' => 'span4'
                    )
                ))
                ->add('email', 'email', array(
                    'label' => 'Email',
                    'attr' => array(
                        'class' => 'span4'
                    )
                ))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'first_options' => array(
                        'label' => 'Mot de passe',
                        'attr' => array(
                            'class' => 'span4'
                        )
                    ),
                    'second_options' => array(
                        'label' => 'Confirmation du mot de passe',
                        'attr' => array(
                            'class' => 'span4'
                        )
                    ),
                    
                ))
                /*
                ->add('formationOption', 'choice', array(
                    'label' => 'Option',
                    'choices' => array('graph' => 'Graphisme', 'dev' => 'Développement')
                ))
                 * 
                 */
                ->add('username', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'registration',
        ));
    }

    public function getName() {
        return 'wg_user_registration';
    }

}
