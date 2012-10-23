<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WG\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as baseHandler;

class RegistrationFormHandler extends baseHandler {

    /**
     * @param boolean $confirmation
     */
    public function process($confirmation = false) {
        $user = $this->createUser();
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod()) {

            //Generate username
            $parameters = $this->request->request->all();
            $username = $this->convertFullNameToUserName($parameters['fos_user_registration_form']['nom'], $parameters['fos_user_registration_form']['prenom']);
            $parameters['fos_user_registration_form']['username'] = $username;
            $this->request->request->replace($parameters);

            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user, $confirmation);

                return true;
            }
        }

        return false;
    }

    /**
     * If someone see this and want to put it in a service, you're welcome.
     */
    private function convertFullNameToUserName($nom, $prenom) {
        //return ucfirst(strtolower($prenom)) . '' . ucfirst(strtolower($nom));
        return ucfirst(strtolower($prenom)) . ' ' . strtoupper(substr($nom, 0, 1)) . '. _' . md5($nom . '' . $prenom . '' . time());
    }

}
