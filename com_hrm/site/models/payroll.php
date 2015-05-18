<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Nghia <dinhtrongnghia92@gmail.com> - http://www.facebook.com/G55.RaFiKi
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

/**
 * Hrm model.
 */
class HrmModelPayroll extends JModelItem {

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {
        $app = JFactory::getApplication('com_hrm');

        // Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_hrm.edit.payroll.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_hrm.edit.payroll.id', $id);
        }
        $this->setState('payroll.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();
        if (isset($params_array['item_id'])) {
            $this->setState('payroll.id', $params_array['item_id']);
        }
        $this->setState('params', $params);
    }

    /**
     * Method to get an ojbect.
     *
     * @param	integer	The id of the object to get.
     *
     * @return	mixed	Object on success, false on failure.
     */
    public function &getData($id = null) {
        if ($this->_item === null) {
            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('payroll.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id)) {
                // Check published state.
                if ($published = $this->getState('filter.published')) {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = JArrayHelper::toObject($properties, 'JObject');
            } elseif ($error = $table->getError()) {
                $this->setError($error);
            }
        }


        if (isset($this->_item->created_by)) {
            $this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
        }

        if (isset($this->_item->employee_guid) && $this->_item->employee_guid != '') {
            if (is_object($this->_item->employee_guid)) {
                $this->_item->employee_guid = JArrayHelper::fromObject($this->_item->employee_guid);
            }
            $values = (is_array($this->_item->employee_guid)) ? $this->_item->employee_guid : explode(',', $this->_item->employee_guid);

            $textValue = array();
            foreach ($values as $value) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query
                        ->select('id')
                        ->from('`#__hrm_payroll`')
                        ->where('guid = ' . $db->quote($db->escape($value)));
                $db->setQuery($query);
                $results = $db->loadObject();
                if ($results) {
                    $textValue[] = $results->id;
                }
            }

            $this->_item->employee_guid = !empty($textValue) ? implode(', ', $textValue) : $this->_item->employee_guid;
        }

        if (isset($this->_item->scale_group_guid) && $this->_item->scale_group_guid != '') {
            if (is_object($this->_item->scale_group_guid)) {
                $this->_item->scale_group_guid = JArrayHelper::fromObject($this->_item->scale_group_guid);
            }
            $values = (is_array($this->_item->scale_group_guid)) ? $this->_item->scale_group_guid : explode(',', $this->_item->scale_group_guid);

            $textValue = array();
            foreach ($values as $value) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query
                        ->select('id')
                        ->from('`#__hrm_payroll`')
                        ->where('guid = ' . $db->quote($db->escape($value)));
                $db->setQuery($query);
                $results = $db->loadObject();
                if ($results) {
                    $textValue[] = $results->id;
                }
            }

            $this->_item->scale_group_guid = !empty($textValue) ? implode(', ', $textValue) : $this->_item->scale_group_guid;
        }

        if (isset($this->_item->scale_type_guid) && $this->_item->scale_type_guid != '') {
            if (is_object($this->_item->scale_type_guid)) {
                $this->_item->scale_type_guid = JArrayHelper::fromObject($this->_item->scale_type_guid);
            }
            $values = (is_array($this->_item->scale_type_guid)) ? $this->_item->scale_type_guid : explode(',', $this->_item->scale_type_guid);

            $textValue = array();
            foreach ($values as $value) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query
                        ->select('id')
                        ->from('`#__hrm_payroll`')
                        ->where('guid = ' . $db->quote($db->escape($value)));
                $db->setQuery($query);
                $results = $db->loadObject();
                if ($results) {
                    $textValue[] = $results->id;
                }
            }

            $this->_item->scale_type_guid = !empty($textValue) ? implode(', ', $textValue) : $this->_item->scale_type_guid;
        }

        if (isset($this->_item->wage_guid) && $this->_item->wage_guid != '') {
            if (is_object($this->_item->wage_guid)) {
                $this->_item->wage_guid = JArrayHelper::fromObject($this->_item->wage_guid);
            }
            $values = (is_array($this->_item->wage_guid)) ? $this->_item->wage_guid : explode(',', $this->_item->wage_guid);

            $textValue = array();
            foreach ($values as $value) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query
                        ->select('guid')
                        ->from('`#__hrm_payroll`')
                        ->where('id = ' . $db->quote($db->escape($value)));
                $db->setQuery($query);
                $results = $db->loadObject();
                if ($results) {
                    $textValue[] = $results->guid;
                }
            }

            $this->_item->wage_guid = !empty($textValue) ? implode(', ', $textValue) : $this->_item->wage_guid;
        }
        $this->_item->employee_type = JText::_('COM_HRM_PAYROLLS_EMPLOYEE_TYPE_OPTION_' . $this->_item->employee_type);

        return $this->_item;
    }

    public function getTable($type = 'Payroll', $prefix = 'HrmTable', $config = array()) {
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to check in an item.
     *
     * @param	integer		The id of the row to check out.
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function checkin($id = null) {
        // Get the id.
        $id = (!empty($id)) ? $id : (int) $this->getState('payroll.id');

        if ($id) {

            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Method to check out an item for editing.
     *
     * @param	integer		The id of the row to check out.
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function checkout($id = null) {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int) $this->getState('payroll.id');

        if ($id) {

            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = JFactory::getUser();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    public function getCategoryName($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('title')
                ->from('#__categories')
                ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }

    public function publish($id, $state) {
        $table = $this->getTable();
        $table->load($id);
        $table->state = $state;
        return $table->store();
    }

    public function delete($id) {
        $table = $this->getTable();
        return $table->delete($id);
    }

    //lay Id trong bag payroll
    public function getCurrentID($employee_guid = NULL) {
        if ($employee_guid) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                    ->select($db->quoteName('id'))
                    ->from('`#__hrm_payroll`')
                    ->where($db->quoteName('employee_guid') . ' = ' . $db->quote($db->escape($employee_guid)))
                    ->order('`id` DESC');
            $db->setQuery($query);
            $id = $db->loadResult();
            if ($id) {
                return $id;
            }
        } else {
            return FALSE;
        }
    }

    //
     public function updateState($employee_guid = NULL) {
        if ($employee_guid) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query = $query->update($db->quoteName('#__hrm_payroll'))->set($db->quoteName('state') . '=' . '-1')->where($db->quoteName('employee_guid') . ' = ' . $db->quote($db->escape($employee_guid)));

            $db->setQuery($query);
            $result = $db->execute();
            return $result;
        } else {
            return FALSE;
        }
    }
}
