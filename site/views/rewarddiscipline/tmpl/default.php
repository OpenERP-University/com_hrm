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
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_GUID'); ?></th>
			<td><?php echo $this->item->guid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_EMPLOYEE_GUID'); ?></th>
			<td><?php echo $this->item->employee_guid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_DEPARTMENT_GUID'); ?></th>
			<td><?php echo $this->item->department_guid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_OPTION'); ?></th>
			<td><?php echo $this->item->option; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_NOTE'); ?></th>
			<td><?php echo $this->item->note; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_DATE'); ?></th>
			<td><?php echo $this->item->date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_REWARDDISCIPLINE_TIME_TO_UPDATE'); ?></th>
			<td><?php echo $this->item->time_to_update; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=rewarddiscipline.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_HRM_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_hrm.rewarddiscipline.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=rewarddiscipline.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_HRM_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_HRM_ITEM_NOT_LOADED');
endif;
?>
