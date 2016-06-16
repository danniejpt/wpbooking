<?php
/**
 * Created by PhpStorm.
 * User: Dungdt
 * Date: 6/2/2016
 * Time: 5:28 PM
 */

$booking=WPBooking_Order::inst();
$value=$order_item;
$items=$booking->get_order_items($order_id);
$checkout_form_data=$booking->get_order_form_datas($order_id);

?>

<div class="wrap">
	<h1><?php esc_html_e('Order Item Detail','wpbooking') ?></h1>

	<?php echo wpbooking_get_admin_message() ?>
	<div id="poststuff">

		<div class="wpbooking-order-information postbox ">
			<h3 class="hndle"><?php esc_html_e('Order Items','wpbooking') ?></h3>
				<div class="inside">
					<?php
					$service_type=$value['service_type'];
					?>
					<div class="review-order-item-info">
						<a class="service-name" href="<?php echo get_permalink($value['post_id'])?>" target="_blank"><?php echo get_the_title($value['post_id'])?></a>
						<?php do_action('wpbooking_order_item_information',$value) ?>
						<?php do_action('wpbooking_order_item_information_'.$service_type,$value) ?>
					</div>
					<div class="review-order-item-total">
						<p class="cart-item-price"><?php esc_html_e('Total','wpbooking'); echo WPBooking_Currency::format_money($booking->get_order_item_total($value)); ?></p>
					</div>
					<?php
					do_action('wpbooking_before_checkout_form_data');

					if(!empty($checkout_form_data) and is_array($checkout_form_data)){?>
						<hr>
						<div class="checkout-form-data">
							<h3><?php _e('Checkout Information','wpbooking')?></h3>

							<ul class="checkout-form-list">
								<?php foreach($checkout_form_data as $key=>$value){
									$value_html= WPBooking_Admin_Form_Build::inst()->get_form_field_data($value);
									if($value_html){
										?>
										<li class="form-item">
						<span class="form-item-title">
							<?php echo do_shortcode($value['title']) ?>:
						</span>
						<span class="form-item-value">
							<?php echo do_shortcode($value_html) ?>
						</span>
										</li>
										<?php
									}
								} ?>
							</ul>
						</div>
					<?php }?>

					<hr>
					<h3><?php esc_html_e('Payment Method','wpbooking') ?></h3>
					<?php
					$selected_gateway=get_post_meta($order_id,'wpbooking_selected_gateway',true);

					?>


					<?php do_action('wpbooking_end_checkout_form_data');?>
				</div>

		</div>

	</div>
</div>