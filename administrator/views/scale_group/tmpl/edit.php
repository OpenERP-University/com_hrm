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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_hrm/assets/css/hrm.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {

        js('input:hidden.scale_group_guid').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('scale_group_guidhidden')) {
                js('#jform_scale_group_guid option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_scale_group_guid").trigger("liszt:updated");
    });

    Joomla.submitbutton = function (task)
    {
        if (task == 'scale_group.cancel') {
            Joomla.submitform(task, document.getElementById('scale_group-form'));
        }
        else {

            if (task != 'scale_group.cancel' && document.formvalidator.isValid(document.id('scale_group-form'))) {

                Joomla.submitform(task, document.getElementById('scale_group-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_hrm&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="scale_group-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_HRM_TITLE_SCALE_GROUP', true)); ?>
        <div class="row-fluid">
            <div class="span4 offset2 form-horizontal">
                <fieldset class="adminform">

                    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
                    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
                    <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
                    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
                    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

                    <?php if (empty($this->item->created_by)) { ?>
                        <input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

                    <?php } else {
                        ?>
                        <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

                    <?php } ?>				<input type="hidden" name="jform[guid]" value="<?php echo $this->item->guid; ?>" />
                    <div class="control-group">
                        <div ><?php echo $this->form->getLabel('title'); ?></div>
                        <div ><?php echo $this->form->getInput('title'); ?></div>
                    </div>
                    <div class="control-group">
                        <div ><?php echo $this->form->getLabel('scale_group_guid'); ?></div>
                        <div ><?php echo $this->form->getInput('scale_group_guid'); ?></div>
                    </div>

                    <?php
                    foreach ((array) $this->item->scale_group_guid as $value):
                        if (!is_array($value)):
                            echo '<input type="hidden" class="scale_group_guid" name="jform[scale_group_guidhidden][' . $value . ']" value="' . $value . '" />';
                        endif;
                    endforeach;
                    ?>			
                    <div class="control-group">
                        <div ><?php echo $this->form->getLabel('wage_max'); ?></div>
                        <div ><?php echo $this->form->getInput('wage_max'); ?></div>
                    </div>


                </fieldset>
            </div>
            <div class="span4  form-horizontal">            
                <div class="control-group">
                    <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                </div>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php if (JFactory::getUser()->authorise('core.admin', 'hrm')) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
            <?php echo $this->form->getInput('rules'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>