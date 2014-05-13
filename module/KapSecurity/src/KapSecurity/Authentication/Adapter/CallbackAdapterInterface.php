<?php

namespace KapSecurity\Authentication\Adapter;

interface CallbackAdapterInterface {

    /**
     * @return string
     */
    public function getRedirectUri();

    /**
     * @param string $uri
     */
    public function setCallbackUri($uri);
}