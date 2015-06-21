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

jimport('joomla.application.component.controllerform');

/**
 * Employee controller class.
 */
class HrmControllerEmployee extends JControllerForm
{

    function __construct() {
        $this->view_list = 'employees';
        parent::__construct();
    }

    public function link2payroll(){
        $input = JFactory::getApplication()->input;
        
        $form = $input->post->getArray();
        
        $employee_guid = $form['jform']['guid'];
        
        $modelPayroll = $this->getModel('payroll');
        
        $id = $modelPayroll->getCurrentID($employee_guid);
        
        $this->setRedirect(JRoute::_('index.php?option=com_hrm&view=payroll&layout=edit&id='.$id, false));
    }

    public function save2payroll() {
        if($this->save()){

        $modelPayroll = $this->getModel('payroll');

        $guid = $modelPayroll->getLastGuid($guid);

        $id = $modelPayroll->getCurrentID($guid);

        $this->setRedirect(JRoute::_('index.php?option=com_hrm&view=payroll&layout=edit&id=' . $id, false));
        }
    }

    public function GUID() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');
        else
            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
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

    public function newPayroll() {
        $modelPayroll = $this->getModel('payroll');
        
        $input = JFactory::getApplication()->input;

        $form = $input->post->getArray();

        $employee_guid = $form['jform']['guid'];
        
        $modelPayroll->updateState($employee_guid);
        
        $this->Insert($employee_guid);

        $id = $modelPayroll->getCurrentID($employee_guid);

        $this->setRedirect(JRoute::_('index.php?option=com_hrm&view=payroll&layout=edit&id=' . $id, false));  
    }

}
