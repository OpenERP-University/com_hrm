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
            jQuery('#form-position').submit(function (event) {

            });


            jQuery('input:hidden.employee_guid').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('employee_guidhidden')) {
                    jQuery('#jform_employee_guid option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_employee_guid").trigger("liszt:updated");
            jQuery('input:hidden.positiontype_guid').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('positiontype_guidhidden')) {
                    jQuery('#jform_positiontype_guid option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_positiontype_guid").trigger("liszt:updated");
            jQuery('input:hidden.department_guid').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('department_guidhidden')) {
                    jQuery('#jform_department_guid option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_department_guid").trigger("liszt:updated");
        });
    });

</script>

<div class="position-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-position" action="<?php echo JRoute::_('index.php?option=com_hrm&task=position.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

        <div class="control-group">

            <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
            <?php
            if ($this->params->get('save_history', 0)) :
                echo $this->form->getInput('contenthistory');
            endif;
            ?>
            <a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=positionform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
        </div>
        <!--mở tab-->
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_HRM_TITLE_POSITION', true)); ?>
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
                    <div class="controls"><?php echo $this->form->getInput('employee_guid'); ?></div>
                </div>
                <?php foreach ((array) $this->item->employee_guid as $value): ?>
                    <?php if (!is_array($value)): ?>
                        <input type="hidden" class="employee_guid" name="jform[employee_guidhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('positiontype_guid'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('positiontype_guid'); ?></div>
                </div>
                <?php foreach ((array) $this->item->positiontype_guid as $value): ?>
                    <?php if (!is_array($value)): ?>
                        <input type="hidden" class="positiontype_guid" name="jform[positiontype_guidhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('department_guid'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('department_guid'); ?></div>
                </div>
                <?php foreach ((array) $this->item->department_guid as $value): ?>
                    <?php if (!is_array($value)): ?>
                        <input type="hidden" class="department_guid" name="jform[department_guidhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('start'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('start'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('end'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('end'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('descriptions'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('descriptions'); ?></div>
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
                    document.getElementById("form-position").appendChild(input);
                });
            </script>
        <?php endif; ?>


        <input type="hidden" name="option" value="com_hrm" />
        <input type="hidden" name="task" value="positionform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
