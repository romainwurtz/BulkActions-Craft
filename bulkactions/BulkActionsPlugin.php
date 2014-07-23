<?php
namespace Craft;

class BulkActionsPlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Bulk Actions');
    }

    function getVersion()
    {
        return '0.1';
    }

    function getDeveloper()
    {
        return 'Romain Wurtz';
    }

    function getDeveloperUrl()
    {
        return 'http://www.t3kila.com';
    }

    public function hasCpSection()
    {
        return true;
    }

    public function registerCpRoutes()
    {
        return array(
            'bulkactions/misc' => 'bulkactions/tabs/misc',
            'bulkactions/delete' => 'bulkactions/tabs/delete',
            'bulkactions' => 'bulkactions/tabs/delete'
        );
    }


    protected function defineSettings()
    {
        return array();
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('bulkactions/_settings', array(
            'settings' => $this->getSettings()
        ));
    }
}
