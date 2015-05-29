<?php
/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */

defined('_JEXEC') or die;


// Include dependancies
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT . '/helpers/mail.php';

// Execute the task.
$controller	= JControllerLegacy::getInstance('Hrm');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
