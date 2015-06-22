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
    js(document).ready(function () {
        js('input:hidden.department_guid').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('department_guidhidden')) {
                js('#jform_department_guid option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_department_guid").trigger("liszt:updated");
        
        js('a.test').click(function () {
            var a_href = js(this).attr('href');
            js('ul#myTabTabs li').removeClass('active');
            js.each(js('ul#myTabTabs li'), function (k, v) {
                var now = js('a', this).attr('href');
                if (now == a_href) {
                    js(this).addClass('active');
                }
            });

        });
        
        js("#emaildefault").click(function () {
            var firstname = js("#jform_firstname").val();
            var lastname = js("#jform_lastname").val();

            if (!firstname || !lastname) {
                alert('<?php echo JText::_('COM_HRM_EMAIL_ALERT', true)?>');
            }
            else
            {
                var fullname = firstname + lastname;
                fullname = encodeURIComponent(fullname);
                var data = {
                    "fullname": fullname
                };

                js.ajax({
                    type: "POST",
                    url: "index.php?option=com_hrm&task=getMail",
                    data: data,
                    datatype: "json",
                    contentType: "application/x-www-form-urlencoded;charset=utf-8",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Accept-Charset", "utf-8");
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
                    },
                    success: function (email) {
                        js("#jform_email").val(email);

                    }
                });
            }

        });

<?php
if ($this->item->id):
    ?>
            js('#emaildefault').remove();
            js("#jform_email").val('<?php echo $this->item->email; ?>');
            js('#jform_email').attr('readonly','readonly');
    <?php
endif;
?>
    });

    Joomla.submitbutton = function (task)
    {
        if (task == 'employee.cancel') {
            Joomla.submitform(task, document.getElementById('employee-form'));
        }
        else {

            if (task != 'employee.cancel' && document.formvalidator.isValid(document.id('employee-form'))) {

                Joomla.submitform(task, document.getElementById('employee-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }


</script>

<form action="<?php echo JRoute::_('index.php?option=com_hrm&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="employee-form" class="form-validate">

    <div class="form-inline form-inline-header">

        <div class="control-label" ><?php echo $this->form->getLabel('firstname'); ?></div>
        <div  class="controls" style="margin-right: 50px"><?php echo $this->form->getInput('firstname'); ?></div>


        <div class="control-label"><?php echo $this->form->getLabel('lastname'); ?></div>
        <div  class="controls"  style="margin-right: 100px" ><?php echo $this->form->getInput('lastname'); ?></div>


        <div class="input-append input-prepend"> 
            <span class="add-on">
                <i class="icon-mail"></i>
            </span>
            <?php echo $this->form->getInput('email'); ?>
            <a id="emaildefault" class="btn width-auto hasTooltip" title data-original-title="<?php echo JText::_('COM_HRM_TOOLTITLE_EMAIL',true);?>">
                <i class="icon-edit"></i>
            </a>
        </div>

    </div>
    <div>     </div>
    <div class="form-horizontal" style=" padding-top:20px; ">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_HRM_TITLE_TAB_PERSONAL_INFORMATION_EMPLOYEE', true)); ?>

        <div class="row-fluid" >
            <div class="span4 offset2 form-horizontal">
                <fieldset class="adminform">

                    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
                    <input type="hidden" name="jform[guid]" value="<?php echo $this->item->guid; ?>" />
                    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
                    <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
                    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
                    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

                    <?php if (empty($this->item->created_by)) { ?>
                        <input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

                    <?php } else {
                        ?>
                        <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

                    <?php } ?>      

                    <div class="control-group">
                        <div class="control-label" ><?php echo $this->form->getLabel('other_name'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('other_name'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('date_of_birth'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('date_of_birth'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('gender'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('gender'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('place_of_birth'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('place_of_birth'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('hometown'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('hometown'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('ethnic'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('ethnic'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('religion'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('religion'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('address'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('current_residence'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('current_residence'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('identity_card_number'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('identity_card_number'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('date_of_issue'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('date_of_issue'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('condition_health'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('condition_health'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('height'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('height'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('weight'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('weight'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('blood_group'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('blood_group'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('level_of_war_invalids'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('level_of_war_invalids'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('family_policy'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('family_policy'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('phone_number'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('phone_number'); ?></div>
                    </div>

                </fieldset>
            </div>

        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- them tab --Trình độ học vấn-- ><-->
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', '1', JText::_('COM_HRM_TITLE_TAB_EDUCATION_BACKGROUND_EMPLOYEE', true)); ?>
        <div class="row-fluid" >
            <div class="span4 offset2 form-horizontal">
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('level_of_general_education'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('level_of_general_education'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('highest_qualification'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('highest_qualification'); ?></div>
                </div>    
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('political_theory'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('political_theory'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('state_management'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('state_management'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('language_employee'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('language_employee'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('informatic'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('informatic'); ?></div>
                </div>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- het  tab><-->

        <!-- them tab --Công việc-- ><-->
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', '2', JText::_('COM_HRM_TITLE_TAB_JOB_EMPLOYEE', true)); ?>
        <div class="row-fluid" >
            <div class="span4 offset2 form-horizontal">
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('profession'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('profession'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('date_of_recruitment'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('date_of_recruitment'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('main_job'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('main_job'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('department_guid'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('department_guid'); ?></div>
                </div>
                <?php
                foreach ((array) $this->item->department_guid as $value):
                    if (!is_array($value)):
                        echo '<input type="hidden" class="department_guid" name="jform[department_guidhidden][' . $value . ']" value="' . $value . '" />';
                    endif;
                endforeach;
                ?> 
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('forte_business'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('forte_business'); ?></div>
                </div>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- het  tab><-->
        <!-- them tab -- Tổ chức và hoạt động-- ><-->
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', '3', JText::_('COM_HRM_TITLE_TAB_INCORPORATION_ACTIVITIES_EMPLOYEE', true)); ?>
        <div class="row-fluid" >
            <div class="span4 offset2 form-horizontal"> 

                <div class="control-group">
                    <div class="control-label" ><?php echo $this->form->getLabel('date_of_enlistment'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('date_of_enlistment'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('demobilization_date'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('demobilization_date'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('highest_army_rank'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('highest_army_rank'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('day_at_party'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('day_at_party'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('date_official'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('date_official'); ?></div>
                </div>
                <div class="control-group">
                    <div class=""><?php echo $this->form->getLabel('highest_trophies_awarded'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('highest_trophies_awarded'); ?></div>
                </div>
                <div class="control-group">
                    <div  ><?php echo $this->form->getLabel('day_join_pos'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('day_join_pos'); ?></div>
                </div>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- het  tab><-->

        <!-- them tab --Nguoi dung:-- ><-->
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', '4', JText::_('COM_HRM_TITLE_TAB_USER_EMPLOYEE', true)); ?>
        <div class="row-fluid" >
            <div class="span4 offset2 form-horizontal">

                <?php if ($this->item->user_id) { ?>
                    <div class="control-group">
                        <label><?php echo $this->form->getLabel('user_id'); ?></label>
                        <input  type="text" value="<?php echo $this->item->username; ?>" disabled>

                    </div>

                <?php } ?>
            </div>
            <div class="control-group">
                <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- het  tab><-->
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
