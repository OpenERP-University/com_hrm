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
    js(document).ready(function() {
        
	js('input:hidden.employee_guid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('employee_guidhidden')){
			js('#jform_employee_guid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_employee_guid").trigger("liszt:updated");
	js('input:hidden.scale_group_guid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('scale_group_guidhidden')){
			js('#jform_scale_group_guid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_scale_group_guid").trigger("liszt:updated");
	js('input:hidden.scale_type_guid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('scale_type_guidhidden')){
			js('#jform_scale_type_guid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_scale_type_guid").trigger("liszt:updated");
	js('input:hidden.wage_guid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('wage_guidhidden')){
			js('#jform_wage_guid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_wage_guid").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'payroll.cancel') {
            Joomla.submitform(task, document.getElementById('payroll-form'));
        }
        else {
            
            if (task != 'payroll.cancel' && document.formvalidator.isValid(document.id('payroll-form'))) {
                
                Joomla.submitform(task, document.getElementById('payroll-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
	
	
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
                        if (parsed.scale_type && parsed.list_ware) {
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
                            js.each(parsed.list_ware, function (k, v) {
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
	
</script>

<form action="<?php echo JRoute::_('index.php?option=com_hrm&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="payroll-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_HRM_TITLE_PAYROLL', true)); ?>
        <div class="row-fluid">
            <div class="span6 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>				<input type="hidden" name="jform[guid]" value="<?php echo $this->item->guid; ?>" />
			
			 <div class="control-group">
                     <div class="control-label"><?php echo $this->form->getLabel('employee_guid'); ?></div>
                    <div class="controls"><?php echo $this->item->employeeName; ?></div>
              </div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('scale_group_guid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('scale_group_guid'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->scale_group_guid as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="scale_group_guid" name="jform[scale_group_guidhidden]['.$value.']" value="'.$value.'" />';
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
                    ?>		<div class="control-group">
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


                </fieldset>
            </div>
            <div class="span4  form-horizontal">            
                <div class="control-group">
                    <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                </div>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        <?php if (JFactory::getUser()->authorise('core.admin','hrm')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>
