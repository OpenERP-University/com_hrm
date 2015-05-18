<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Nghia <dinhtrongnghia92@gmail.com> - http://www.facebook.com/G55.RaFiKi
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Hrm.
 */
class HrmViewRewarddisciplines extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        HrmHelper::addSubmenu('rewarddisciplines');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/hrm.php';

        $state = $this->get('State');
        $canDo = HrmHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_HRM_TITLE_REWARDDISCIPLINES'), 'rewarddisciplines.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/rewarddiscipline';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('rewarddiscipline.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('rewarddiscipline.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('rewarddisciplines.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('rewarddisciplines.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'rewarddisciplines.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('rewarddisciplines.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('rewarddisciplines.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'rewarddisciplines.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('rewarddisciplines.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_hrm');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_hrm&view=rewarddisciplines');

        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
                                                
        //Filter for the field employee_guid;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_hrm.rewarddiscipline', 'rewarddiscipline');

        $field = $form->getField('employee_guid');

        $query = $form->getFieldAttribute('filter_employee_guid','query');
        $translate = $form->getFieldAttribute('filter_employee_guid','translate');
        $key = $form->getFieldAttribute('filter_employee_guid','key_field');
        $value = $form->getFieldAttribute('filter_employee_guid','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            '$Tên cán bộ',
            'filter_employee_guid',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.employee_guid')),
            true
        );                                                
        //Filter for the field department_guid;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_hrm.rewarddiscipline', 'rewarddiscipline');

        $field = $form->getField('department_guid');

        $query = $form->getFieldAttribute('filter_department_guid','query');
        $translate = $form->getFieldAttribute('filter_department_guid','translate');
        $key = $form->getFieldAttribute('filter_department_guid','key_field');
        $value = $form->getFieldAttribute('filter_department_guid','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            '$Cấp quyết định',
            'filter_department_guid',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.department_guid')),
            true
        );
		//Filter for the field option
		$select_label = JText::sprintf('COM_HRM_FILTER_SELECT_LABEL', 'Hình thức');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "1";
		$options[0]->text = "Khen thưởng";
		$options[1] = new stdClass();
		$options[1]->value = "2";
		$options[1]->text = "Kỷ luật";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_option',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.option'), true)
		);

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.employee_guid' => JText::_('COM_HRM_REWARDDISCIPLINES_EMPLOYEE_GUID'),
		'a.department_guid' => JText::_('COM_HRM_REWARDDISCIPLINES_DEPARTMENT_GUID'),
		'a.option' => JText::_('COM_HRM_REWARDDISCIPLINES_OPTION'),
		'a.note' => JText::_('COM_HRM_REWARDDISCIPLINES_NOTE'),
		'a.date' => JText::_('COM_HRM_REWARDDISCIPLINES_DATE'),
		);
	}

}
