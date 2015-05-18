<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Nghia <dinhtrongnghia92@gmail.com> - http://www.facebook.com/G55.RaFiKi
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Hrm records.
 */
class HrmModelCoefficients extends JModelList {

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
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'guid', 'a.guid',
                'scale_group_guid', 'a.scale_group_guid',
                'wage_guid', 'a.wage_guid',
                'coefficient', 'a.coefficient',
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

        
		//Filtering scale_group_guid
		$this->setState('filter.scale_group_guid', $app->getUserStateFromRequest($this->context.'.filter.scale_group_guid', 'filter_scale_group_guid', '', 'string'));

		//Filtering wage_guid
		$this->setState('filter.wage_guid', $app->getUserStateFromRequest($this->context.'.filter.wage_guid', 'filter_wage_guid', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_hrm');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.scale_group_guid', 'asc');
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
        $query->from('`#__hrm_coefficient` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the foreign key 'scale_group_guid'
		$query->select('#__hrm_scale_group_1814284.title AS scale_groups_title_1814284');
		$query->join('LEFT', '#__hrm_scale_group AS #__hrm_scale_group_1814284 ON #__hrm_scale_group_1814284.guid = a.scale_group_guid');
		// Join over the foreign key 'wage_guid'
		$query->select('#__hrm_wage_12345.id AS wages_title_12345');
		$query->join('LEFT', '#__hrm_wage AS #__hrm_wage_12345 ON #__hrm_wage_12345.guid = a.wage_guid');

        

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
                $query->where('( a.scale_group_guid LIKE '.$search.'  OR  a.wage_guid LIKE '.$search.'  OR  a.coefficient LIKE '.$search.' )');
            }
        }

        

		//Filtering scale_group_guid
		$filter_scale_group_guid = $this->state->get("filter.scale_group_guid");
		if ($filter_scale_group_guid) {
			$query->where("a.scale_group_guid = '".$db->escape($filter_scale_group_guid)."'");
		}

		//Filtering wage_guid
		$filter_wage_guid = $this->state->get("filter.wage_guid");
		if ($filter_wage_guid) {
			$query->where("a.wage_guid = '".$db->escape($filter_wage_guid)."'");
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

			if (isset($oneItem->scale_group_guid)) {
				$values = explode(',', $oneItem->scale_group_guid);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('title'))
							->from('`#__hrm_scale_group`')
							->where($db->quoteName('guid') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->title;
					}
					$query = $db->getQuery(true);
                    $query
                            ->select($db->quoteName('title'))
                            ->from('`#__hrm_scale_type`')
                            ->where($db->quoteName('guid') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->title;
                    }
				}

			$oneItem->scale_group_guid = !empty($textValue) ? implode(', ', $textValue) : $oneItem->scale_group_guid;

			}

			if (isset($oneItem->wage_guid)) {
				$values = explode(',', $oneItem->wage_guid);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('title'))
							->from('`#__hrm_wage`')
							->where($db->quoteName('guid') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->title;
					}
				}

			$oneItem->wage_guid = !empty($textValue) ? implode(', ', $textValue) : $oneItem->wage_guid;

			}
		}
        return $items;
    }

}
