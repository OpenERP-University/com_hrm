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
// no direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_hrm.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_hrm' . $this->item->id)) {
    $canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_ID'); ?></th>
                <td><?php echo $this->item->id; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_GUID'); ?></th>
                <td><?php echo $this->item->guid; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_STATE'); ?></th>
                <td>
                    <i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_CREATED_BY'); ?></th>
                <td><?php echo $this->item->created_by_name; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_FIRSTNAME'); ?></th>
                <td><?php echo $this->item->firstname; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_LASTNAME'); ?></th>
                <td><?php echo $this->item->lastname; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_FULLNAME'); ?></th>
                <td><?php echo $this->item->fullname; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_USER_ID'); ?></th>
                <td><?php echo $this->item->user_id; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_OTHER_NAME'); ?></th>
                <td><?php echo $this->item->other_name; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DATE_OF_BIRTH'); ?></th>
                <td><?php echo $this->item->date_of_birth; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_GENDER'); ?></th>
                <td><?php echo $this->item->gender; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_PLACE_OF_BIRTH'); ?></th>
                <td><?php echo $this->item->place_of_birth; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_HOMETOWN'); ?></th>
                <td><?php echo $this->item->hometown; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DEPARTMENT_GUID'); ?></th>
                <td><?php echo $this->item->department_guid; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_ETHNIC'); ?></th>
                <td><?php echo $this->item->ethnic; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_RELIGION'); ?></th>
                <td><?php echo $this->item->religion; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_ADDRESS'); ?></th>
                <td><?php echo $this->item->address; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_CURRENT_RESIDENCE'); ?></th>
                <td><?php echo $this->item->current_residence; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_PROFESSION'); ?></th>
                <td><?php echo $this->item->profession; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DATE_OF_RECRUITMENT'); ?></th>
                <td><?php echo $this->item->date_of_recruitment; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_MAIN_JOB'); ?></th>
                <td><?php echo $this->item->main_job; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_LEVEL_OF_GENERAL_EDUCATION'); ?></th>
                <td><?php echo $this->item->level_of_general_education; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_HIGHEST_QUALIFICATION'); ?></th>
                <td><?php echo $this->item->highest_qualification; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_IDENTITY_CARD_NUMBER'); ?></th>
                <td><?php echo $this->item->identity_card_number; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DATE_OF_ISSUE'); ?></th>
                <td><?php echo $this->item->date_of_issue; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_POLITICAL_THEORY'); ?></th>
                <td><?php echo $this->item->political_theory; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_STATE_MANAGEMENT'); ?></th>
                <td><?php echo $this->item->state_management; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_LANGUAGE'); ?></th>
                <td><?php echo $this->item->language_employee; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_INFORMATIC'); ?></th>
                <td><?php echo $this->item->informatic; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DAY_AT_PARTY'); ?></th>
                <td><?php echo $this->item->day_at_party; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DATE_OFFICIAL'); ?></th>
                <td><?php echo $this->item->date_official; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DAY_JOIN_POS'); ?></th>
                <td><?php echo $this->item->day_join_pos; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DATE_OF_ENLISTMENT'); ?></th>
                <td><?php echo $this->item->date_of_enlistment; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_DEMOBILIZATION_DATE'); ?></th>
                <td><?php echo $this->item->demobilization_date; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_HIGHEST_ARMY_RANK'); ?></th>
                <td><?php echo $this->item->highest_army_rank; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_HIGHEST_TROPHIES_AWARDED'); ?></th>
                <td><?php echo $this->item->highest_trophies_awarded; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_FORTE_BUSINESS'); ?></th>
                <td><?php echo $this->item->forte_business; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_CONDITION_HEALTH'); ?></th>
                <td><?php echo $this->item->condition_health; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_HEIGHT'); ?></th>
                <td><?php echo $this->item->height; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_WEIGHT'); ?></th>
                <td><?php echo $this->item->weight; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_BLOOD_GROUP'); ?></th>
                <td><?php echo $this->item->blood_group; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_LEVEL_OF_WAR_INVALIDS'); ?></th>
                <td><?php echo $this->item->level_of_war_invalids; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_FAMILY_POLICY'); ?></th>
                <td><?php echo $this->item->family_policy; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_HRM_FORM_LBL_EMPLOYEE_PHONE_NUMBER'); ?></th>
                <td><?php echo $this->item->phone_number; ?></td>
            </tr>

        </table>
    </div>
    <?php if ($canEdit && $this->item->checked_out == 0): ?>
        <a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=employee.edit&id=' . $this->item->id); ?>"><?php echo JText::_("COM_HRM_EDIT_ITEM"); ?></a>
    <?php endif; ?>
    <?php if (JFactory::getUser()->authorise('core.delete', 'com_hrm.employee.' . $this->item->id)): ?>
        <a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=employee.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_HRM_DELETE_ITEM"); ?></a>
    <?php endif; ?>
    <?php
else:
    echo JText::_('COM_HRM_ITEM_NOT_LOADED');
endif;
?>
