<?php

/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class HrmController extends JControllerLegacy {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/hrm.php';

        $view = JFactory::getApplication()->input->getCmd('view', 'departments');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }

    /**
     * Ajax for filter scale
     */
    public function filter_scale() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $scale_guid = $input->get("scale_group_guid");

        $scale_type_model = $this->getModel("scale_type");

        $scale_type = $scale_type_model->getListScale($scale_guid);

        $scale_group_model = $this->getModel("scale_group");

        $wage_max = $scale_group_model->getWageMax($scale_guid);

        $wage_model = $this->getModel("wage");

        $list_wage = $wage_model->getListWage($wage_max);

        $data = array(
            "scale_type" => $scale_type,
            "wage_max" => $wage_max,
            "list_wage" => $list_wage
        );
        echo json_encode($data);

        JFactory::getApplication()->close();
    }

}
