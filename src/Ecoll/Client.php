<?php

namespace Ecoll;

use Ecoll\Service\CreateVa;

class Client
{
    /**
     * @var array
     */
    private $config = array();

    public function isProduction($value)
    {
        $this->config['environment'] = env('ECOLL_ENV', $value);
    }

    public function setPrefix($prefix)
    {
        $this->config['prefix'] = env('ECOLL_PREFIX', $prefix);
    }

    public function setClientID($clientID)
    {
        $this->config['client_id'] = env('ECOLL_CLIENT', $clientID);
    }

    public function setSharedKey($key)
    {
        $this->config['shared_key'] = env('ECOLL_SECRET', $key);
    }

    public function getConfig()
    {
        $this->config['environment'] = env('ECOLL_ENV', $this->config['environment'] ?? '');
        $this->config['client_id'] = env('ECOLL_CLIENT', $this->config['client_id'] ?? '');
        $this->config['shared_key'] = env('ECOLL_SECRET', $this->config['shared_key'] ?? '');
        return $this->config;
    }

    public function generateVa($params)
    {
        $this->config = $this->getConfig();
        return CreateVa::generated($this->config, $params);
    }
}
