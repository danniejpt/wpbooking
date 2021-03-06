<?php
    /**
     * @since 1.0.0
     **/

    $class      = ' wpbooking-form-group ';
    $data_class = '';
    if ( !empty( $data[ 'condition' ] ) ) {
        $class .= ' wpbooking-condition';
        $data_class .= ' data-condition=' . $data[ 'condition' ] . ' ';
    }
    $property_available_for = get_post_meta( $post_id, 'property_available_for', true );

    $df_price = get_post_meta( $post_id, 'base_price', true );

    $pricing_type = get_post_meta( $post_id, 'pricing_type', true );
    if ( empty( $pricing_type ) ) $pricing_type = 'per_person';

    $post_id  = wpbooking_origin_id( $post_id, 'wpbooking_service' );
?>

<div class="<?php echo esc_html( $class ); ?>" <?php echo esc_html( $data_class ); ?>>
    <label
        for="<?php echo esc_html( $data[ 'id' ] ); ?>"><strong><?php echo esc_html( $data[ 'label' ] ); ?></strong></label>
    <div class="st-metabox-content-wrapper">
        <div class="form-group full-width">
            <div class="wpbooking-calendar-wrapper wb_tour" data-post-id="<?php echo esc_attr( $post_id ); ?>"
                 data-post-encrypt="<?php echo wpbooking_encrypt( $post_id ); ?>">
                <div class="wpbooking-calendar-content">
                    <div class="overlay">
                        <span class="spinner is-active"></span>
                    </div>
                    <div
                        class="calendar-room2 tour <?php echo esc_attr( $pricing_type ); ?> <?php echo ( $property_available_for == 'specific_periods' ) ? 'specific_periods' : false ?>">

                    </div>
                    <div
                        class="calendar-room <?php echo ( $property_available_for == 'specific_periods' ) ? 'specific_periods' : false ?>">

                    </div>
                </div>
                <div class="wpbooking-calendar-sidebar">
                    <div class="form-container calendar-room-form">
                        <h4 class="form-title"><?php echo esc_html__( 'Set price by arranged date', 'wpbooking' ) ?></h4>
                        <p class="form-desc"><?php echo esc_html__( 'You can book rooms for any purposes (like discount, high price, ...)', 'wpbooking' ); ?></p>
                        <div class="calendar-room-form-item full-width">
                            <label class="calendar-label"
                                   for="calendar-checkin"><?php echo esc_html__( 'Start Date', 'wpbooking' ); ?></label>
                            <div class="calendar-input-icon">
                                <input class="calendar-input date-picker" type="text" id="calendar-checkin"
                                       name="calendar-checkin" value="" readonly="readonly"
                                       placeholder="<?php echo esc_html__( 'From Date', 'wpbooking' ); ?>">
                                <label for="calendar-checkin" class="fa"><i class="fa fa-calendar"></i></label>
                            </div>
                        </div>
                        <div class="calendar-room-form-item full-width">
                            <label class="calendar-label"
                                   for="calendar-checkout"><?php echo esc_html__( 'End Date', 'wpbooking' ); ?></label>
                            <div class="calendar-input-icon">
                                <input class="calendar-input date-picker" type="text" id="calendar-checkout"
                                       name="calendar-checkout" value="" readonly="readonly"
                                       placeholder="<?php echo esc_html__( 'To Date', 'wpbooking' ); ?>">
                                <label for="calendar-checkout" class="fa"><i class="fa fa-calendar"></i></label>
                            </div>
                        </div>
                        <div class="calendar-room-form-item full-width">
                            <label class="calendar-label"
                                   for="calendar-status"><?php echo esc_html__( 'Status', 'wpbooking' ); ?></label>
                            <select name="calendar-status" id="calendar-status">
                                <option value="available"><?php echo esc_html__( 'Available', 'wpbooking' ); ?></option>
                                <option
                                    value="not_available"><?php echo esc_html__( 'Not Available', 'wpbooking' ); ?></option>
                            </select>
                        </div>

                        <table class="calendar-room-price-table wpbooking-condition"
                               data-condition="pricing_type:is(per_unit)" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th><?php esc_html_e( 'Min Travelers', 'wpbooking' ) ?></th>
                                <th><?php esc_html_e( 'Max Travelers', 'wpbooking' ) ?></th>
                                <th><?php esc_html_e( 'Price', 'wpbooking' ) ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="number" name="calendar_minimum" min="1" class="number-select"
                                           value="1"></td>
                                <td>
                                    <input type="number" name="calendar_maximum" min="1" class="number-select" value=""
                                           placeholder="0">
                                </td>
                                <td>
                                    <div class="input-group ">
                                        <span
                                            class="input-group-addon"><?php echo WPBooking_Currency::get_current_currency( 'title' ) . ' ' . WPBooking_Currency::get_current_currency( 'symbol' ) ?></span>
                                        <input type="number" class="form-control" value="" id="calendar-price"
                                               name="calendar_price" placeholder="0">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="calendar-room-price-table wpbooking-condition"
                               data-condition="pricing_type:is(per_person)" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th><?php esc_html_e( 'Age band', 'wpbooking' ) ?></th>
                                <th><?php esc_html_e( 'Min Travelers', 'wpbooking' ) ?></th>
                                <th><?php esc_html_e( 'Price', 'wpbooking' ) ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php esc_html_e( 'Adult', 'wpbooking' ) ?></td>
                                <td>
                                    <input type="number" name="calendar_adult_minimum" min="0" class="number-select"
                                           value="" placeholder="0">
                                </td>
                                <td>
                                    <div class="input-group ">
                                        <span
                                            class="input-group-addon"><?php echo WPBooking_Currency::get_current_currency( 'title' ) . ' ' . WPBooking_Currency::get_current_currency( 'symbol' ) ?></span>
                                        <input type="number" class="form-control" value="" name="calendar_adult_price"
                                               placeholder="0">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e( 'Child', 'wpbooking' ) ?></td>
                                <td>
                                    <input type="number" name="calendar_child_minimum" min="0" class="number-select"
                                           value="" placeholder="0">
                                </td>
                                <td>
                                    <div class="input-group ">
                                        <span
                                            class="input-group-addon"><?php echo WPBooking_Currency::get_current_currency( 'title' ) . ' ' . WPBooking_Currency::get_current_currency( 'symbol' ) ?></span>
                                        <input type="number" class="form-control" value="" name="calendar_child_price"
                                               placeholder="0">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e( 'Infant', 'wpbooking' ) ?></td>
                                <td>
                                    <input type="number" name="calendar_infant_minimum" min="0" class="number-select"
                                           value="" placeholder="0">
                                </td>
                                <td>
                                    <div class="input-group ">
                                        <span
                                            class="input-group-addon"><?php echo WPBooking_Currency::get_current_currency( 'title' ) . ' ' . WPBooking_Currency::get_current_currency( 'symbol' ) ?></span>
                                        <input type="number" class="form-control" value="" name="calendar_infant_price"
                                               min="0" placeholder="0">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="clear"></div>
                        <div class="clearfix mb10">
                            <input type="hidden" id="calendar-post-id" name="post_id"
                                   value="<?php echo esc_attr( $post_id ); ?>">
                            <input type="hidden" id="calendar-post-encrypt" name="calendar-post-encrypt"
                                   value="<?php echo wpbooking_encrypt( $post_id ); ?>">
                            <button type="button" id="calendar-save"
                                    class="button button-large wb-button-primary"><?php echo esc_html__( 'Save', 'wpbooking' ); ?></button>
                            <button type="button"
                                    class="calendar-bulk-edit button button-large right"><?php echo esc_html__( 'Bulk Edit', 'wpbooking' ); ?></button>
                        </div>
                        <div class="mb10">

                        </div>
                        <div class="form-message mb10">
                        </div>
                    </div>


                    <div class="calendar-help">
                        <div class="help-label"><?php esc_html_e( 'How to set Availability ?', 'wpbooking' ) ?></div>
                        <h4><strong><?php esc_html_e( 'Way 1:', 'wpbooking' ) ?></strong></h4>
                        <ul class="list">
                            <li>+ <?php esc_html_e( 'To set availability on your calendar:', 'wpbooking' ) ?>
                                <ul>
                                    <li>
                                        - <?php esc_html_e( 'At the  right side of table, click to Start Date picker to set a start date', 'wpbooking' ) ?></li>
                                    <li>
                                        - <?php esc_html_e( 'At the  right side of, click to End Date picker to set a end date of the period you want to edit', 'wpbooking' ) ?></li>
                                </ul>
                            </li>
                            <li>
                                + <?php esc_html_e( 'At the  right side of, allow you to set status and price for that period', 'wpbooking' ) ?></li>

                        </ul>
                        <h4><strong><?php esc_html_e( 'Way 2:', 'wpbooking' ) ?></strong></h4>
                        <ul class="list">
                            <li>
                                + <?php esc_html_e( 'Drag the mouse in the left calendar to get start date and end date', 'wpbooking' ) ?>
                            </li>
                            <li>
                                + <?php esc_html_e( 'A right sight table, allowing you to set status and price for that period', 'wpbooking' ) ?></li>

                        </ul>
                    </div>
                    <div class="form-bulk-edit">
                        <div class="form-container">
                            <div class="overlay">
                                <span class="spinner is-active"></span>
                            </div>
                            <div class="form-title">
                                <h3 class="clearfix"><?php echo esc_html__( 'Bulk Price Edit', 'wpbooking' ); ?>
                                    <button type="button"
                                            class="calendar-bulk-close wpbooking-btn-close pull-right">x</button>
                                </h3>
                            </div>
                            <div class="form-content clearfix">
                                <div class="form-group">
                                    <div class="form-title">
                                        <h4 class=""><input type="checkbox" class="check-all"
                                                            data-name="day-of-week"> <?php echo esc_html__( 'Days Of Week', 'wpbooking' ); ?>
                                        </h4>
                                    </div>
                                    <div class="form-content">
                                        <label class="block"><input type="checkbox" name="day-of-week[]"
                                                                    value="Sunday"><?php echo esc_html__( 'Sunday', 'wpbooking' ); ?>
                                        </label>
                                        <label class="block"><input type="checkbox" name="day-of-week[]"
                                                                    value="Monday"><?php echo esc_html__( 'Monday', 'wpbooking' ); ?>
                                        </label>
                                        <label class="block"><input type="checkbox" name="day-of-week[]"
                                                                    value="Tuesday"><?php echo esc_html__( 'Tuesday', 'wpbooking' ); ?>
                                        </label>
                                        <label class="block"><input type="checkbox" name="day-of-week[]"
                                                                    value="Wednesday"><?php echo esc_html__( 'Wednesday', 'wpbooking' ); ?>
                                        </label>
                                        <label class="block"><input type="checkbox" name="day-of-week[]"
                                                                    value="Thursday"><?php echo esc_html__( 'Thursday', 'wpbooking' ); ?>
                                        </label>
                                        <label class="block"><input type="checkbox" name="day-of-week[]"
                                                                    value="Friday"><?php echo esc_html__( 'Friday', 'wpbooking' ); ?>
                                        </label>
                                        <label class="block"><input type="checkbox" name="day-of-week[]"
                                                                    value="Saturday"><?php echo esc_html__( 'Saturday', 'wpbooking' ); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group group-day">
                                    <div class="form-title">
                                        <h4 class=""><input type="checkbox" class="check-all"
                                                            data-name="day-of-month"> <?php echo esc_html__( 'Days Of Month', 'wpbooking' ); ?>
                                        </h4>
                                    </div>
                                    <div class="form-inner">
                                        <?php for ( $i = 1; $i <= 31; $i++ ):
                                            if ( $i == 1 ) {
                                                echo '<div>';
                                            }
                                            $day = sprintf( '%02d', $i );
                                            ?>
                                            <label><input type="checkbox" name="day-of-month[]"
                                                          value="<?php echo esc_attr( $i ); ?>"><?php echo esc_attr( $day ); ?>
                                            </label>

                                            <?php
                                            if ( $i != 1 && $i % 5 == 0 ) echo '</div><div>';
                                            if ( $i == 31 ) echo '</div>';
                                            ?>

                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="form-group group-month">
                                    <div class="form-title">
                                        <h4 class=""><input type="checkbox" class="check-all"
                                                            data-name="months"> <?php echo esc_html__( 'Months', 'wpbooking' ); ?>
                                            (*)</h4>
                                    </div>
                                    <div class="form-inner">
                                        <?php
                                            $months = [
                                                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
                                            ];
                                            foreach ( $months as $key => $month ):
                                                if ( $key == 0 ) {
                                                    echo '<div>';
                                                }
                                                ?>
                                                <label><input type="checkbox" name="months[]"
                                                              value="<?php echo esc_attr( $month ); ?>"><?php echo esc_html( $month ); ?>
                                                </label>
                                                <?php
                                                if ( $key != 0 && ( $key + 1 ) % 2 == 0 ) echo '</div><div>';
                                                if ( $key + 1 == count( $months ) ) echo '</div>';
                                                ?>

                                            <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-title">
                                        <h4 class=""><input type="checkbox" class="check-all"
                                                            data-name="years"> <?php echo esc_html__( 'Years', 'wpbooking' ); ?>
                                            (*)</h4>
                                    </div>
                                    <div class="form-content">
                                        <?php
                                            $year = date( 'Y' );
                                            $j    = $year - 1;
                                            for ( $i = $year; $i <= $year + 4; $i++ ):
                                                if ( $i == $year ) {
                                                    echo '<div>';
                                                }
                                                ?>
                                                <label><input type="checkbox" name="years[]"
                                                              value="<?php echo esc_attr( $i ); ?>"><?php echo esc_attr( $i ); ?>
                                                </label>

                                                <?php
                                                if ( $i != $year && ( $i == $j + 2 ) ) {
                                                    echo '</div><div>';
                                                    $j = $i;
                                                }
                                                if ( $i == $year + 4 ) echo '</div>';
                                                ?>

                                            <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-content flex clearfix">
                                <label
                                    class="mr10 wpbooking-condition" data-condition="pricing_type:is(per_person)"><span><strong><?php echo esc_html__( 'Adult', 'wpbooking' ); ?>
                                            : </strong></span>
                                    <input type="text" value="" name="adult-bulk" id="adult-bulk"
                                           placeholder="<?php echo esc_html__( 'Adult', 'wpbooking' ); ?>">
                                </label>
                                <label
                                    class="mr10 wpbooking-condition" data-condition="pricing_type:is(per_person)"><span><strong><?php echo esc_html__( 'Child', 'wpbooking' ); ?>
                                            : </strong></span>
                                    <input type="text" value="" name="child-bulk" id="child-bulk"
                                           placeholder="<?php echo esc_html__( 'Child', 'wpbooking' ); ?>">
                                </label>
                                <label
                                    class="mr10 wpbooking-condition" data-condition="pricing_type:is(per_person)"><span><strong><?php echo esc_html__( 'Infant', 'wpbooking' ); ?>
                                            : </strong></span>
                                    <input type="text" value="" name="infant-bulk" id="infant-bulk"
                                           placeholder="<?php echo esc_html__( 'Infant', 'wpbooking' ); ?>">
                                </label>
                                <label
                                    class=" mr10 wpbooking-condition"
                                    data-condition="pricing_type:is(per_unit)"><span><strong><?php echo esc_html__( 'Price', 'wpbooking' ); ?>
                                            : </strong></span>
                                    <input type="text" value="" name="price-bulk" id="price-bulk"
                                           placeholder="<?php echo esc_html__( 'Price', 'wpbooking' ); ?>">
                                </label>
                                <label class="">
                                    <span><strong><?php echo esc_html__( 'Status', 'wpbooking' ); ?>: </strong></span>
                                    <select name="status-bulk">
                                        <option
                                            value="available"><?php echo esc_html__( 'Available', 'wpbooking' ) ?></option>
                                        <option
                                            value="not_available"><?php echo esc_html__( 'Unavailable', 'wpbooking' ) ?></option>
                                    </select>
                                </label>
                                <input type="hidden" class="post-bulk" name="post_id"
                                       value="<?php echo esc_attr( $post_id ); ?>">
                                <input type="hidden" class="type-bulk" name="type-bulk" value="tour">
                                <input type="hidden" class="price-type" name="price-type" value="persion">
                                <input type="hidden" name="post-encrypt"
                                       value="<?php echo wpbooking_encrypt( $post_id ); ?>">
                                <div class="clear"></div>
                            </div>
                            <div class="form-message"></div>
                            <div class="form-footer">
                                <button type="button"
                                        class="calendar-bulk-save button button-primary button-large"><?php echo esc_html__( 'Save', 'wpbooking' ); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <i class="wpbooking-desc"><?php echo do_shortcode( $data[ 'desc' ] ) ?></i>
</div>