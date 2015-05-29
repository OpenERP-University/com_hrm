<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Hoang <hoangdau17592@gmail.com> - https://www.facebook.com/hoangdau92
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Hrm records.
 */
class HrmModelEmployees extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
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
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{


		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');
		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');
		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
{
	$list['ordering'] = 'ordering';
}

if (empty($list['direction']))
{
	$list['direction'] = 'asc';
}

		$this->setState('list.ordering', $list['ordering']);
		$this->setState('list.direction', $list['direction']);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);

		$query->from('`#__hrm_employee` AS a');

		
    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the foreign key 'user_id'
		$query->select('#__users_1838077.username AS users_username_1838077');
		$query->join('LEFT', '#__users AS #__users_1838077 ON #__users_1838077.id = a.user_id');
		// Join over the foreign key 'department_guid'
		$query->select('#__hrm_departments_1791296.title AS departments_title_1791296');
		$query->join('LEFT', '#__hrm_departments AS #__hrm_departments_1791296 ON #__hrm_departments_1791296.guid = a.department_guid');

		
if (!JFactory::getUser()->authorise('core.edit.state', 'com_hrm'))
{
	$query->where('a.state = 1');
}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.fullname LIKE '.$search.'  OR  a.hometown LIKE '.$search.'  OR  a.current_residence LIKE '.$search.' )');
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
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		foreach($items as $item){
	

			if (isset($item->user_id) && $item->user_id != '') {
				if(is_object($item->user_id)){
					$item->user_id = JArrayHelper::fromObject($item->user_id);
				}
				$values = (is_array($item->user_id)) ? $item->user_id : explode(',',$item->user_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('username'))
							->from('`#__users`')
							->where($db->quoteName('id') . ' = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->username;
					}
				}

			$item->user_id = !empty($textValue) ? implode(', ', $textValue) : $item->user_id;

			}
					$item->gender = JText::_('COM_HRM_EMPLOYEES_GENDER_OPTION_' . strtoupper($item->gender));

			if (isset($item->department_guid) && $item->department_guid != '') {
				if(is_object($item->department_guid)){
					$item->department_guid = JArrayHelper::fromObject($item->department_guid);
				}
				$values = (is_array($item->department_guid)) ? $item->department_guid : explode(',',$item->department_guid);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('title'))
							->from('`#__hrm_departments`')
							->where($db->quoteName('guid') . ' = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->title;
					}
				}

			$item->department_guid = !empty($textValue) ? implode(', ', $textValue) : $item->department_guid;

			}
					$item->ethnic = JText::_('COM_HRM_EMPLOYEES_ETHNIC_OPTION_' . strtoupper($item->ethnic));
					$item->blood_group = JText::_('COM_HRM_EMPLOYEES_BLOOD_GROUP_OPTION_' . strtoupper($item->blood_group));
}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;
		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value))
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}
		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_HRM_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in an specified format (YYYY-MM-DD)
	 *
	 * @param string Contains the date to be checked
	 *
	 */
	private function isValidDate($date)
	{
		return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
	}

}
