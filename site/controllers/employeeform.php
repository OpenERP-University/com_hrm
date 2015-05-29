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

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Employee controller class.
 */
class HrmControllerEmployeeForm extends HrmController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_hrm.edit.employee.id');
        $editId = $app->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_hrm.edit.employee.id', $editId);

        // Get the model.
        $model = $this->getModel('EmployeeForm', 'HrmModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_hrm&view=employeeform&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('EmployeeForm', 'HrmModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $input = $app->input;
            $jform = $input->get('jform', array(), 'ARRAY');

            // Save the data in the session.
            $app->setUserState('com_hrm.edit.employee.data', $jform, array());

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_hrm.edit.employee.id');
            $this->setRedirect(JRoute::_('index.php?option=com_hrm&view=employeeform&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_hrm.edit.employee.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_hrm.edit.employee.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_hrm&view=employeeform&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_hrm.edit.employee.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_HRM_ITEM_SAVED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_hrm&view=employees' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_hrm.edit.employee.data', null);
    }

    function cancel() {

        $app = JFactory::getApplication();

        // Get the current edit id.
        $editId = (int) $app->getUserState('com_hrm.edit.employee.id');

        // Get the model.
        $model = $this->getModel('EmployeeForm', 'HrmModel');

        // Check in the item
        if ($editId) {
            $model->checkin($editId);
        }

        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_hrm&view=employees' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
    }

    public function remove() {

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('EmployeeForm', 'HrmModel');

        // Get the user data.
        $data = array();
        $data['id'] = $app->input->getInt('id');

        // Check for errors.
        if (empty($data['id'])) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_hrm.edit.employee.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_hrm.edit.employee.id');
            $this->setRedirect(JRoute::_('index.php?option=com_hrm&view=employee&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_hrm.edit.employee.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_hrm.edit.employee.id');
            $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_hrm&view=employee&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_hrm.edit.employee.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_HRM_ITEM_DELETED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_hrm&view=employees' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_hrm.edit.employee.data', null);
    }

    // nút chuyển link
    public function link2payroll() {

        $app = JFactory::getApplication();
        
        //
        $editId = $app->input->getInt('id', null, 'array');
        $model = $this->getModel('employee');
        $guid_employee = $model->getGuid_employee($editId);

        // Get the model.

        $model = $this->getModel('payroll');
        //
        $id_payroll = $model->getCurrentID($guid_employee);
        $this->setRedirect(JRoute::_('index.php?option=com_hrm&task=payrollform.edit&id='. $id_payroll , false));
    }

    /**
     * Tạo mới quá trình lương - tức là chuyển ngạch
     */
    public function newPayroll() {
        $app = JFactory::getApplication();
        // Get the current edit id.
        $id = $app->input->getInt('id', null, 'array');
        // Get the model.
        $model = $this->getModel('employee');
        $employee_guid = $model->getGuid_employee($id);
        $modelPayroll = $this->getModel('payroll');
        $modelPayroll->updateState($employee_guid); //gọi dến hàm update trạng thái trong model - tức là khi chuyển ngạch thì ngạch trước sẽ đc truyển sang dạng lưu trữ
        $this->Insert($employee_guid); //thêm mới quá trình lương ý như trong Bind của employee

        $id_payroll = $modelPayroll->getCurrentID($employee_guid); //lấy cái Id cuối cùng vừa mới thêm vào ở trong payroll - theo cán bộ

        $this->setRedirect(JRoute::_('index.php?option=com_hrm&task=payrollform.edit&id=' . $id_payroll, false));  //truyền vào link để chuyển
    }

    // them 1 ngạch lương mới
    public function Insert($employee_guid = NULL) {
        if ($employee_guid) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query = "INSERT INTO #__hrm_payroll(guid,employee_guid) VALUES (" . $db->quote($db->escape($this->checkExitsGuid())) . "," . $db->quote($db->escape($employee_guid)) . ")";

            $db->setQuery($query);

            $result = $db->execute();

            return $result;
        } else {
            return FALSE;
        }
    }

    public function checkExitsGuid($GUID = NULL) {
        if ($GUID) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                    ->select('count(' . $db->quoteName('guid') . ')')
                    ->from('`#__hrm_payroll`')
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

    public function GUID() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');
        else
            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

}
