<?php

namespace PacksAnSpielBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PacksAnSpielBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
