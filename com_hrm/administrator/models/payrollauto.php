<?php
/**
 * @version     1.0.0
 * @package     com_hrm
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Hrm model.
 */
class HrmModelPayrollauto extends JModelAdmin {

    public function getForm($data = array(), $loadData = true) {
        
    }

    public function getTime($day = 0) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('DATE_ADD(`checked_out_time`,INTERVAL '.$day.' DAY)')
                ->from('#__hrm_payroll_autoup')
                ->where('DATE_ADD(`checked_out_time`,INTERVAL '.$day.' DAY) <= NOW()');
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    public function updateDate(){
        $db = JFactory::getDbo();
        $db->transactionStart();
        $date = JFactory::getDate()->toSql();
        try {
            $query = $db->getQuery(true);
            $query
                    ->update('#__hrm_payroll_autoup')
                    ->set('checked_out_time = ' . $db->quote($db->escape($date)))
                    ->where('id = 1');
            $db->setQuery($query);

            $result = $db->execute();

            $db->transactionCommit();
            
            return $result;
        } catch (Exception $exc) {
            echo $exc;
            $db->transactionRollback();
            return FALSE;
        }
    }
}
