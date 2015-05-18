<?php
/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Nghia <dinhtrongnghia92@gmail.com> - http://www.facebook.com/G55.RaFiKi
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
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_GUID'); ?></th>
			<td><?php echo $this->item->guid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_EMPLOYEE_GUID'); ?></th>
			<td><?php echo $this->item->employee_guid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_OPTION'); ?></th>
			<td><?php echo $this->item->option; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_INFORMATION'); ?></th>
			<td><?php echo $this->item->information; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_DATE'); ?></th>
			<td><?php echo $this->item->date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_HISTORYITSELF_TIME_TO_UPDATE'); ?></th>
			<td><?php echo $this->item->time_to_update; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=historyitself.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_HRM_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_hrm.historyitself.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=historyitself.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_HRM_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_HRM_ITEM_NOT_LOADED');
endif;
?>
