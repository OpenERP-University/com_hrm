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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_hrm', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addStyleSheet('components/com_hrm/assets/css/hrm.css');
$doc->addScript(JUri::base() . '/components/com_hrm/assets/js/form.js');
?>
</style>
<script type="text/javascript">
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function () {
        jQuery(document).ready(function () {
            jQuery('#form-employee').submit(function (event) {

            });


            jQuery('input:hidden.user_id').each(function () {
                var name = jQuery(this).attr('name');
                if (name.indexOf('user_idhidden')) {
                    jQuery('#jform_user_id option[value="' + jQuery(this).val() + '"]').attr('selected', true);
                }
            });
            jQuery("#jform_user_id").trigger("liszt:updated");
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

<div class="employee-edit front-end-edit">
    <div>
        <?php if (!empty($this->item->id)): ?>
            <h1>Edit <?php echo $this->item->id; ?></h1>
        <?php else: ?>
            <h1>Add</h1>
        <?php endif; ?>
    </div>

    <form id="form-employee" action="<?php echo JRoute::_('index.php?option=com_hrm&task=employee.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

        <div class="control-group">
            <div >
                <button type="submit" class="validate btn btn-primary" ><?php echo JText::_('JSUBMIT'); ?></button>
                <?php
                if ($this->params->get('save_history', 0)) :
                    echo $this->form->getInput('contenthistory');
                endif;
                ?>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_hrm&task=employeeform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>


        <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

        <input type="hidden" name="jform[guid]" value="<?php echo $this->item->guid; ?>" />

        <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

        <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

        <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

        <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

        <?php if (empty($this->item->created_by)): ?>
            <input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
        <?php else: ?>
            <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
        <?php endif; ?>

        <!--tao tab-->
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_HRM_TITLE_TAB_PERSONAL_INFORMATION_EMPLOYEE', true)); ?>

        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('firstname'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('firstname'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('lastname'); ?></div>
            <div class=" controls"><?php echo $this->form->getInput('lastname'); ?></div>
        </div>
        <input type="hidden" name="jform[fullname]" value="<?php echo $this->item->fullname; ?>" />

        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('other_name'); ?></div>
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
        <?php foreach ((array) $this->item->department_guid as $value): ?>
            <?php if (!is_array($value)): ?>
                <input type="hidden" class="department_guid" name="jform[department_guidhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
            <?php endif; ?>
        <?php endforeach; ?>
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

        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!--kết thúc-->


        <!-- them tab --Trình độ học vấn-- ><-->
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', '1', JText::_('COM_HRM_TITLE_TAB_EDUCATION_BACKGROUND_EMPLOYEE', true)); ?>
        <div> 
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
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- het  tab><-->

        <!-- them tab --Công việc-- ><-->
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', '2', JText::_('COM_HRM_TITLE_TAB_JOB_EMPLOYEE', true)); ?>
        <div> 
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
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- het  tab><-->
        <!-- them tab -- Tổ chức và hoạt động-- ><-->
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', '3', JText::_('COM_HRM_TITLE_TAB_INCORPORATION_ACTIVITIES_EMPLOYEE', true)); ?>
        <div> 

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
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- het  tab><-->
        <!-- them tab --Nguoi dung:-- ><-->
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', '4', JText::_('COM_HRM_TITLE_TAB_USER_EMPLOYEE', true)); ?>
        <div class="row-fluid" >
        <div class="span4 offset2"> 

            <?php if ($this->item->user_id) { ?>
                <div class="control-group">
                    <label><?php echo $this->form->getLabel('user_id'); ?></label>
                    <input  type="text" value="<?php echo $this->item->username; ?>" disabled>

                </div>
                <div class="control-group">
                    <label ><?php echo JText::_('JGLOBAL_EMAIL'); ?></label>
                    <input  type="text" value="<?php echo $this->item->email; ?>" disabled>

                </div>
            <?php } ?>
            <?php foreach ((array) $this->item->user_id as $value): ?>
                <?php if (!is_array($value)): ?>
                    <input type="hidden" class="user_id" name="jform[user_idhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="control-group">
                <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
            </div>      

        </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <!-- het  tab><-->

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
                    document.getElementById("form-employee").appendChild(input);
                });
            </script>
        <?php endif; ?>


        <input type="hidden" name="option" value="com_hrm" />
        <input type="hidden" name="task" value="employeeform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
