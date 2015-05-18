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
class HrmModelPayrolls extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'guid', 'a.guid',
                'employee_guid', 'a.employee_guid',
                'scale_group_guid', 'a.scale_group_guid',
                'scale_type_guid', 'a.scale_type_guid',
                'wage_guid', 'a.wage_guid',
                'employee_type', 'a.employee_type',
                'start_time', 'a.start_time',
                'end_time', 'a.end_time',
                'time_to_update', 'a.time_to_update',
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
    protected function populateState($ordering = null, $direction = null) {


        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = $app->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array')) {
            foreach ($list as $name => $value) {
                // Extra validations
                switch ($name) {
                    case 'fullordering':
                        $orderingParts = explode(' ', $value);

                        if (count($orderingParts) >= 2) {
                            // Latest part will be considered the direction
                            $fullDirection = end($orderingParts);

                            if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', ''))) {
                                $this->setState('list.direction', $fullDirection);
                            }

                            unset($orderingParts[count($orderingParts) - 1]);

                            // The rest will be the ordering
                            $fullOrdering = implode(' ', $orderingParts);

                            if (in_array($fullOrdering, $this->filter_fields)) {
                                $this->setState('list.ordering', $fullOrdering);
                            }
                        } else {
                            $this->setState('list.ordering', $ordering);
                            $this->setState('list.direction', $direction);
                        }
                        break;

                    case 'ordering':
                        if (!in_array($value, $this->filter_fields)) {
                            $value = $ordering;
                        }
                        break;

                    case 'direction':
                        if (!in_array(strtoupper($value), array('ASC', 'DESC', ''))) {
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
        if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array')) {
            foreach ($filters as $name => $value) {
                $this->setState('filter.' . $name, $value);
            }
        }

        $ordering = $app->input->get('filter_order');
        if (!empty($ordering)) {
            $list = $app->getUserState($this->context . '.list');
            $list['ordering'] = $app->input->get('filter_order');
            $app->setUserState($this->context . '.list', $list);
        }

        $orderingDirection = $app->input->get('filter_order_Dir');
        if (!empty($orderingDirection)) {
            $list = $app->getUserState($this->context . '.list');
            $list['direction'] = $app->input->get('filter_order_Dir');
            $app->setUserState($this->context . '.list', $list);
        }

        $list = $app->getUserState($this->context . '.list');

        if (empty($list['ordering'])) {
            $list['ordering'] = 'ordering';
        }

        if (empty($list['direction'])) {
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
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query
                ->select(
                        $this->getState(
                                'list.select', 'DISTINCT a.*'
                        )
        );

        $query->from('`#__hrm_payroll` AS a');


        // Join over the users for the checked out user.
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the created by field 'created_by'
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        $query->select('#__hrm_employee_1805192.fullname AS employees_fullname_1805192');
        $query->join('LEFT', '#__hrm_employee AS #__hrm_employee_1805192 ON #__hrm_employee_1805192.guid = a.employee_guid');
        // Join over the foreign key 'scale_group_guid'
        $query->select('#__hrm_scale_group_1814284.title AS scale_groups_title_1814284');
        $query->join('LEFT', '#__hrm_scale_group AS #__hrm_scale_group_1814284 ON #__hrm_scale_group_1814284.guid = a.scale_group_guid');
        // Join over the foreign key 'scale_type_guid'
        $query->select('#__hrm_scale_type_1814316.title AS scale_types_title_1814316');
        $query->join('LEFT', '#__hrm_scale_type AS #__hrm_scale_type_1814316 ON #__hrm_scale_type_1814316.guid = a.scale_group_guid');
        // Join over the foreign key 'wage_guid'
        $query->select('#__hrm_wage_12345.id AS wages_title_12345');
        $query->join('LEFT', '#__hrm_wage AS #__hrm_wage_12345 ON #__hrm_wage_12345.guid = a.wage_guid');

        if (!JFactory::getUser()->authorise('core.edit.state', 'com_hrm')) {
            $query->where('a.state = 1');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
            }
        }



        //Filtering employee_guid
        $filter_employee_guid = $this->state->get("filter.employee_guid");
        if ($filter_employee_guid) {
            $query->where("a.employee_guid = '" . $db->escape($filter_employee_guid) . "'");
        }

        //Filtering scale_group_guid
        $filter_scale_group_guid = $this->state->get("filter.scale_group_guid");
        if ($filter_scale_group_guid) {
            $query->where("a.scale_group_guid = '" . $db->escape($filter_scale_group_guid) . "'");
        }

        //Filtering scale_type_guid
        $filter_scale_type_guid = $this->state->get("filter.scale_type_guid");
        if ($filter_scale_type_guid) {
            $query->where("a.scale_type_guid = '" . $db->escape($filter_scale_type_guid) . "'");
        }

        //Filtering wage_guid
        $filter_wage_guid = $this->state->get("filter.wage_guid");
        if ($filter_wage_guid) {
            $query->where("a.wage_guid = '" . $db->escape($filter_wage_guid) . "'");
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
        foreach ($items as $item) {


            if (isset($item->employee_guid) && $item->employee_guid != '') {
                if (is_object($item->employee_guid)) {
                    $item->employee_guid = JArrayHelper::fromObject($item->employee_guid);
                }
                $values = (is_array($item->employee_guid)) ? $item->employee_guid : explode(',', $item->employee_guid);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                    ->select(array($db->quoteName('fullname')))
                    ->from('`#__hrm_employee`')
                    ->where($db->quoteName('guid') . ' = '. $db->quote($db->escape($value)))
                    ->where($db->quoteName('state') . '>= 0');
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->fullname;
                    }
                }

                $item->employee_guid = !empty($textValue) ? implode(', ', $textValue) : $item->employee_guid;
            }

            if (isset($item->scale_group_guid) && $item->scale_group_guid != '') {
                if (is_object($item->scale_group_guid)) {
                    $item->scale_group_guid = JArrayHelper::fromObject($item->scale_group_guid);
                }
                $values = (is_array($item->scale_group_guid)) ? $item->scale_group_guid : explode(',', $item->scale_group_guid);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                            ->select($db->quoteName('title'))
                            ->from('`#__hrm_scale_group`')
                            ->where($db->quoteName('guid') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->title;
                    }
                }

                $item->scale_group_guid = !empty($textValue) ? implode(', ', $textValue) : $item->scale_group_guid;
            }

            if (isset($item->scale_type_guid) && $item->scale_type_guid != '') {
                if (is_object($item->scale_type_guid)) {
                    $item->scale_type_guid = JArrayHelper::fromObject($item->scale_type_guid);
                }
                $values = (is_array($item->scale_type_guid)) ? $item->scale_type_guid : explode(',', $item->scale_type_guid);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
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

                $item->scale_type_guid = !empty($textValue) ? implode(', ', $textValue) : $item->scale_type_guid;
            }

            if (isset($item->wage_guid) && $item->wage_guid != '') {
                if (is_object($item->wage_guid)) {
                    $item->wage_guid = JArrayHelper::fromObject($item->wage_guid);
                }
                $values = (is_array($item->wage_guid)) ? $item->wage_guid : explode(',', $item->wage_guid);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                            ->select($db->quoteName('title'))
                            ->from('`#__hrm_wage`')
                            ->where($db->quoteName('guid') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->title;
                    }
                }

                $item->wage_guid = !empty($textValue) ? implode(', ', $textValue) : $item->wage_guid;
            }
            $item->employee_type = JText::_('COM_HRM_PAYROLLS_EMPLOYEE_TYPE_OPTION_' . strtoupper($item->employee_type));
        }

        return $items;
    }

    /**
     * Overrides the default function to check Date fields format, identified by
     * "_dateformat" suffix, and erases the field if it's not correct.
     */
    protected function loadFormData() {
        $app = JFactory::getApplication();
        $filters = $app->getUserState($this->context . '.filter', array());
        $error_dateformat = false;
        foreach ($filters as $key => $value) {
            if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value)) {
                $filters[$key] = '';
                $error_dateformat = true;
            }
        }
        if ($error_dateformat) {
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
    private function isValidDate($date) {
        return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
    }

}
