<?php
/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
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
			<th><?php echo JText::_('COM_HRM_FORM_LBL_POSITIONSTYPE_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_POSITIONSTYPE_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_POSITIONSTYPE_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_POSITIONSTYPE_GUID'); ?></th>
			<td><?php echo $this->item->guid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_POSITIONSTYPE_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_POSITIONSTYPE_ALLOWANCES'); ?></th>
			<td><?php echo $this->item->allowances; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_HRM_FORM_LBL_POSITIONSTYPE_DESCRIPTIONS'); ?></th>
			<td><?php echo $this->item->descriptions; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=positionstype.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_HRM_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_hrm.positionstype.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=positionstype.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_HRM_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_HRM_ITEM_NOT_LOADED');
endif;
?>
