<?php

class Fooman_EmailAttachments_Model_Updates extends Mage_AdminNotification_Model_Feed
{
    const RSS_UPDATES_URL = 'store.fooman.co.nz/extensions/news/cat/emailattachments/updates';
    const XML_GET_EMAILATTACHMENTS_UPDATES_PATH = 'foomancommon/notifications/enableemailattachmentsupdates';

    public function getFeedUrl()
    {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://')
            . self::RSS_UPDATES_URL;
        }
        return $this->_feedUrl;
    }

    public function getLastUpdate()
    {
        return Mage::app()->loadCache('emailattachments_notifications_lastcheck');
    }

    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'emailattachments_notifications_lastcheck');
        return $this;
    }

    public function checkUpdate()
    {
        if(Mage::getStoreConfigFlag(self::XML_GET_EMAILATTACHMENTS_UPDATES_PATH)){
            Mage::log('Looking for updates - Fooman EmailAttachments');
            parent::checkUpdate();
        }
    }

}