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
            jQuery('#form-payroll').submit(function (event) {

            });


            jQuery('input:hidden.employee_guid').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('employee_guidhidden')) {
                    jQuery('#jform_employee_guid option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_employee_guid").trigger("liszt:updated");
            jQuery('input:hidden.scale_group_guid').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('scale_group_guidhidden')) {
                    jQuery('#jform_scale_group_guid option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_scale_group_guid").trigger("liszt:updated");
            jQuery('input:hidden.scale_type_guid').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('scale_type_guidhidden')) {
                    jQuery('#jform_scale_type_guid option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_scale_type_guid").trigger("liszt:updated");
            jQuery('input:hidden.wage_guid').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('wage_guidhidden')) {
                    jQuery('#jform_wage_guid option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_wage_guid").trigger("liszt:updated");
        });
    });

    js = jQuery.noConflict();
    //Ham xu ly
    js(document).ready(function () {
        js("#jform_scale_type_guid_chzn").remove();
        js("#jform_wage_guid_chzn").remove();

        function ajax(scale_group_guid) {
            js.ajax({
                type: "POST",
                url: "index.php?option=com_hrm&task=filter_scale",
                data: scale_group_guid,
                datatype: "json",
                success: function (results) {
                    js("#scale_type_guid").css("display", "block");
                    js("#wage_guid").css("display", "block");
                    var parsed = js.parseJSON(results);
                    if (results) {

                        if (parsed.scale_type && parsed.list_wage) {
                            js("#jform_scale_type_guid").append(js('<option>', {
                                value: "",
                                text: "<?php echo JText::_('COM_HRM_PAYROLLS_SCALE_TYPE_GUID_FILTER'); ?>"
                            }));
                            js.each(parsed.scale_type, function (k, v) {
                                js("#jform_scale_type_guid").append(js('<option>', {
                                    value: v.guid,
                                    text: v.title
                                }));
                                js("#jform_scale_type_guid").css("display", "block");
                            });

                            js("#jform_wage_guid").append(js('<option>', {
                                value: "",
                                text: "<?php echo JText::_('COM_HRM_PAYROLLS_WAGE_GUID'); ?>"
                            }));
                            js.each(parsed.list_wage, function (k, v) {
                                js("#jform_wage_guid").append(js('<option>', {
                                    value: v.guid,
                                    text: v.title
                                }));
                                js("#jform_wage_guid").css("display", "block");
                            });

<?php
if ($this->item->scale_type_guid) {
    ?>
                                var scale_type = "<?php echo $this->item->scale_type_guid; ?>";
                                js('#jform_scale_type_guid option[value="' + scale_type + '"]').attr('selected', true);
    <?php
}
if ($this->item->wage_guid) {
    ?>
                                var wage = "<?php echo $this->item->wage_guid; ?>";
                                js('#jform_wage_guid option[value="' + wage + '"]').attr('selected', true);
    <?php
}
?>

                        } else {
                            js("#jform_scale_type_guid option").remove();
                            js("#jform_wage_guid option").remove();
                            js("#scale_type_guid").css("display", "none");
                            js("#wage_guid").css("display", "none");
                        }
                    } else {

                    }
                }
            });
        }

        js("#jform_scale_group_guid").on("change", function () {
            var scale_group_guid = "scale_group_guid=" + this.value;
            js("#jform_scale_type_guid option").remove();
            js("#jform_wage_guid option").remove();
            ajax(scale_group_guid);
        });


        js("#jform_scale_group_guid").change(function () {

        }).trigger("change");
    });

    //ket thuc su ly
    //submit và chuy?n link
    jQuery(document).ready(function () {
        jQuery('#employee').click(submitandlink);
    });
    function submitandlink() {

        document.getElementById('form-payroll').submit();
        return true;
    }//kt

</script>

<div class="payroll-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-payroll" action="<?php echo JRoute::_('index.php?option=com_hrm&task=payroll.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary" name="1"><?php echo JText::_('JSUBMIT'); ?></button>
                <button type="submit" class="btn" name="2"><?php echo JText::_('JSUBMIT') . ' -> ' . JText::_('COM_HRM_TITLE_EMPLOYEE', TRUE); ?></button>
                <?php
                if ($this->params->get('save_history', 0)) :
                    echo $this->form->getInput('contenthistory');
                endif;
                ?>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=payrollform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        <!--mở tab-->
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_HRM_TITLE_PAYROLL', true)); ?>
        <div class="row-fluid">
            <div class="span8 form-horizontal">
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
                    <div class="control-label"><?php echo $this->form->getLabel('employee_guid'); ?></div>
                    <div class="controls"><?php echo $this->item->employeeName; ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('scale_group_guid'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('scale_group_guid'); ?></div>
                </div>

                <?php
                foreach ((array) $this->item->scale_group_guid as $value):
                    if (!is_array($value)):
                        echo '<input type="hidden" class="scale_group_guid" name="jform[scale_group_guidhidden][' . $value . ']" value="' . $value . '" />';
                    endif;
                endforeach;
                ?>			<div id="scale_type_guid" class="control-group" style="display:none">
                    <div class="control-label"><?php echo $this->form->getLabel('scale_type_guid'); ?></div>

                    <div class="controls">
                        <!-- controls new select-->
                        <select id="jform_scale_type_guid" name="jform[scale_type_guid]" class="select-controls">
                        </select>
                    </div>
                </div>

                <?php
                foreach ((array) $this->item->scale_type_guid as $value):
                    if (!is_array($value)):
                        echo '<input type="hidden" class="scale_type_guid" name="jform[scale_type_guidhidden][' . $value . ']" value="' . $value . '" />';
                    endif;
                endforeach;
                ?>			<div id="wage_guid" class="control-group" style="display:none">
                    <div class="control-label"><?php echo $this->form->getLabel('wage_guid'); ?></div>
                    <div class="controls">

                        <!-- controls new select-->
                        <select id="jform_wage_guid" name="jform[wage_guid]" class="select-controls">
                        </select>
                    </div>
                </div>

                <?php
                foreach ((array) $this->item->wage_guid as $value):
                    if (!is_array($value)):
                        echo '<input type="hidden" class="wage_guid" name="jform[wage_guidhidden][' . $value . ']" value="' . $value . '" />';
                    endif;
                endforeach;
                ?>	
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('employee_type'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('employee_type'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('start_time'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('start_time'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('end_time'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('end_time'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('time_to_update'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('time_to_update'); ?></div>
                </div>
            </div>
            <div class="span4  form-horizontal">            
                <div class="control-group">
                    <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                </div>
            </div>
        </div>
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
                    document.getElementById("form-payroll").appendChild(input);
                });
            </script>
        <?php endif; ?>


        <input type="hidden" name="option" value="com_hrm" />
        <input type="hidden" name="task" value="payrollform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
