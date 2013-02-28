<?php

namespace Ephp\ACLBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EphpACLBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }

}
