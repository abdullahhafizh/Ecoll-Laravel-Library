<?php

namespace Ecoll\Common;

class Config {

  const SANDBOX_BASE_URL    = 'https://apibeta.bni-ecollection.com';
  const PRODUCTION_BASE_URL = 'https://api.bni-ecollection.com';

  /**
   * @return string Ecoll API URL, depends on $state
   */
  public static function getBaseUrl($state)
  {
    return $state ? Config::PRODUCTION_BASE_URL : Config::SANDBOX_BASE_URL;
  }
}
