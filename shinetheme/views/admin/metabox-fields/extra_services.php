<?php
/**
 *@since 1.0.0
 **/
$type_id=!empty($data['service_type'])?$data['service_type']:false;
?>
<div class="form-table wpbooking-settings wpbooking-form-group wpbooking_extra_service_type wpbooking-condition " >
	<div class="st-metabox-left">
		<label for="<?php echo esc_html( $data['id'] ); ?>"><?php echo esc_html( $data['label'] ); ?></label>
	</div>
	<div class="st-metabox-right">
		<div class="st-metabox-content-wrapper wb-extra-services-wrapper">
			<div class="form-group">
				<div class="list-extra-services">
					<?php
					$old=get_post_meta($post_id,$data['id'],true);
					
					$extras=(!empty($data['extra_services']))?$data['extra_services']:array();
					if(!empty($extras)){
						foreach($extras as $k=>$value){
							$checked=FALSE;
							$current=FALSE;
							$is_required=FALSE;
							$selected_quantity= '';
							if(!empty($old[$k])){
								$current=$old[$k];
								if($old[$k]['is_selected']) {
									$checked='checked';
									if($old[$k]['require']=='yes') $is_required=TRUE;
									$selected_quantity = $old[$k]['quantity'];
								}
							}

							$condition = '';
							$service_types = get_term_meta($k,'service_type');
							if(!empty($service_types)){
								foreach($service_types as $k2=>$v2){
									$condition .= 'wb_service_type:is('.$v2.')';
								}
							}
							if(empty($condition)) $condition = 'wb_service_type:is()';

						?>
							<div class="extra-item wpbooking-condition" data-condition="<?php echo esc_html($condition) ?>" data-operator="or">
								<div class="service_detail">
									<label class="title" ><input type="checkbox" value="<?php echo esc_html($value['title']) ?>" <?php echo esc_attr($checked) ?> name="<?php echo esc_attr($data['id'].'['.esc_attr($k).'][is_selected]') ?>">
									<?php echo esc_html($value['title']) ?></label>
								</div>
								<div class="money-number">
									<div class="input-group ">
										<span class="input-group-addon" ><?php echo WPBooking_Currency::get_current_currency('title').' '.WPBooking_Currency::get_current_currency('symbol') ?></span>
										<input type="number" class="form-control" placeholder="0" value="<?php echo (!empty($current['money']))?$current['money']:''; ?>" min="0" name="<?php echo esc_attr($data['id'].'['.esc_attr($k).'][money]') ?>"  >
									</div>
								</div>
								<div class="max-quantity">
									<select name="<?php echo esc_attr($data['id'].'['.esc_attr($k).'][quantity]') ?>" class="max-quantity-select" >
										<?php for($i = 1; $i <=20; $i++ ) {
											echo '<option '.selected($selected_quantity,$i,false).' value="'.esc_attr($i).'">'.esc_html($i).'</option>';
										}?>
									</select>
									<span class="help_inline"><?php esc_html_e('Max quantity','wpbooking') ?></span>
								</div>
								<div class="require-options">
									<select name="<?php echo esc_attr($data['id'].'['.esc_attr($k).'][require]') ?>" >
										<option value="no"><?php esc_html_e('No','wpbooking') ?></option>
										<option <?php echo ($is_required)?'selected':false; ?> value="yes"><?php esc_html_e('Yes','wpbooking') ?></option>
									</select>
									<span class="help_inline"><?php esc_html_e('Required','wpbooking') ?></span>
								</div>
								<div class="service_desc">
									<span class="service_desc metabox-help"><?php echo do_shortcode($value['description']); ?>
										<input type="hidden" value="<?php echo esc_html($value['description']) ?>"  name="<?php echo esc_attr($data['id'].'['.esc_attr($k).'][desc]') ?>"/>
									</span>
								</div>
							</div>
						<?php
						}
					}?>
				</div>
				<div class="add-new-extra-service">
					<div class="hidden extra-item-default ">
						<div class="extra-item">
							<div class="service_detail">
								<label class="title" ><input type="checkbox" value="" name=""> <span class="extra-item-name"></span>
									</label>
								<span class="service_desc metabox-help"></span>
							</div>
							<div class="money-number">
								<div class="input-group ">
									<span class="input-group-addon" ><?php echo WPBooking_Currency::get_current_currency('title').' '.WPBooking_Currency::get_current_currency('symbol') ?></span>
									<input type="number" placeholder="0" min="0" class="form-control" value="" name=""  >
								</div>
							</div>
							<div class="max-quantity">
								<select name="" class="max-quantity-select" >
									<option value=""><?php esc_html_e('-','wpbooking') ?></option>
									<?php for($i = 1; $i <=20; $i++ ) {
										echo '<option value="'.esc_attr($i).'">'.esc_html($i).'</option>';
									}?>
								</select>
								<span class="help_inline"><?php esc_html_e('Max quantity','wpbooking') ?></span>
							</div>
							<div class="require-options">
								<select name="" >
									<option value="no"><?php esc_html_e('No','wpbooking') ?></option>
									<option value="yes"><?php esc_html_e('Yes','wpbooking') ?></option>
								</select>
								<span class="help_inline"><?php esc_html_e('Required', 'wpbooking') ?></span>
							</div>
						</div>
					</div>
					<input type="text" class="service-name " placeholder="<?php esc_html_e('Extra Service Name','wpbooking') ?>">
					<input type="text" class="service-desc" placeholder="<?php esc_html_e('Description','wpbooking') ?>">
					<select class="max-quantity-sv" >
						<option value=""><?php esc_html_e('Max Quantity','wpbooking') ?></option>
						<?php for($i = 1; $i <=20; $i++ ) {
							echo '<option value="'.esc_attr($i).'">'.esc_html($i).'</option>';
						}?>
					</select>
					<a href="#" onclick="return false" class="button wb-btn-add-extra-service" data-id="<?php echo esc_attr($data['id']) ?>" data-type
					="<?php echo esc_attr($type_id) ?>" ><?php esc_html_e('Add New','wpbooking') ?> <i class="fa fa-spin  fa-spinner loading-icon"></i></a>
				</div>
			</div>
		</div>
		<div class="metabox-help"><?php echo do_shortcode( $data['desc'] ) ?></div>
	</div>
</div>
<?php
