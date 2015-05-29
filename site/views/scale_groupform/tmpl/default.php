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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_hrm', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_hrm/assets/js/form.js');
?>
</style>
<script type="text/javascript">
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function () {
        jQuery(document).ready(function () {
            jQuery('#form-scale_group').submit(function (event) {

            });


            jQuery('input:hidden.scale_group_guid').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('scale_group_guidhidden')) {
                    jQuery('#jform_scale_group_guid option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_scale_group_guid").trigger("liszt:updated");
        });
    });

</script>

<div class="scale_group-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-scale_group" action="<?php echo JRoute::_('index.php?option=com_hrm&task=scale_group.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

        <div class="control-group">
            <div >
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                <?php
                if ($this->params->get('save_history', 0)) :
                    echo $this->form->getInput('contenthistory');
                endif;
                ?>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=departmentform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        <!--mở tab-->
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general',JText::_('COM_HRM_TITLE_SCALE_GROUP', true)); ?>
        <div class="row-fluid">
            <div class="span6 offset1 form-horizontal">
                <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

                <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

                <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

                <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

                <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

                <?php if (empty($this->item->created_by)): ?>
                    <input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
                <?php else: ?>
                    <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
                <?php endif; ?>
                <input type="hidden" name="jform[guid]" value="<?php echo $this->item->guid; ?>" />

                <div class="control-group">
                    <div><?php echo $this->form->getLabel('title'); ?></div>
                    <div ><?php echo $this->form->getInput('title'); ?></div>
                </div>
                <div class="control-group">
                    <div ><?php echo $this->form->getLabel('scale_group_guid'); ?></div>
                    <div ><?php echo $this->form->getInput('scale_group_guid'); ?></div>
                </div>
                <?php foreach ((array) $this->item->scale_group_guid as $value): ?>
                    <?php if (!is_array($value)): ?>
                        <input type="hidden" class="scale_group_guid" name="jform[scale_group_guidhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="control-group">
                    <div ><?php echo $this->form->getLabel('wage_max'); ?></div>
                    <div ><?php echo $this->form->getInput('wage_max'); ?></div>
                </div>
            </div>
            <div class="span4  form-horizontal">            
                <div class="control-group">
                    <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                </div>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?><!--đóng tab-->
        <div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin', 'hrm')): ?> style="display:none;" <?php endif; ?> >
            <?php echo JHtml::_('sliders.start', 'permissions-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
            <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
            <fieldset class="panelform">
                <?php echo $this->form->getLabel('rules'); ?>
                <?php echo $this->form->getInput('rules'); ?>
            </fieldset>
            <?php echo JHtml::_('sliders.end'); ?>
        </div>
        <?php if (!JFactory::getUser()->authorise('core.admin', 'hrm')): ?>
            <script type="text/javascript">
                jQuery.noConflict();
                jQuery('.tab-pane select').each(function () {
                    var option_selected = jQuery(this).find(':selected');
                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");
                    input.setAttribute("name", jQuery(this).attr('name'));
                    input.setAttribute("value", option_selected.val());
                    document.getElementById("form-scale_group").appendChild(input);
                });
            </script>
        <?php endif; ?>


        <input type="hidden" name="option" value="com_hrm" />
        <input type="hidden" name="task" value="scale_groupform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
