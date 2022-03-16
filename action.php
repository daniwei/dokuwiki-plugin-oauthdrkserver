<?php

use dokuwiki\plugin\oauth\Adapter;
use dokuwiki\plugin\oauthdrkserver\DRKServer;

// Check if the oauth plugin is installed
if(!plugin_load('helper', 'oauth')){
    msg('The plugin oauthdrkserver requires the oauth plugin. Please install it.', -1);
    return;
}

/**
 * Service Implementation for oAuth drkserver authentication
 * 
 * @author Daniel Weisshaar <daniwei-dev@gmx.de>
 */
class action_plugin_oauthdrkserver extends Adapter
{
    /** @inheritdoc */
    public function registerServiceClass()
    {
        $useDemoEnv = $this->getConf('demoenv');
        DRKServer::setUseDemoEnvironment($useDemoEnv);
        return DRKServer::class;
    }

    /** * @inheritDoc */
    public function getUser()
    {
        $oauth = $this->getOAuthService();
        $data = array();

        $result = json_decode($oauth->request(DRKServer::getUserInfoEndpoint()), true);
        
        $data['user'] = $result[DRKServer::USERINFO_JSON_USER];
        $data['name'] = $result[DRKServer::USERINFO_JSON_NAME_FIRST] . ' ' . $result[DRKServer::USERINFO_JSON_NAME_LAST];
        $data['mail'] = $result[DRKServer::USERINFO_JSON_MAIL];
        $data['grps'] = $result[DRKServer::USERINFO_JSON_GRPS];

        return $data;
    }

    /** @inheritdoc */
    public function getScopes()
    {
        $scopes = [DRKServer::SCOPE_OPENID, DRKServer::SCOPE_EMAIL, DRKServer::SCOPE_PROFILE];
        return $scopes;
    }

    /** @inheritDoc */
    public function getLabel()
    {
        return DRKServer::BUTTON_LABEL;
    }

    /** @inheritDoc */
    public function getColor()
    {
        return DRKServer::BUTTON_BACKGROUND_COLOR;
    }
}
