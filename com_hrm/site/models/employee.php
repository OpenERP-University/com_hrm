<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Hoang <hoangdau17592@gmail.com> - https://www.facebook.com/hoangdau92
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

/**
 * Hrm model.
 */
class HrmModelEmployee extends JModelItem {

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
            $id = JFactory::getApplication()->getUserState('com_hrm.edit.employee.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_hrm.edit.employee.id', $id);
        }
        $this->setState('employee.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();
        if (isset($params_array['item_id'])) {
            $this->setState('employee.id', $params_array['item_id']);
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
                $id = $this->getState('employee.id');
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

        
		if ( isset($this->_item->created_by) ) {
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}

			if (isset($this->_item->user_id) && $this->_item->user_id != '') {
				if(is_object($this->_item->user_id)){
					$this->_item->user_id = JArrayHelper::fromObject($this->_item->user_id);
				}
				$values = (is_array($this->_item->user_id)) ? $this->_item->user_id : explode(',',$this->_item->user_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('username')
							->from('`#__users`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->username;
					}
				}

			$this->_item->user_id = !empty($textValue) ? implode(', ', $textValue) : $this->_item->user_id;

			}
					$this->_item->gender = JText::_('COM_HRM_EMPLOYEES_GENDER_OPTION_' . $this->_item->gender);

			if (isset($this->_item->department_guid) && $this->_item->department_guid != '') {
				if(is_object($this->_item->department_guid)){
					$this->_item->department_guid = JArrayHelper::fromObject($this->_item->department_guid);
				}
				$values = (is_array($this->_item->department_guid)) ? $this->_item->department_guid : explode(',',$this->_item->department_guid);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('fullname')
							->from('`#__hrm_employee`')
							->where('guid = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->fullname;
					}
				}

			$this->_item->department_guid = !empty($textValue) ? implode(', ', $textValue) : $this->_item->department_guid;

			}
					$this->_item->ethnic = JText::_('COM_HRM_EMPLOYEES_ETHNIC_OPTION_' . $this->_item->ethnic);
					$this->_item->blood_group = JText::_('COM_HRM_EMPLOYEES_BLOOD_GROUP_OPTION_' . $this->_item->blood_group);

        return $this->_item;
    }

    public function getTable($type = 'Employee', $prefix = 'HrmTable', $config = array()) {
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
        $id = (!empty($id)) ? $id : (int) $this->getState('employee.id');

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
        $id = (!empty($id)) ? $id : (int) $this->getState('employee.id');

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

    
    
    //lay guid can bo theo Id
    public function getGuid_employee($id = NULL) {
        if ($id) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                    ->select($db->quoteName('guid'))
                    ->from('`#__hrm_employee`')
                    ->where($db->quoteName('id') . ' = ' . $db->quote($db->escape($id)))
                    ->order('`id` DESC');
            $db->setQuery($query);
            $guid = $db->loadResult();
            if ($guid) {
                return $guid;
            }
        } else {
            return FALSE;
        }
    }
}
