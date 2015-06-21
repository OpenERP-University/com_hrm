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
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Hrm records.
 */
class HrmModelEmployees extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'guid', 'a.guid',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'firstname', 'a.firstname',
                'lastname', 'a.lastname',
                'fullname', 'a.fullname',
                'user_id', 'a.user_id',
                'other_name', 'a.other_name',
                'date_of_birth', 'a.date_of_birth',
                'gender', 'a.gender',
                'place_of_birth', 'a.place_of_birth',
                'hometown', 'a.hometown',
                'department_guid', 'a.department_guid',
                'ethnic', 'a.ethnic',
                'religion', 'a.religion',
                'address', 'a.address',
                'current_residence', 'a.current_residence',
                'profession', 'a.profession',
                'date_of_recruitment', 'a.date_of_recruitment',
                'main_job', 'a.main_job',
                'level_of_general_education', 'a.level_of_general_education',
                'highest_qualification', 'a.highest_qualification',
                'identity_card_number', 'a.identity_card_number',
                'date_of_issue', 'a.date_of_issue',
                'political_theory', 'a.political_theory',
                'state_management', 'a.state_management',
                'language_employee', 'a.language_employee',
                'informatic', 'a.informatic',
                'day_at_party', 'a.day_at_party',
                'date_official', 'a.date_official',
                'day_join_pos', 'a.day_join_pos',
                'date_of_enlistment', 'a.date_of_enlistment',
                'demobilization_date', 'a.demobilization_date',
                'highest_army_rank', 'a.highest_army_rank',
                'highest_trophies_awarded', 'a.highest_trophies_awarded',
                'forte_business', 'a.forte_business',
                'condition_health', 'a.condition_health',
                'height', 'a.height',
                'weight', 'a.weight',
                'blood_group', 'a.blood_group',
                'level_of_war_invalids', 'a.level_of_war_invalids',
                'family_policy', 'a.family_policy',
                'phone_number', 'a.phone_number',
                'time_update', 'a.time_update',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        
		//Filtering user_id
		$this->setState('filter.user_id', $app->getUserStateFromRequest($this->context.'.filter.user_id', 'filter_user_id', '', 'string'));

		//Filtering department_guid
		$this->setState('filter.department_guid', $app->getUserStateFromRequest($this->context.'.filter.department_guid', 'filter_department_guid', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_hrm');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.fullname', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'DISTINCT a.*'
                )
        );
        $query->from('`#__hrm_employee` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the foreign key 'user_id'
		$query->select('#__users_1838077.username AS users_username_1838077');
		$query->join('LEFT', '#__users AS #__users_1838077 ON #__users_1838077.id = a.user_id');
		// Join over the foreign key 'department_guid'
        $query->select('#__hrm_departments_1791296.title AS departments_title_1791296');
        $query->join('LEFT', '#__hrm_departments AS #__hrm_departments_1791296 ON #__hrm_departments_1791296.guid = a.department_guid');
        

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.fullname LIKE '.$search.'  OR  a.user_id LIKE '.$search.'  OR  a.gender LIKE '.$search.'  OR  a.hometown LIKE '.$search.'  OR  a.department_guid LIKE '.$search.'  OR  a.current_residence LIKE '.$search.' )');
            }
        }

        

		//Filtering user_id
		$filter_user_id = $this->state->get("filter.user_id");
		if ($filter_user_id) {
			$query->where("a.user_id = '".$db->escape($filter_user_id)."'");
		}

		//Filtering department_guid
		$filter_department_guid = $this->state->get("filter.department_guid");
		if ($filter_department_guid) {
			$query->where("a.department_guid = '".$db->escape($filter_department_guid)."'");
		}


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
		foreach ($items as $oneItem) {

			if (isset($oneItem->user_id)) {
				$values = explode(',', $oneItem->user_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('username'))
							->from('`#__users`')
							->where($db->quoteName('id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->username;
					}
				}

			$oneItem->user_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->user_id;

			}
					$oneItem->gender = JText::_('COM_HRM_EMPLOYEES_GENDER_OPTION_' . strtoupper($oneItem->gender));

			if (isset($oneItem->department_guid)) {
				$values = explode(',', $oneItem->department_guid);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('title'))
							->from('`#__hrm_departments`')
							->where($db->quoteName('guid') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->title;
					}
				}

			$oneItem->department_guid = !empty($textValue) ? implode(', ', $textValue) : $oneItem->department_guid;

			}
					$oneItem->ethnic = JText::_('COM_HRM_EMPLOYEES_ETHNIC_OPTION_' . strtoupper($oneItem->ethnic));
					$oneItem->blood_group = JText::_('COM_HRM_EMPLOYEES_BLOOD_GROUP_OPTION_' . strtoupper($oneItem->blood_group));
		}
        return $items;
    }

}
