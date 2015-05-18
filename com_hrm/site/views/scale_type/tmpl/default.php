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
			<th><?php echo JText::_('COM_HRM_FORM_LBL_SCALE_TYPE_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_SCALE_TYPE_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_SCALE_TYPE_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_SCALE_TYPE_GUID'); ?></th>
			<td><?php echo $this->item->guid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_SCALE_TYPE_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_SCALE_TYPE_CODE'); ?></th>
			<td><?php echo $this->item->code; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_SCALE_TYPE_SCALE_GROUP_GUID'); ?></th>
			<td><?php echo $this->item->scale_group_guid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_SCALE_TYPE_TIME_UPDATE'); ?></th>
			<td><?php echo $this->item->time_update; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=scale_type.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_HRM_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_hrm.scale_type.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=scale_type.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_HRM_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_HRM_ITEM_NOT_LOADED');
endif;
?>
