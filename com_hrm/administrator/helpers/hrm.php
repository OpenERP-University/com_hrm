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

/**
 * Hrm helper.
 */
class HrmHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {

        //Cán Bộ
        JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_EMPLOYEES'),
			'index.php?option=com_hrm&view=employees',
			$vName == 'employees'
		);
		// chuc vu
		JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_POSITIONS'),
			'index.php?option=com_hrm&view=positions',
			$vName == 'positions'
		);
    	//Đơn Vị
        		JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_DEPARTMENTS'),
			'index.php?option=com_hrm&view=departments',
			$vName == 'departments'
		);
        		//loai chuc vu

        		JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_POSITIONSTYPES'),
			'index.php?option=com_hrm&view=positionstypes',
			$vName == 'positionstypes'
		);
        
		
			//khen thưởng - kỷ luật	
				JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_REWARDDISCIPLINES'),
			'index.php?option=com_hrm&view=rewarddisciplines',
			$vName == 'rewarddisciplines'
		);
		
		//lịch sử bản thân
				JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_HISTORYITSELFS'),
			'index.php?option=com_hrm&view=historyitselfs',
			$vName == 'historyitselfs'
		);
		
		//quá trình lương
        		JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_PAYROLLS'),
			'index.php?option=com_hrm&view=payrolls',
			$vName == 'payrolls'
		);
		
		// bậc lương
#				JHtmlSidebar::addEntry(
#			JText::_('COM_HRM_TITLE_WAGES'),
#			'index.php?option=com_hrm&view=wages',
#			$vName == 'wages'
#		);

		//hệ số lương
				JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_COEFFICIENTS'),
			'index.php?option=com_hrm&view=coefficients',
			$vName == 'coefficients'
		);
		
		//loại ngạch
				JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_SCALE_TYPES'),
			'index.php?option=com_hrm&view=scale_types',
			$vName == 'scale_types'
		);
		
		//nhóm ngạch
				JHtmlSidebar::addEntry(
			JText::_('COM_HRM_TITLE_SCALE_GROUPS'),
			'index.php?option=com_hrm&view=scale_groups',
			$vName == 'scale_groups'
		);

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_hrm';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
