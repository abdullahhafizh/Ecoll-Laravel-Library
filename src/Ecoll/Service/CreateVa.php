<?php

namespace Ecoll\Service;

use Ecoll\Common\PaycodeGenerator;

class CreateVa
{
    public static function generated($config, $params)
    {
        return PaycodeGenerator::post($config, $params);
    }
}
