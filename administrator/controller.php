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
// No direct access
defined('_JEXEC') or die;

class HrmController extends JControllerLegacy {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/hrm.php';

        $view = JFactory::getApplication()->input->getCmd('view', 'departments');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }

    /**
     * Ajax for filter scale
     */
    public function filter_scale() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $scale_guid = $input->get("scale_group_guid");

        $scale_type_model = $this->getModel("scale_type");

        $scale_type = $scale_type_model->getListScale($scale_guid);

        $scale_group_model = $this->getModel("scale_group");

        $wage_max = $scale_group_model->getWageMax($scale_guid);

        $wage_model = $this->getModel("wage");

        $list_ware = $wage_model->getListWare($wage_max);

        $data = array(
            "scale_type" => $scale_type,
            "wage_max" => $wage_max,
            "list_ware" => $list_ware
        );
        echo json_encode($data);

        JFactory::getApplication()->close();
    }

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->autoUpdateParroll();
    }

    public function autoUpdateParroll() {
        $state_auto = JComponentHelper::getParams('com_hrm')->get('state_auto');
        if ($state_auto) {
            $modelauto = $this->getModel('payrollauto');
            $timeUpdate = ($modelauto->getTime(JComponentHelper::getParams('com_hrm')->get('time_up')));
            if ($timeUpdate) {
                $modelPayroll = $this->getModel('payroll');
                $listUpdate = $modelPayroll->getPayrollsUpdate();
                $listNotify = $modelPayroll->getPayrollsUpdate(JComponentHelper::getParams('com_hrm')->get('time_notify'));

                if ($listNotify) {
                    HrmHelperMail::sendMailNotify($listNotify);
                    $modelauto->updateDate();
                } else {
                    $modelauto->updateDate();
                }
                if ($listUpdate) {
                    $update = $modelPayroll->updatePayrolls($listUpdate);
                    if ($update) {
                        $update = $modelPayroll->updatePayrolls($listUpdate);
                        if ($update) {
                            HrmHelperMail::sendMailUpdate($listUpdate);
                        }
                        $modelauto->updateDate();
                    }
                } else {
                    $modelauto->updateDate();
                }
            }
        }
    }

    public function getMail() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;
        $fullname = $input->post->getString('fullname');
        $fullname = urldecode($fullname);
        $employee = $this->getModel('employee');
        $table = $employee->getTable();
        $convert = $table->createNewEmail($table->convertVNese($fullname));

        $suffixes = JComponentHelper::getParams('com_hrm')->get('suffixes_email', 'humg.edu.vn');

        $email = $convert . '@' . $suffixes;

        echo $email;

        JFactory::getApplication()->close();
    }

}
