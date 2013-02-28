<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ExpressionEngine Textarea Fieldtype Class
 *
 * @package		Bene Field Duplicator 
 * @category	Fieldtypes
 * @author		Tim Bertens
 * @link		http://www.bene.be
 */

class Bene_field_duplicator_ft extends EE_Fieldtype {
	
	var $info = array(
			'name'		=>	'Bene Field Duplicator',
			'version'	=>	'1.1'
			);

	
	var $has_array_data = TRUE;
	
	function Bene_field_duplicator_ft ()
	{
		parent::EE_Fieldtype();
		
	}

	// --------------------------------------------------------------------

	function install()
	{
			return array(
				'selected_field_id'	=> '1',
			);
	}

	// --------------------------------------------------------------------
	function save($data)
	{
		$field_id = "field_id_".$this->settings['selected_field_id'];
		$transformation_id = $this->settings['selected_transformation_id'];		

		if($field_id=="field_id_100000" or $field_id=="field_id_100001")
		{
			if($field_id == "field_id_100000")
			{
				$field_id = 'title';
			}
			else
			{
				$field_id = 'url_title';
			}
		}
				
		switch ($transformation_id) 
		{
		    case 1:
		        $transformed_string = $this->EE->input->post($field_id);
		        break;
		    case 2:
		        $this->CI =& get_instance(); 
				$this->CI->load->helper('url');
    			$transformed_string = url_title($this->EE->input->post($field_id));
		        break;
		}
		
		return $transformed_string;	
			
	}
	
	
	// --------------------------------------------------------------------

	function display_field($data)
	{

		$field = array(
			'name'		=> $this->field_name,
			'id'		=> $this->field_name,
			'value'		=> $data,
			'dir'		=> $this->settings['field_text_direction'],
			'readonly'  => 'true'
			);
		
		return form_input($field);
		
	}

	// --------------------------------------------------------------------
	
	function display_settings($settings)
	{	
		if (array_key_exists("selected_field_id", $settings )) 
			{
			$selected_field = $settings['selected_field_id'];
			} 
			else 
			{
			$selected_field = 100000;
			}
			
		if (array_key_exists("selected_transformation_id", $settings )) 
			{
			$selected_transformation = $settings['selected_transformation_id'];
			} 
			else 
			{
			$selected_transformation = 1;
			}	
			
		$results[100000]='Title';
		$results[100001]='URL Title';
		
		$group_id = $this->EE->input->get('group_id');
		$query = $this->EE->db->query("SELECT field_id,field_label,field_name FROM exp_channel_fields WHERE group_id = $group_id");
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row) {
				if ($settings['field_id'] != $row['field_id']) {
				$field_id = $row['field_id'];
				$results[$field_id] = $row['field_label']; 
				}
				
			}
		} else {
		$results[0] = "No other custom fields defined.";
		}
	
		// show available fields in dropdown
		$this->EE->table->add_row(
				'Base Field',
		 form_dropdown('selected_field_id', $results, $selected_field));
		
		// show available transformations 
		$this->EE->table->add_row(
				'Transformation',
				form_dropdown('selected_transformation_id', array(1=>'No tranformation', 2=>'EE URL'), $selected_transformation)
				);

	}
	

	// --------------------------------------------------------------------

	function save_settings ($data)
	{
		return array_merge($this->settings, $_POST);	
	}
	
	


	
} 
// END Bene_field_duplicator_ft class

/* End of file ft.bene_field_duplicator_ft.php */
/* Location: ./system/expressionengine/third_party/bene_field_duplicator/ft.bene_field_duplicator.php */