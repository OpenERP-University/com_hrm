<?php
/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Hoang <hoangdau17592@gmail.com> - https://www.facebook.com/hoangdau92
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
//var_dump($user);die();
$canCreate = $user->authorise('core.create', 'com_hrm');
$canEdit = $user->authorise('core.edit', 'com_hrm');
$canCheckin = $user->authorise('core.manage', 'com_hrm');
$canChange = $user->authorise('core.edit.state', 'com_hrm');
$canDelete = $user->authorise('core.delete', 'com_hrm');
?>

<form action="<?php echo JRoute::_('index.php?option=com_hrm&view=employees'); ?>" method="post" name="adminForm" id="adminForm">

    <?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
    <table class="table table-striped" id = "employeeList" >
        <thead >
            <tr >
                <?php if (isset($this->items[0]->state)): ?>
                    <th width="1%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>

                <th class='left'>
                    <?php echo JHtml::_('grid.sort', 'COM_HRM_EMPLOYEES_FULLNAME', 'a.fullname', $listDirn, $listOrder); ?>
                </th>
                <th class='left'>
                    <?php echo JHtml::_('grid.sort', 'COM_HRM_EMPLOYEES_GENDER', 'a.gender', $listDirn, $listOrder); ?>
                </th>
                <th class='left'>
                    <?php echo JHtml::_('grid.sort', 'COM_HRM_EMPLOYEES_HOMETOWN', 'a.hometown', $listDirn, $listOrder); ?>
                </th>
                <th class='left'>
                    <?php echo JHtml::_('grid.sort', 'COM_HRM_EMPLOYEES_DEPARTMENT_GUID', 'a.department_guid', $listDirn, $listOrder); ?>
                </th>
                <th class='left'>
                    <?php echo JHtml::_('grid.sort', 'COM_HRM_EMPLOYEES_CURRENT_RESIDENCE', 'a.current_residence', $listDirn, $listOrder); ?>
                </th>


                <?php if (isset($this->items[0]->id)): ?>
                    <th width="1%" class="nowrap center hidden-phone">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>

                <?php if ($canEdit || $canDelete): ?>
                    <th class="center">
                        <?php echo JText::_('COM_HRM_EMPLOYEES_ACTIONS'); ?>
                    </th>
                <?php endif; ?>

            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
                <?php $canEdit = $user->authorise('core.edit', 'com_hrm'); ?>

                <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_hrm')): ?>
                    <?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
                <?php endif; ?>

                <tr class="row<?php echo $i % 2; ?>">

                    <?php if (isset($this->items[0]->state)): ?>
                        <?php $class = ($canEdit || $canChange) ? 'active' : 'disabled'; ?>
                        <td class="center">
                            <a class="btn btn-micro <?php echo $class; ?>"
                               href="<?php echo ($canEdit || $canChange) ? JRoute::_('index.php?option=com_hrm&task=employee.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
                                   <?php if ($item->state == 1): ?>
                                    <i class="icon-publish"></i>
                                <?php else: ?>
                                    <i class="icon-unpublish"></i>
                                <?php endif; ?>
                            </a>
                        </td>
                    <?php endif; ?>

                    <td>

                        <?php echo $item->fullname; ?>
                    </td>
                    <td>

                        <?php echo $item->gender; ?>
                    </td>
                    <td>

                        <?php echo $item->hometown; ?>
                    </td>
                    <td>

                        <?php echo $item->department_guid; ?>
                    </td>
                    <td>

                        <?php echo $item->current_residence; ?>
                    </td>


                    <?php if (isset($this->items[0]->id)): ?>
                        <td class="center hidden-phone">
                            <?php echo (int) $item->id; ?>
                        </td>
                    <?php endif; ?>

                    <?php if ($canEdit || $canDelete): ?>
                        <td >
                            <?php if ($canEdit): ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_hrm&task=employeeform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
                                <!--them nut - chuyen link va chuyen ngach-->
                                <a href="<?php echo JRoute::_('index.php?option=com_hrm&task=employeeform.link2payroll&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-new"  title="<?php echo JText::_('COM_HRM_TITLE_PAYROLLS'); ?>"></i></a>
                                <?php if ($item->checkpayroll == TRUE) { ?>        <a href="<?php echo JRoute::_('index.php?option=com_hrm&task=employeeform.newPayroll&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-plus-circle"  title="<?php echo 'Chuyển nghạch'; ?>"></i></a><?php } ?>
                            <?php endif; ?>
                            <?php if ($canDelete): ?>
                                <button data-item-id="<?php echo $item->id; ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></button>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($canCreate): ?>
        <a href="<?php echo JRoute::_('index.php?option=com_hrm&task=employeeform.edit&id=0', false, 2); ?>"
           class="btn btn-success btn-small"><i
                class="icon-plus"></i> <?php echo JText::_('COM_HRM_ADD_ITEM'); ?></a>
        <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
    });

    function deleteItem() {
        var item_id = jQuery(this).attr('data-item-id');
        if (confirm("<?php echo JText::_('COM_HRM_DELETE_MESSAGE'); ?>")) {
            window.location.href = '<?php echo JRoute::_('index.php?option=com_hrm&task=employeeform.remove&id=', false, 2) ?>' + item_id;
        }
    }
    
</script>


