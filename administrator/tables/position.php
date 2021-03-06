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
 * position Table class
 */
class HrmTableposition extends JTable {

    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        parent::__construct('#__hrm_position', 'id', $db);
        JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_hrm.position'));
    }

    /**
     * Generate a globally unique identifier (GUID)
     *
     * @param	array Named array
     * @return	GUID
     */
    public function GUID() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');
        else
            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public function checkExitsGuid($GUID = NULL) {
        if ($GUID) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                    ->select('count(' . $db->quoteName('guid') . ')')
                    ->from('`#__hrm_position`')
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
        if (($task == 'save' || $task == 'apply') && (!JFactory::getUser()->authorise('core.edit.state', 'com_hrm.position.' . $array['id']) && $array['state'] == 1)) {
            $array['state'] = 0;
        }
        if ($array['id'] == 0) {
            $array['created_by'] = JFactory::getUser()->id;
            $array['guid'] = $this->checkExitsGuid();
        }

        if (!$array['guid']) {
            $array['guid'] = $this->checkExitsGuid();
        }

        $array['time_update'] = $array['checked_out_time'];

        //Support for multiple or not foreign key field: employee_guid
        if (!empty($array['employee_guid'])) {
            if (is_array($array['employee_guid'])) {
                $array['employee_guid'] = implode(',', $array['employee_guid']);
            } else if (strrpos($array['employee_guid'], ',') != false) {
                $array['employee_guid'] = explode(',', $array['employee_guid']);
            }
        } else {
            $array['employee_guid'] = '';
        }

        //Support for multiple or not foreign key field: positiontype_guid
        if (!empty($array['positiontype_guid'])) {
            if (is_array($array['positiontype_guid'])) {
                $array['positiontype_guid'] = implode(',', $array['positiontype_guid']);
            } else if (strrpos($array['positiontype_guid'], ',') != false) {
                $array['positiontype_guid'] = explode(',', $array['positiontype_guid']);
            }
        } else {
            $array['positiontype_guid'] = '';
        }

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
        if (!JFactory::getUser()->authorise('core.admin', 'com_hrm.position.' . $array['id'])) {
            $actions = JFactory::getACL()->getActions('com_hrm', 'position');
            $default_actions = JFactory::getACL()->getAssetRules('com_hrm.position.' . $array['id'])->getData();
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

        return parent::bind($array, $ignore);
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

        return 'com_hrm.position.' . (int) $this->$k;
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

    public function store($updateNulls = false) {

        if (!parent::store($updateNulls)) {
            return FALSE;
        }
        $this->sendMail();
        return TRUE;
    }

    /**
     * Automatic send mail for new user
     */
    public function sendMail() {
        HrmHelperMail::sendMailInfomation($this->id, "positions");
    }

}
