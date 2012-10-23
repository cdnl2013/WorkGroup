<?php

namespace WG\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WGUserBundle extends Bundle {
    
    public function getParent() {
        return 'FOSUserBundle';
    }
}
