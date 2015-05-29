<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Hoang <hoangdau17592@gmail.com> - https://www.facebook.com/hoangdau92
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class HrmViewEmployee extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        if ($this->item->user_id) {
            $info = $this->getInfoUser($this->item->user_id);
            $this->item->username = $info['username'];
            $this->item->email = $info['email'];
            
        }
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user = JFactory::getUser();
        $isNew = ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
        $canDo = HrmHelper::getActions();

        JToolBarHelper::title(JText::_('COM_HRM_TITLE_EMPLOYEE'), 'employee.png');

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create')))) {

            JToolBarHelper::apply('employee.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('employee.save', 'JTOOLBAR_SAVE');
        }

        if (empty($this->item->id)) {
            JToolBarHelper::custom('employee.save2payroll', 'save.png', 'save_f2.png', 'Save -> Quá trình lương', false);
        }

        if (!$checkedOut && ($canDo->get('core.create'))) {
            JToolBarHelper::custom('employee.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
        }
        // If an existing item, can save to a copy.
        if (!$isNew && $canDo->get('core.create')) {
            JToolBarHelper::custom('employee.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
        }
        if (empty($this->item->id)) {
            JToolBarHelper::cancel('employee.cancel', 'JTOOLBAR_CANCEL');
        } else {
        	if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit')) {
                JToolbarHelper::versions('com_hrm.employee', $this->item->id);
            }
            JToolBarHelper::custom('employee.link2payroll', 'save-new.png', 'save-new_f2.png', JText::_('COM_HRM_TITLE_PAYROLLS'), false);
            JToolBarHelper::custom('employee.newPayroll', 'save-new.png', 'save-new_f2.png', 'Chuyển ngạch', false);
            JToolBarHelper::cancel('employee.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    protected function getInfoUser($id = NULL) {
        if ($id) {
            $info = JFactory::getUser($id);
            if ($info){
                
                return array(
                    "username" => $info->username,
                    "email"  => $info->email
                );
                
            }
            
        } else {
            return FALSE;
        }
    }

}
