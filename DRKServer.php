<?php

namespace dokuwiki\plugin\oauthdrkserver;

use dokuwiki\plugin\oauth\Service\AbstractOAuth2Base;
use OAuth\Common\Http\Uri\Uri;

/**
 * Custom Service for drkserver oAuth
 */
class DRKServer extends AbstractOAuth2Base
{
    /**
     * Defined scopes
     */
    
    const USERINFO_JSON_USER = 'name';
    const USERINFO_JSON_NAME = 'given_name';
    const USERINFO_JSON_MAIL = 'email';
    const USERINFO_JSON_GRPS = 'profile';
    
    const SCOPE_OPENID = 'openid';
    const SCOPE_EMAIL = 'email';
    const SCOPE_PROFILE = 'profile';

    const BUTTON_LABEL = 'drkserver';
    const BUTTON_BACKGROUND_COLOR = '#FFFFFF; color: #000000'; // injecting the font color prevents changing the oauth plugin

    const PATH_DEMO = 'drkdemo';
    const PATH_REAL = 'drkserver';

    /** @inheritdoc */
    public function getAuthorizationEndpoint()
    {
        $path = ($this->getConf('demomode')==1) ? $this->PATH_DEMO : $this->PATH_REAL;
        $authUrl = 'https://login.drkserver.org/auth/realms/{$path}/protocol/openid-connect/auth';
        return new Uri($authUrl);
        
    }

    /** @inheritdoc */
    public function getAccessTokenEndpoint()
    {
        $path = ($this->getConf('demomode')==1) ? $this->PATH_DEMO : $this->PATH_REAL;
        $tokenUrl = 'https://login.drkserver.org/auth/realms/{$path}/protocol/openid-connect/token';
        return new Uri($tokenUrl);
    }

    /** @inheritdoc */
    public function getUserInfoEndpoint()
    {
        $path = ($this->getConf('demomode')==1) ? $this->PATH_DEMO : $this->PATH_REAL;
        $userUrl = 'https://login.drkserver.org/auth/realms/{$path}/protocol/openid-connect/userinfo';
        return new Uri($userUrl);
    }

    /**
     * @inheritdoc
     */
    protected function getAuthorizationMethod()
    {
        return static::AUTHORIZATION_METHOD_HEADER_BEARER;
    }
}
