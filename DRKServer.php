<?php

namespace dokuwiki\plugin\oauthdrkserver;

use dokuwiki\plugin\oauth\Service\AbstractOAuth2Base;
use OAuth\Common\Http\Uri\Uri;

/**
 * Custom Service for drkserver oAuth
 * 
 * @author Daniel Weisshaar <daniwei-dev@gmx.de>
 */
class DRKServer extends AbstractOAuth2Base
{
    /**
     * Defined scopes
     */
    
    const USERINFO_JSON_USER = 'name';
    const USERINFO_JSON_NAME_FIRST = 'given_name';
    const USERINFO_JSON_NAME_LAST = 'family_name';
    const USERINFO_JSON_MAIL = 'email';
    const USERINFO_JSON_GRPS = 'profile';
    
    const SCOPE_OPENID = 'openid';
    const SCOPE_EMAIL = 'email';
    const SCOPE_PROFILE = 'profile';
    
    const BUTTON_LABEL = 'drkserver';
    const BUTTON_BACKGROUND_COLOR = '#FFFFFF; color: #000000'; // injecting the font color for the button text here prevents changing the oauth plugin
    
    const ENVIRONMENT_PATH =  array('drkserver', 'drkdemo'); // possible environment paths, [0]=>'drkserver', [1]=>'drkdemo'
	
	private static $useDemoEnv = false;

    /** @inheritdoc */
    public function getAuthorizationEndpoint()
    {
        $authUrl = self::getDrkServerOpenidPath() . 'auth';
		return new Uri($authUrl);
        
    }

    /** @inheritdoc */
    public function getAccessTokenEndpoint()
    {
        $tokenUrl = self::getDrkServerOpenidPath() . 'token';
        return new Uri($tokenUrl);
    }

    /** @inheritdoc */
    public function getUserInfoEndpoint()
    {
        $userUrl = self::getDrkServerOpenidPath() . 'userinfo';
        return new Uri($userUrl);
    }

    /**
     * @inheritdoc
     */
    protected function getAuthorizationMethod()
    {
        return static::AUTHORIZATION_METHOD_HEADER_BEARER;
    }

    /**
     * @inheritdoc
     */
    private function getDrkServerOpenidPath()
    {
		// insert the choosen environment into the openid path
        $path = 'https://login.drkserver.org/auth/realms/' . self::ENVIRONMENT_PATH[self::$useDemoEnv] . '/protocol/openid-connect/';
        
        return $path;
    }
    
    /**
     * Decide between the production environment and the demo environment
	 *
	 * @param $pUseDemoMode boolean true for using demo environment, otherwise use production environment
     */
    public static function setUseDemoEnvironment($pUseDemoEnv=false)
    {
		self::$useDemoEnv = $pUseDemoEnv;
    }
}
