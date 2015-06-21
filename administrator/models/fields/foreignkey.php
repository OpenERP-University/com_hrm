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
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports a value from an external table
 */
class JFormFieldForeignKey extends JFormField
{

	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	protected $type = 'foreignkey';
	private $input_type;
	private $table;
	private $key_field;
	private $value_field;
        private $table_check;
        private $key_field_check;

        /**
	 * Method to get the field input markup.
	 *
	 * @return    string    The field input markup.
	 * @since    1.6
	 */
	protected function getInput()
	{

		//Assign field properties.
		//Type of input the field shows
		$this->input_type = $this->getAttribute('input_type');

		//Database Table
		$this->table = $this->getAttribute('table');

		//The field that the field will save on the database
		$this->key_field = (string) $this->getAttribute('key_field');

		//The column that the field shows in the input
		$this->value_field = (string) $this->getAttribute('value_field');
                
                //Check exits On table 
                $this->table_check = (string) $this->getAttribute('table_check');
                
                //The column foreignkey
                $this->key_field_check = (string) $this->getAttribute('key_field_check');

                //special_guid
        		$this->special_guid = (string) $this->getAttribute('special_guid');
                
		// Initialize variables.
		$html = '';

		//Load all the field options
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select(
				array(
					$db->quoteName($this->key_field),
					$db->quoteName($this->value_field)
				)
			)
			->from($this->table);
                if($this->table_check && $this->key_field_check){
                    $query->where($db->quoteName($this->key_field).' IN (SELECT '.$db->quoteName($this->key_field_check).' FROM '.$db->quoteName($this->table_check).')');
                    if ($this->special_guid) {
                		$query->where($db->quoteName($this->key_field) . ' <> ' . $db->quote($this->special_guid));

                		$queryUnion = $db->getQuery(true);
                		$queryUnion
                        		->select(
                                		array(
                                    		$db->quoteName($this->key_field),
                                    		$db->quoteName($this->value_field)
                                		)
                        		)
                        		->from($this->table_check)
                        		->where($db->quoteName($this->key_field_check) . ' = ' . $db->quote($this->special_guid));
                		$query->union($queryUnion);
            		}
                }
		$db->setQuery($query);
		$results = $db->loadObjectList();

		$input_options = 'class="' . $this->getAttribute('class') . '"';

		//Depends of the type of input, the field will show a type or another
		switch ($this->input_type)
		{
			case 'list':
			default:
				$options = array();

				//Iterate through all the results
				foreach ($results as $result)
				{
					$options[] = JHtml::_('select.option', $result->{$this->key_field}, $result->{$this->value_field});
				}

				$value = $this->value;

				//If the value is a string -> Only one result
				if (is_string($value))
				{
					$value = array($value);
				}
				else if (is_object($value))
				{ //If the value is an object, let's get its properties.
					$value = get_object_vars($value);
				}

				//If the select is multiple
				if ($this->multiple)
				{
					$input_options .= 'multiple="multiple"';
				}
				else
				{
					array_unshift($options, JHtml::_('select.option', '', ''));
				}

				$html = JHtml::_('select.genericlist', $options, $this->name, $input_options, 'value', 'text', $value, $this->id);
				break;
		}

		return $html;
	}

	/**
	 * Wrapper method for getting attributes from the form element
	 *
	 * @param string $attr_name Attribute name
	 * @param mixed  $default   Optional value to return if attribute not found
	 *
	 * @return mixed The value of the attribute if it exists, null otherwise
	 */
	public function getAttribute($attr_name, $default = null)
	{
		if (!empty($this->element[$attr_name]))
		{
			return $this->element[$attr_name];
		}
		else
		{
			return $default;
		}
	}

}
