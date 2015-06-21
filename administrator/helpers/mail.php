<?php
/**
* Open ERP University - HUMG
*
* Copyright (c) 2015 Open ERP University <https://github.com/OpenERP-University> - Hanoi University of Mining and Geology (HUMG)- http://humg.edu.vn 
*
* This component is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This component is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this component; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
*
* 
* @version 1.0.0
* @package com_hrm
* @copyright Copyright (c) 2015 Open ERP University - Hanoi University of Mining and Geology (HUMG)- http://humg.edu.vn 
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
* @group OpenERP University - Chuyen Trung Tran <chuyentt@gmail.com> 
* @author Leader: Tran Xuan Duc <ductranxuan.29710@gmail.com> 
* @author Dinh Trong Nghia <dinhtrongnghia92@gmail.com> 
* @author Nguyen Dau Hoang <hoangdau17592@gmail.com> 
* @author Nguyen Duc Nhan <nhannd92@gmail.com> 
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
           $send->__toString();
        } else {
            return FALSE;
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
        $titleSub = substr($title, 0, strlen($title) - 1);
        $url = JUri::base() . 'index.php?option=com_hrm&view=' . strtolower($titleSub) . '&layout=edit&id=' . $idChange;

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
           $send->__toString();
        } else {
            return FALSE;
        }
    }

    public static function sendMailNotify($listNotify =array()) {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $mailer->setSender($sender);

        $groups = JComponentHelper::getParams('com_hrm')->get('mail_usergroup');

        $recipient = HrmHelperMail::getEmailsByGroups($groups);

        $mailer->addRecipient($recipient);
        
        $table = "";
        foreach ($listNotify as $value) {
            $url = '<a href="'.JUri::base() . 'index.php?option=com_hrm&view=payroll&layout=edit&id=' . $value['id'].'">'.JText::_('COM_HRM_WAGE_LINK_READMODE').'</a>';
            
            $table .= JText::sprintf('COM_HRM_WAGE_BODY_ROW', $value['fullname'], $value['nextupdate'], $url);
        }
         
        
        $body = JText::sprintf('COM_HRM_WAGE_NOTIFY_BODY',$table);
        
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setSubject(JText::_('COM_HRM_WAGE_NOTIFY_SUBJECT'));
        $mailer->setBody($body);
        $send = $mailer->Send();
        if ($send !== true) {
           $send->__toString();
        } else {
            return FALSE;
        }
    }
    
     public static function sendMailUpdate($listUpdate =array()) {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $mailer->setSender($sender);

        $groups = JComponentHelper::getParams('com_hrm')->get('mail_usergroup');

        $recipient = HrmHelperMail::getEmailsByGroups($groups);

        $mailer->addRecipient($recipient);
        
        $table = "";
        foreach ($listUpdate as $value) {
            $url = '<a href="'.JUri::base() . 'index.php?option=com_hrm&view=payroll&layout=edit&id=' . $value['id'].'">'.JText::_('COM_HRM_WAGE_LINK_READMODE').'</a>';
            
            $table .= JText::sprintf('COM_HRM_WAGE_BODY_ROW_2', $value['fullname'], $url);
        }
         
        
        $body = JText::sprintf('COM_HRM_WAGE_BODY',$table);
        
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setSubject(JText::_('COM_HRM_WAGE_INCREASE_SUBJECT'));
        $mailer->setBody($body);
        $send = $mailer->Send();
        if ($send !== true) {
           $send->__toString();
        } else {
            return FALSE;
        }
    } 


    public static function getEmailsByGroups($groups = array()) {
        if ($groups) {
            $users = HrmHelperMail::getUsersByGroups($groups);
            return $emails = HrmHelperMail::getEmailsByUsers($users);
        }
    }

}
