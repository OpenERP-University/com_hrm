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

jimport('joomla.user.helper');

/**
 * employee Table class
 */
class HrmTableemployee extends JTable {

    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        parent::__construct('#__hrm_employee', 'id', $db);
        JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_hrm.employee'));
    }

    /**
     * Generate a globally unique identifier (GUID)
     *
     * @param	array Named array
     * @return	GUID
     */
    public function GUID() {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        } else {
            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        }
    }

    /**
     * Check Exits GUID From Table
     * 
     * @param type $GUID
     * @return type
     */
    public function checkExitsGuid($GUID = NULL) {
        if ($GUID) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                    ->select('count(' . $db->quoteName('guid') . ')')
                    ->from($this->_tbl)
                    ->where($db->quoteName('guid') . ' = ' . $db->quote($db->escape($GUID)));
            $db->setQuery($query);
            $results = $db->loadResult();
            if ($results == 0) {
                return $GUID;
            } else {
                $newGUID = $this->GUID();
                return $this->checkExitsGuid($newGUID);
            }
        } else {
            $newGUID = $this->GUID();
            return $this->checkExitsGuid($newGUID);
        }
    }

    /**
     * Overloaded bind function to pre-process the params.
     *
     * @param    array        Named array
     *
     * @return    null|string    null is operation was satisfactory, otherwise returns an error
     * @see        JTable:bind
     * @since      1.5
     */
    public function bind($array, $ignore = '') {


        $input = JFactory::getApplication()->input;

        $task = $input->getString('task', '');
        if (($task == 'save' || $task == 'apply') && (!JFactory::getUser()->authorise('core.edit.state', 'com_hrm.employee.' . $array['id']) && $array['state'] == 1)) {
            $array['state'] = 0;
        }
        if ($array['id'] == 0) {
            $array['created_by'] = JFactory::getUser()->id;
            $array['guid'] = $this->checkExitsGuid();
        }

//create GUID
        if (!$array['guid']) {
            $array['guid'] = $this->checkExitsGuid();
        }
        $array['fullname'] = $array['firstname'] . ' ' . $array['lastname'];



//Support for multiple or not foreign key field: user_id
        if (!empty($array['user_id'])) {
            if (is_array($array['user_id'])) {
                $array['user_id'] = implode(',', $array['user_id']);
            } else if (strrpos($array['user_id'], ',') != false) {
                $array['user_id'] = explode(',', $array['user_id']);
            }
        }
//        else {
//            $array['user_id'] = '';
//        }
//Support for multiple or not foreign key field: department_guid
        if (!empty($array['department_guid'])) {
            if (is_array($array['department_guid'])) {
                $array['department_guid'] = implode(',', $array['department_guid']);
            } else if (strrpos($array['department_guid'], ',') != false) {
                $array['department_guid'] = explode(',', $array['department_guid']);
            }
        } else {
            $array['department_guid'] = '';
        }

        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }

        if (isset($array['metadata']) && is_array($array['metadata'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['metadata']);
            $array['metadata'] = (string) $registry;
        }
        if (!JFactory::getUser()->authorise('core.admin', 'com_hrm.employee.' . $array['id'])) {
            $actions = JFactory::getACL()->getActions('com_hrm', 'employee');
            $default_actions = JFactory::getACL()->getAssetRules('com_hrm.employee.' . $array['id'])->getData();
            $array_jaccess = array();
            foreach ($actions as $action) {
                $array_jaccess[$action->name] = $default_actions[$action->name];
            }
            $array['rules'] = $this->JAccessRulestoArray($array_jaccess);
        }
//Bind the rules for ACL where supported.
        if (isset($array['rules']) && is_array($array['rules'])) {
            $this->setRules($array['rules']);
        }


//Create Mail for new User
        if ($array['id'] == 0) {
            #   $convert = $this->createNewEmail($this->convertVNese($array['fullname']));
            $emailCreate = $this->createEmailUser($array['email'], $array['fullname']);

            if (!$emailCreate) {
                return FALSE;
            }

            $array['user_id'] = $emailCreate;

            $this->addUser2Group($array['user_id']);
        }

        $result = parent::bind($array, $ignore);

//Insert Payroll for new User
        if ($result && $array['id'] == 0) {
            return $this->Insert($array['guid']);
        } else {
            return $result;
        }
    }

    /**
     * This function convert an array of JAccessRule objects into an rules array.
     *
     * @param type $jaccessrules an arrao of JAccessRule objects.
     */
    private function JAccessRulestoArray($jaccessrules) {
        $rules = array();
        foreach ($jaccessrules as $action => $jaccess) {
            $actions = array();
            foreach ($jaccess->getData() as $group => $allow) {
                $actions[$group] = ((bool) $allow);
            }
            $rules[$action] = $actions;
        }

        return $rules;
    }

    /**
     * Overloaded check function
     */
    public function check() {

//If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }
        
        //check datatypes
        if ($this->identity_card_number != NULL) {
            $lengthstr = strlen($this->identity_card_number);
            $array = str_split($this->identity_card_number);
            for ($i = 0; $i < $lengthstr; $i++) {
                if (!(int) $array[$i]) {
                    $this->setError(JText::_('COM_HRM_ERROR'));
                    return FALSE;
                    break;
                }
            }
        }
        
        if ($this->phone_number != NULL) {
            $lengthstr = strlen($this->phone_number);
            $array = str_split($this->phone_number);
            for ($i = 0; $i < $lengthstr; $i++) {
                if (!(int) $array[$i]) {
                    $this->setError(JText::_('COM_HRM_ERROR'));
                    return FALSE;
                    break;
                }
            }
        }
        
        if ($this->height != NULL) {
            $lengthstr = strlen($this->height);
            $array = str_split($this->height);
            for ($i = 0; $i < $lengthstr; $i++) {
                if (!(int) $array[$i]) {
                    $this->setError(JText::_('COM_HRM_ERROR'));
                    return FALSE;
                    break;
                }
            }
        }
        
        if ($this->weight != NULL) {
            $lengthstr = strlen($this->weight);
            $array = str_split($this->weight);
            for ($i = 0; $i < $lengthstr; $i++) {
                if (!(int) $array[$i]) {
                    $this->setError(JText::_('COM_HRM_ERROR'));
                    return FALSE;
                    break;
                }
            }
        }

        return parent::check();
    }

    /**
     * Method to set the publishing state for a row or list of rows in the database
     * table.  The method respects checked out rows by other users and will attempt
     * to checkin rows that it can after adjustments are made.
     *
     * @param    mixed    An optional array of primary key values to update.  If not
     *                    set the instance property value is used.
     * @param    integer  The publishing state. eg. [0 = unpublished, 1 = published]
     * @param    integer  The user id of the user performing the operation.
     *
     * @return    boolean    True on success.
     * @since    1.0.4
     */
    public function publish($pks = null, $state = 1, $userId = 0) {
// Initialise variables.
        $k = $this->_tbl_key;

// Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

// If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            }
// Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

                return false;
            }
        }

// Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

// Determine if there is checkin support for the table.
        if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
            $checkin = '';
        } else {
            $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
        }

// Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery(
                'UPDATE `' . $this->_tbl . '`' .
                ' SET `state` = ' . (int) $state .
                ' WHERE (' . $where . ')' .
                $checkin
        );
        $this->_db->execute();
        if ($state == '-2') {
            $checkTrue = NULL;
            foreach ($pks as $pk) {

                $db = $this->_db;
                $query = $db->getQuery(true);
                $query
                        ->select($db->quoteName('guid'))
                        ->from('`#__hrm_employee`')
                        ->where($db->quoteName('id') . ' = ' . $db->quote($db->escape($pk)));
                $db->setQuery($query);
                $guid = $db->loadResult();

                if ($guid) {
                    $db->setQuery(
                            'UPDATE `#__fm_revenue_deduction`' .
                            ' SET `state` = ' . (int) $state .
                            ' WHERE employee_guid = ' .
                            $db->quote($db->escape($guid))
                    );
                    $this->_db->execute();
                    $db->setQuery(
                            'UPDATE `#__fm_e_allowance`' .
                            ' SET `state` = ' . (int) $state .
                            ' WHERE employee_guid = ' .
                            $db->quote($db->escape($guid))
                    );
                    $this->_db->execute();
                    $db->setQuery(
                            'UPDATE `#__fm_employee_payroll`' .
                            ' SET `state` = ' . (int) $state .
                            ' WHERE employee_guid = ' .
                            $db->quote($db->escape($guid))
                    );
                    $this->_db->execute();
                    $db->setQuery(
                            'UPDATE `#__hrm_payroll`' .
                            ' SET `state` = ' . (int) $state .
                            ' WHERE employee_guid = ' .
                            $db->quote($db->escape($guid))
                    );
                    $this->_db->execute();
                    $db->setQuery(
                            'UPDATE `#__hrm_position`' .
                            ' SET `state` = ' . (int) $state .
                            ' WHERE employee_guid = ' .
                            $db->quote($db->escape($guid))
                    );
                    $this->_db->execute();
                    $db->setQuery(
                            'UPDATE `#__hrm_history_itself`' .
                            ' SET `state` = ' . (int) $state .
                            ' WHERE employee_guid = ' .
                            $db->quote($db->escape($guid))
                    );
                    $this->_db->execute();
                }
            }
        }
// If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
// Checkin each row.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

// If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks)) {
            $this->state = $state;
        }

        $this->setError('');

        return true;
    }

    /**
     * Define a namespaced asset name for inclusion in the #__assets table
     * @return string The asset name
     *
     * @see JTable::_getAssetName
     */
    protected function _getAssetName() {
        $k = $this->_tbl_key;

        return 'com_hrm.employee.' . (int) $this->$k;
    }

    /**
     * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
     *
     * @see JTable::_getAssetParentId
     */
    protected function _getAssetParentId(JTable $table = null, $id = null) {
// We will retrieve the parent-asset from the Asset-table
        $assetParent = JTable::getInstance('Asset');
// Default: if no asset-parent can be found we take the global asset
        $assetParentId = $assetParent->getRootId();
// The item has the component as asset-parent
        $assetParent->loadByName('com_hrm');
// Return the found asset-parent-id
        if ($assetParent->id) {
            $assetParentId = $assetParent->id;
        }

        return $assetParentId;
    }

    public function delete($pk = null) {
        $this->load($pk);
        $result = parent::delete($pk);
        if ($result) {
            
        }

        return $result;
    }

    /**
     * convert accented Vietnamese
     * 
     * @param type $str
     * @return type
     */
    public function convertVNese($str = NULL) {
        if ($str) {
            $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
            $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
            $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
            $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
            $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
            $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
            $str = preg_replace("/(đ)/", "d", $str);
            $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "a", $str);
            $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "e", $str);
            $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "i", $str);
            $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "o", $str);
            $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "u", $str);
            $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "y", $str);
            $str = preg_replace("/(Đ)/", "d", $str);
            $str = preg_replace("/( )/", "", $str);
            $str = strtolower($str);
        }
        return $str;
    }

    /**
     * Check Exits Email from table Users
     * 
     * @param string $str
     * @return boolean
     */
    public function checkExitsEmail($str = NULL) {
        if ($str) {
            $suffixes = JComponentHelper::getComponent('com_hrm')->params->get('suffixes_email');
            if ($suffixes) {
                $str = $str . "@" . $suffixes;
            } else {
                $str = $str . "@humg.edu.vn";
            }
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                    ->select('count(' . $db->quoteName('email') . ')')
                    ->from('`#__users`')
                    ->where($db->quoteName('email') . ' = ' . $db->quote($db->escape($str)));
            $db->setQuery($query);
            $results = $db->loadResult();
            if ($results == 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Check Exits User from table Users
     * 
     * @param string $str
     * @return boolean
     */
    public function checkExitsUser($str = NULL) {
        if ($str) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                    ->select('count(' . $db->quoteName('username') . ')')
                    ->from('`#__users`')
                    ->where($db->quoteName('username') . ' = ' . $db->quote($db->escape($str)));
            $db->setQuery($query);
            $results = $db->loadResult();
            if ($results == 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Automatic Create Email for User
     * 
     * @param type $str
     * @return string
     */
    public function createNewEmail($str = NULL) {
        $output = NULL;
        if ($str) {
            $i = 1;
            $output = $str;
            while (!$this->checkExitsEmail($output)) {
                $output = $str;
                $output = $output . $i;
                $i++;
            }
        }
        return $output;
    }

    /**
     * Automatic Create Email for User
     * 
     * @param type $str
     * @return string
     */
    public function createNewUsername($str = NULL) {
        $output = NULL;
        if ($str) {
            $i = 1;
            $output = $str;
            while (!$this->checkExitsUser($output)) {
                $output = $str;
                $output = $output . $i;
                $i++;
            }
        }
        return $output;
    }

    /**
     * Insert new User if form submit
     * 
     * @param string $email
     * @param type $fullname
     * @return boolean
     */
    public function createEmailUser($email = NULL, $fullname = NULL) {
        if ($email && $fullname) {
            $username = $this->convertVNese($fullname);

            $username = $this->createNewUsername($username);
//            $suffixes = JComponentHelper::getComponent('com_hrm')->params->get('suffixes_email');
//            if ($suffixes) {
//                $email = $email . "@" . $suffixes;
//            } else {
//                $email = $email . "@humg.edu.vn";
//            }
            $salt = JUserHelper::genRandomPassword(32);

            $autoPassword = JUserHelper::genRandomPassword();

            $crypt = JUserHelper::getCryptedPassword($autoPassword, $salt);
            $password = $crypt . ':' . $salt;
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query = "INSERT INTO #__users(name,username,email,password) VALUES (" . $db->quote($db->escape($fullname)) . "," . $db->quote($db->escape($username)) . "," . $db->quote($db->escape($email)) . "," . $db->quote($db->escape($password)) . ")";

            $db->setQuery($query);

            $result = $db->execute();

            $lastId = $db->insertid();
            if ($result) {
                HrmHelperMail::sendMailNewUser($lastId, $autoPassword);
                return $lastId;
            } else {
                return $result;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Add Group for User
     * 
     * @param type $user_id
     * @return boolean
     */
    public function addUser2Group($user_id = NULL) {
        if ($user_id) {
            $default_usergroup = JComponentHelper::getComponent('com_hrm')->params->get('default_usergroup', '2');

            $db = JFactory::getDbo();

            $query = $db->getQuery(true);

            $query = "INSERT INTO #__user_usergroup_map(user_id,group_id) VALUES (" . $db->quote($db->escape($user_id)) . "," . $db->quote($db->escape($default_usergroup)) . ")";

            $db->setQuery($query);

            $result = $db->execute();
            return $result;
        } else {
            return FALSE;
        }
    }

    /**
     * Overloaded store method for the notes table.
     *
     * @param   boolean  $updateNulls  Toggle whether null values should be updated.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   2.5
     */
    public function store($updateNulls = false) {

        if (!parent::store($updateNulls)) {
            return FALSE;
        }

        $this->sendMail();
        return TRUE;
    }

    /**
     * Automatic send mail 
     */
    public function sendMail() {
        HrmHelperMail::sendMailInfomation($this->id, "employees");
    }

    /**
     * Insert new Payroll for New User
     * 
     * @param type $employee_guid
     * @return boolean
     */
    public function Insert($employee_guid = NULL, $department_guid = NULL) {
        if ($employee_guid) {
            $db = $this->getDbo();
            $query = "INSERT INTO `#__hrm_payroll`(`guid`,`employee_guid`) VALUES (" . $db->quote($db->escape($this->checkExitsGuid(NULL, '#__hrm_payroll'))) . "," . $db->quote($db->escape($employee_guid)) . ");";
            $db->setQuery($query);
            $db->execute();

            $query = "INSERT INTO `#__fm_revenue_deduction`(`guid`,`employee_guid`) VALUES (" . $db->quote($db->escape($this->checkExitsGuid(NULL, '#__fm_revenue_deduction'))) . "," . $db->quote($db->escape($employee_guid)) . ");";
            $db->setQuery($query);
            $db->execute();

            $query = "INSERT INTO `#__fm_e_allowance`(`guid`,`employee_guid`) VALUES (" . $db->quote($db->escape($this->checkExitsGuid(NULL, '#__fm_e_allowance'))) . "," . $db->quote($db->escape($employee_guid)) . ");";
            $db->setQuery($query);
            $db->execute();

            $query = "INSERT INTO `#__fm_employee_payroll`(`guid`,`employee_guid`) VALUES (" . $db->quote($db->escape($this->checkExitsGuid(NULL, '#__fm_employee_payroll'))) . "," . $db->quote($db->escape($employee_guid)) . ");";
            $db->setQuery($query);

            $result = $db->execute();

            return $result;
        } else {
            return FALSE;
        }
    }

}
