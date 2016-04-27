<?php
if(!class_exists('Traveler_Form_Checkbox_Field')){
	class Traveler_Form_Checkbox_Field extends Traveler_Abstract_Formbuilder_Field
	{
		static $_inst;

		function construct()
		{
			$this->field_id='checkbox';
			$this->field_data=array(
				"title"   => __( "Check Box" , 'traveler-booking' ) ,
				"category" => 'Standard Fields',
				"options" => array(
					array(
						"type"             => "text" ,
						"title"            => __( "Title" , 'traveler-booking' ) ,
						"name"             => "title" ,
						"desc"             => __( "Title" , 'traveler-booking' ) ,
						'edit_field_class' => 'traveler-col-md-6' ,
						'value'            => ""
					) ,
					array(
						"type"             => "text" ,
						"title"            => __( "Name" , 'traveler-booking' ) ,
						"name"             => "name" ,
						"desc"             => __( "Name" , 'traveler-booking' ) ,
						'edit_field_class' => 'traveler-col-md-6' ,
						'value'            => ""
					) ,
					array(
						"type"             => "text" ,
						"title"            => __( "ID" , 'traveler-booking' ) ,
						"name"             => "id" ,
						"desc"             => __( "ID" , 'traveler-booking' ) ,
						'edit_field_class' => 'traveler-col-md-6' ,
						'value' => ""
					) ,
					array(
						"type"             => "text" ,
						"title"            => __( "Class" , 'traveler-booking' ) ,
						"name"             => "class" ,
						"desc"             => __( "Class" , 'traveler-booking' ) ,
						'edit_field_class' => 'traveler-col-md-6' ,
						'value' => ""
					) ,
					array(
						"type"             => "textarea" ,
						"title"            => __( "Options" , 'traveler-booking' ) ,
						"name"             => "options" ,
						"desc"             => __("Ex: Title 1:value_1|Title 2:value_2",'traveler-booking'),
						'edit_field_class' => 'traveler-col-md-12' ,
						'value' => ""
					) ,
				)
			);
			parent::__construct();
		}

		function shortcode($attr=array(),$content=FALSE)
		{
			$data = shortcode_atts(
				array(
					'title'          => '' ,
					'name'          => '' ,
					'id'          => '' ,
					'class'       => '' ,
					'options'       => '' ,
				) , $attr , 'traveler_booking_check_box' );
			extract( $data );
			$list_item = "";
			if(!empty($options)){
				$tmp_list_item = explode('|',$options);
				if(!empty($tmp_list_item)){
					foreach($tmp_list_item as $k=>$v){
						$tmp = explode(':',$v);
						if(!empty($tmp[0]) and !empty($tmp[1])){
							$list_item .= '
                                        <label>
                                            <input type="checkbox" name="'.$name.'[]" id="'.$id.'" class="'.$class.'" value="'.$tmp[1].'"> '.$tmp[0].'
                                        </label>';
						}
					}
				}
			}
			parent::add_field($name,array('data'=>$data,'rule'=>''));

			return $list_item;
		}

		function get_value($form_item_data){
			return isset($form_item_data['value'])?$form_item_data['value']:FALSE;
		}

		static  function inst()
		{
			if(!self::$_inst){
				self::$_inst=new self();
			}

			return self::$_inst;
		}
	}
	Traveler_Form_Checkbox_Field::inst();

}