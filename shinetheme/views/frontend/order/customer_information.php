<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 1/3/2017
 * Version: 1.0
 */
?>
<div class="order-information-content customer wpbooking-bootstrap">
    <div class="title">
        <?php esc_html_e("Customer Information","wpbooking") ?>
    </div>
    <div class="row">
        <?php
        $fist_name = get_post_meta($order_id,'wpbooking_user_first_name',true);
        $last_name = get_post_meta($order_id,'wpbooking_user_last_name',true);
        $full_name = $fist_name.' '.$last_name;
        if(!empty($full_name)){?>
            <div class="col-md-12">
                <label><?php esc_html_e("Full name:","wpbooking") ?> </label>
                <p><?php echo esc_html($full_name) ?></p>
            </div>
        <?php } ?>
        <?php if(!empty($email = get_post_meta($order_id,'wpbooking_user_email',true))){ ?>
            <div class="col-md-6">
                <label><?php esc_html_e("Email confirmation:","wpbooking") ?> </label>
                <p><?php echo esc_html($email) ?></p>
            </div>
        <?php } ?>
        <?php if(!empty($phone = get_post_meta($order_id,'wpbooking_user_phone',true))){ ?>
            <div class="col-md-6">
                <label><?php esc_html_e("Telephone:","wpbooking") ?> </label>
                <p><?php echo esc_html($phone) ?></p>
            </div>
        <?php } ?>
        <?php if(!empty($address = get_post_meta($order_id,'wpbooking_user_address',true))){ ?>
            <div class="col-md-12">
                <label><?php esc_html_e("Address:","wpbooking") ?> </label>
                <p><?php echo esc_html($address) ?></p>
            </div>
        <?php } ?>
        <?php if(!empty($postcode_zip = get_post_meta($order_id,'wpbooking_user_postcode',true))){ ?>
            <div class="col-md-6">
                <label><?php esc_html_e("Postcode / Zip:","wpbooking") ?> </label>
                <p><?php echo esc_html($postcode_zip) ?></p>
            </div>
        <?php } ?>
        <?php if(!empty($apt_unit = get_post_meta($order_id,'wpbooking_user_apt_unit',true))){ ?>
            <div class="col-md-6">
                <label><?php esc_html_e("Apt/ Unit:","wpbooking") ?> </label>
                <p><?php echo esc_html($apt_unit) ?></p>
            </div>
        <?php } ?>
        <?php if(!empty($special_request = get_post_meta($order_id,'wpbooking_user_special_request',true))){ ?>
            <div class="col-md-12">
                <label><?php esc_html_e("Special request:","wpbooking") ?> </label>
                <p><?php echo esc_html($special_request) ?></p>
            </div>
        <?php } ?>

        <?php do_action('wpbooking_order_detail_customer_information',$order_data) ?>
        <?php do_action('wpbooking_order_detail_customer_information_'.$service_type,$order_data) ?>

        <div class="col-md-12 text-center">
            <?php
            $page_account = wpbooking_get_option('myaccount-page');
            if(!empty($page_account)){
                $link_page = get_permalink($page_account);
                ?>
                <a href="<?php echo esc_url($link_page) ?>tab/booking_history/" class="wb-button wb-btn wb-btn-primary wb-history"><?php esc_html_e("Booking History","wpbooking") ?></a>
            <?php } ?>
        </div>
    </div>
</div>
