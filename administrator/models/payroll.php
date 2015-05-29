<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Hrm model.
 */
class HrmModelPayroll extends JModelAdmin {

    /**
     * @var		string	The prefix to use with controller messages.
     * @since	1.6
     */
    protected $text_prefix = 'COM_HRM';
    public $typeAlias = 'com_hrm.payroll';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Payroll', $prefix = 'HrmTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		An optional array of data for the form to interogate.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	JForm	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_hrm.payroll', 'payroll', array('control' => 'jform', 'load_data' => $loadData));


        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_hrm.edit.payroll.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param	integer	The id of the primary key.
     *
     * @return	mixed	Object on success, false on failure.
     * @since	1.6
     */
    public function getItem($pk = null) {
        if ($item = parent::getItem($pk)) {

            //Do any procesing on fields here if needed
        }

        return $item;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @since	1.6
     */
    protected function prepareTable($table) {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__hrm_payroll');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

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

    public function getLastGuid($guid = NULL) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select($db->quoteName('guid'))
                ->from('`#__hrm_employee`')
                ->where('1')
                ->order('`id` DESC');
        $db->setQuery($query);
        $guid = $db->loadResult();
        if ($guid) {
            return $guid;
        } else {
            return FALSE;
        }
    }

    public function updateState($employee_guid = NULL) {
        if ($employee_guid) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query = $query->update($db->quoteName('#__hrm_payroll'))
                    ->set($db->quoteName('state') . '=' . '-1')
                    ->where($db->quoteName('employee_guid') . ' = ' . $db->quote($db->escape($employee_guid)));

            $db->setQuery($query);

            $result = $db->execute();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function getPayrollsUpdate($time = NULL) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        if (!$time) {
            $query
                    ->select('#__hrm_payroll.id,#__hrm_payroll.guid,#__hrm_payroll.scale_group_guid,#__hrm_payroll.wage_guid,employee_guid,DATE_ADD(IF(`last_update` = 0,`start_time`,`last_update`),INTERVAL  `time_to_update` YEAR) AS timeupdate')
                    ->from('#__hrm_payroll')
                    ->select('#__hrm_employee.fullname')
                    ->join('LEFT', '#__hrm_employee ON #__hrm_employee.guid = #__hrm_payroll.employee_guid')
                    ->select('#__hrm_scale_group.wage_max')
                    ->join('LEFT', '#__hrm_scale_group ON #__hrm_scale_group.guid = #__hrm_payroll.scale_group_guid')
                    ->select('#__hrm_wage.title AS wage')
                    ->join('LEFT', '#__hrm_wage ON #__hrm_wage.guid = #__hrm_payroll.wage_guid')
                    ->where('DATE_ADD(IF(`last_update` = 0,`start_time`,`last_update`),INTERVAL  `time_to_update` YEAR) <= NOW()')
                    ->where('#__hrm_wage.title != #__hrm_scale_group.wage_max');
        } else {
            $query
                    ->select('#__hrm_payroll.id,#__hrm_payroll.guid,#__hrm_payroll.scale_group_guid,#__hrm_payroll.wage_guid,employee_guid,DATEDIFF(DATE_ADD(IF(`last_update` = 0,`start_time`,`last_update`),INTERVAL  `time_to_update` YEAR),NOW()) AS nextupdate')
                    ->from('#__hrm_payroll')
                    ->select('#__hrm_employee.fullname')
                    ->join('LEFT', '#__hrm_employee ON #__hrm_employee.guid = #__hrm_payroll.employee_guid')
                    ->where('DATE_ADD(IF(`last_update` = 0,`start_time`,`last_update`),INTERVAL  `time_to_update` YEAR) > NOW()')
                    ->where('DATE_ADD(IF(`last_update` = 0,`start_time`,`last_update`),INTERVAL  `time_to_update` YEAR) <= DATE_ADD(NOW(),INTERVAL ' . $time . ' MONTH)');
        }
        $db->setQuery($query);
        return $db->loadAssocList();
    }

    public function updatePayrolls($listPayroll = array()) {
        if ($listPayroll) {
            $db = JFactory::getDbo();
            foreach ($listPayroll as $item) {
                try {
                    $nextWage = (int) $item['wage'] + 1;
                    $queryNextWage = $db->getQuery(true);
                    $queryNextWage
                            ->select('guid')
                            ->from('#__hrm_wage')
                            ->where('title = ' . $nextWage . '');
                    $db->setQuery($queryNextWage);

                    $wageGuid = $db->loadResult();
                    
                    $this->updatePayroll($item['id'], $wageGuid, $item['timeupdate']);
                    
                } catch (Exception $exc) {
                    echo $exc;
                    return FALSE;
                }
            }
            return TRUE;
        }
    }

    public function updatePayroll($id, $wage_guid ,$time_update) {
        $db = JFactory::getDbo();
        $db->transactionStart();
        try {
            $query = $db->getQuery(true);
            $query
                    ->update('#__hrm_payroll')
                    ->set('wage_guid = ' . $db->quote($db->escape($wage_guid)))
                    ->set('last_update = ' . $db->quote($db->escape($time_update)))
                    ->where('id = ' . $id);
            $db->setQuery($query);

            $result = $db->execute();

            $db->transactionCommit();
            
            return $result;
        } catch (Exception $exc) {
            echo $exc;
            $db->transactionRollback();
            return FALSE;
        }
    }

}
