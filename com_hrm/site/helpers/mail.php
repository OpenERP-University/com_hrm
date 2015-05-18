<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Hrm helper.
 */
class HrmHelperMail {

    /**
     * Get User by Groups 
     * 
     * @param type $groups
     * @return int
     */
    public static function getUsersByGroups($groups = array()) {
        if ($groups) {
            $Users = array();
            $access = new JAccess;
            if (is_array($groups)) {
                foreach ($groups as $group) {
                    $Users = array_merge($Users, $access->getUsersByGroup($group));
                }
            } else {
                $Users = $access->getUsersByGroup($groups);
            }
            return $Users;
        } else {
            return 0;
        }
    }

    /**
     * Get List Email 
     * 
     * @param $Users
     * @return array 
     */
    public static function getEmailsByUsers($Users = array()) {
        if ($Users) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                    ->select('email')
                    ->from('#__users');

            if (is_array($Users)) {
                $query->where('id IN (' . implode(',', $Users) . ')');
            } else {
                $query->where('id = ' . (int) $Users);
            }
            $db->setQuery($query);
            $rows = $db->loadColumn();

            return $rows;
        } else {
            return 0;
        }
    }

    public static function sendMailNewUser($User, $password) {
        $user = JFactory::getUser($User);
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );

        $mailer->setSender($sender);
        $recipient = $user->email;
        $mailer->addRecipient($recipient);

        $body = JText::sprintf('COM_HRM_NEW_USER_EMAIL_BODY', $user->name, $config->get('sitename'), $config->get('url'), $user->username, $password);

        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setSubject(JText::_('COM_HRM_NEW_USER_EMAIL_SUBJECT'));
        $mailer->setBody($body);
        $send = $mailer->Send();
        if ($send !== true) {
            echo 'Error sending email: ' . $send->__toString();
        } else {
            echo 'Mail sent';
        }
    }

    /**
     * Send mail if infomation changed
     * 
     * @param type $Site
     * @param type $url
     */
    public static function sendMailInfomation($idChange, $title) {
        $user = JFactory::getUser();
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );

        $Site = JText::_('COM_HRM_TITLE_' . strtoupper($title));
        $titleSub = substr($title,0, strlen($title) - 1);
        $url = JUri::base() . 'administrator/index.php?option=com_hrm&view=' . strtolower($titleSub) . '&layout=edit&id=' . $idChange;

        $mailer->setSender($sender);

        $groups = JComponentHelper::getParams('com_hrm')->get('mail_usergroup');

        $recipient = HrmHelperMail::getEmailsByGroups($groups);

        $mailer->addRecipient($recipient);

        $body = JText::sprintf('COM_HRM_EDIT_INFORMATION_BODY', $Site, $user->name, $url);

        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setSubject(JText::plural('COM_HRM_EDIT_INFORMATION_SUBJECT', $Site));
        $mailer->setBody($body);
        $send = $mailer->Send();
        if ($send !== true) {
            echo 'Error sending email: ' . $send->__toString();
        } else {
            echo 'Mail sent';
        }
    }

    public static function getEmailsByGroups($groups = array()) {
        if ($groups) {
            $users = HrmHelperMail::getUsersByGroups($groups);
            return $emails = HrmHelperMail::getEmailsByUsers($users);
        }
    }

}
