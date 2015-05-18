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

jimport('joomla.application.component.controllerform');

/**
 * Department controller class.
 */
class HrmControllerDepartment extends JControllerForm
{

    function __construct() {
        $this->view_list = 'departments';
        parent::__construct();
    }

}