<?php
    $service      = wpbooking_get_service();
    $service_type = $service->get_type();
    $hotel_id     = get_the_ID();
?>
<div itemscope itemtype="http://schema.org/Place" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

    <meta itemprop="url" content="<?php the_permalink(); ?>"/>
    <div class="container-fluid wpbooking-single-content entry-header">
    <div class="wb-service-title-address">
        <h1 class="wb-service-title" itemprop="name"><?php the_title(); ?></h1>
        <div class="wb-hotel-star">
            <?php
                $service->get_star_rating_html();
            ?>
        </div>
        <?php $address = $service->get_address();
            if ( $address ) {
                ?>
                <div class="service-address" itemprop="address">
                    <i class="fa fa-map-marker"></i> <?php echo esc_html( $address ) ?>
                </div>
            <?php } ?>
        <?php do_action( 'wpbooking_after_service_address_rate', $hotel_id, $service->get_type(), $service ) ?>
    </div>
    <div class="wb-price-html" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <?php $service->get_price_html( true ); ?>
    </div>
    <div class="row-service-gallery-contact">
        <div class="col-service-gallery">
            <div class="wb-tabs-gallery-map">
                <?php
                    $map_lat  = get_post_meta( $hotel_id, 'map_lat', true );
                    $map_lng  = get_post_meta( $hotel_id, 'map_long', true );
                    $map_zoom = get_post_meta( $hotel_id, 'map_zoom', true );
                ?>
                <ul class="wb-tabs">
                    <li class="active"><a href="#photos"><i class="fa fa-camera"></i>
                            &nbsp;<?php esc_html_e( 'Photos', 'wpbooking' ); ?></a></li>
                    <?php if ( !empty( $map_lat ) and !empty( $map_lng ) ) { ?>
                        <li><a href="#map"><i class="fa fa-map-marker"></i>
                                &nbsp;<?php esc_html_e( 'On the map', 'wpbooking' ); ?></a></li>
                    <?php } ?>
                </ul>
                <div class="wp-tabs-content">
                    <div class="wp-tab-item" id="photos">
                        <div class="service-gallery-single">
                            <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs">
                                <?php
                                    $gallery = $service->get_gallery();
                                    if ( !empty( $gallery ) and is_array( $gallery ) ) {
                                        foreach ( $gallery as $k => $v ) {
                                            echo( $v[ 'gallery' ] );
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        if ( !empty( $map_lat ) and !empty( $map_lng ) ) { ?>
                            <div class="wp-tab-item" id="map">
                                <div class="service-map">

                                    <div class="service-map-element" data-lat="<?php echo esc_attr( $map_lat ) ?>"
                                         data-lng="<?php echo esc_attr( $map_lng ) ?>"
                                         data-zoom="<?php echo esc_attr( $map_zoom ) ?>"></div>

                                </div>
                            </div>
                        <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-service-reviews-meta">
            <div class="wb-service-reviews-meta">
                <?php
                    do_action( 'wpbooking_before_contact_meta' );
                ?>
                <?php
                    $contact_meta = [
                        'contact_number' => 'fa-phone',
                        'contact_email'  => 'fa-envelope',
                        'website'        => 'fa-home',
                    ];
                    $html         = '';
                    foreach ( $contact_meta as $key => $val ) {
                        if ( $value = get_post_meta( $hotel_id, $key, true ) ) {
                            switch ( $key ) {
                                case 'contact_number':
                                    $value = sprintf( '<a href="tel:%s" itemprop="telephone" >%s</a>', esc_html( $value ), do_shortcode( $value ) );
                                    break;

                                case 'contact_email':
                                    $value = sprintf( '<a href="mailto:%s" itemprop="email" >%s</a>', esc_html( $value ), do_shortcode( $value ) );
                                    break;
                                case 'website';
                                    $value = '<a target=_blank href="' . esc_url( $value ) . '" itemprop="url" >' . do_shortcode( $value ) . '</a>';
                                    break;
                            }
                            $html .= '<div class="wb-meta-contact">
                                    <i class="fa ' . esc_attr( $val ) . ' wb-icon-contact"></i>
                                    <span>' . do_shortcode( $value ) . '</span>
                                </div>';
                        }
                    }
                    if ( !empty( $html ) ) {
                        echo '<div class="wb-contact-box wp-box-item">' . do_shortcode( $html ) . '</div>';
                    }
                    do_action( 'wpbooking_after_contact_meta' );
                ?>
            </div>
        </div>
    </div>
    <div class="service-content-section">
        <h5 class="service-info-title"><?php esc_html_e( 'Description', 'wpbooking' ) ?></h5>
        <div class="service-content-wrap" itemprop="description">
            <?php
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        the_content();
                    }
                }
            ?>
        </div>
    </div>
    <?php
        $amenities = get_post_meta( $hotel_id, 'wpbooking_select_amenity', true );
        if ( !empty( $amenities ) ) {
            ?>
            <div class="service-content-section">
                <h5 class="service-info-title"><?php esc_html_e( 'Amenities', 'wpbooking' ) ?></h5>
                <div class="service-content-wrap">
                    <ul class="wb-list-amenities">
                        <?php
                            foreach ( $amenities as $val ) {
                                $amenity = get_term_by( 'id', $val, 'wpbooking_amenity' );
                                if ( !empty( $amenity->term_id ) ) {
                                    $icon = get_tax_meta( $amenity->term_id, 'wpbooking_icon' );
                                    if ( !empty( $amenity ) ) {
                                        echo '<li><i class="fa fa-check-square-o"></i> &nbsp; <i class="' . wpbooking_handle_icon( $icon ) . '"></i> ' . esc_html( $amenity->name ) . '</li>';
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php do_action( 'wpbooking_after_service_amenity' ) ?>
    <div class="service-content-section">
        <h5 class="service-info-title"><?php esc_html_e( 'Accommodation Policies', 'wpbooking' ) ?></h5>

        <div class="service-details">
            <?php
                $check_in      = [
                    'checkin_from' => esc_html__( 'from %s ', 'wpbooking' ),
                    'checkin_to'   => esc_html__( 'to %s', 'wpbooking' )
                ];
                $check_out     = [
                    'checkout_from' => esc_html__( 'from %s ', 'wpbooking' ),
                    'checkout_to'   => esc_html__( 'to %s', 'wpbooking' )
                ];
                $time_html     = '';
                $checkin_html  = esc_html__( 'Check In: ', 'wpbooking' );
                $checkout_html = esc_html__( 'Check Out: ', 'wpbooking' );
                foreach ( $check_in as $key => $val ) {
                    $value = get_post_meta( $hotel_id, $key, true );
                    if ( $key == 'checkin_from' && empty( $value ) ) {
                        $checkin_html = '';
                        break;
                    } else {
                        if ( !empty( $value ) ) {
                            $checkin_html .= sprintf( $val, $value );
                        }
                        if ( $key == 'checkin_to' && empty( $value ) ) {
                            $checkin_html = str_replace( 'from ', '', $checkin_html );
                        }
                    }
                }
                $bool = false;
                foreach ( $check_out as $key => $val ) {
                    $value = get_post_meta( $hotel_id, $key, true );
                    if ( $key == 'checkout_to' && empty( $value ) ) {
                        $checkout_html = '';
                        break;
                    } else {
                        if ( !empty( $value ) ) {
                            $checkout_html .= sprintf( $val, $value );
                            if ( $bool ) $checkout_html = $value;
                        }
                        if ( $key == 'checkout_from' && empty( $value ) ) {
                            $bool = true;
                        }
                    }
                }
                $time_html = $checkin_html . '<br>' . $checkout_html;
                if ( !empty( $checkin_html ) || !empty( $checkout_html ) ) {
                    ?>
                    <div class="service-detail-item">
                        <div class="service-detail-title"><?php esc_html_e( 'Time', 'wpbooking' ) ?></div>
                        <div class="service-detail-content">
                            <?php echo( $time_html ) ?>
                        </div>
                    </div>
                    <?php
                }
                $array                = [
                    'deposit_payment_status' => '',
                    'deposit_payment_amount' => wp_kses( esc_html__( 'Deposit: %s &nbsp;&nbsp; required', 'wpbooking' ), [ 'span' => [ 'class' => [] ] ] ),
                    'allow_cancel'           => esc_html__( 'Allowed Cancellation: Yes', 'wpbooking' ),
                    'cancel_free_days_prior' => esc_html__( 'Time allowed to free: %s', 'wpbooking' ),
                    'cancel_guest_payment'   => esc_html__( 'Fee cancel for booking: %s', 'wpbooking' ),
                ];
                $cancel_guest_payment = [
                    'first_night' => esc_html__( '100&#37; of the first night', 'wpbooking' ),
                    'full_stay'   => esc_html__( '100&#37; of the full stay', 'wpbooking' ),
                ];

                $deposit_html  = [];
                $allow_deposit = '';
                foreach ( $array as $key => $val ) {
                    $meta = get_post_meta( $hotel_id, $key, true );
                    if ( $key == 'deposit_payment_status' ) {
                        $allow_deposit = $meta;
                        continue;
                    }
                    if ( !empty( $meta ) ) {
                        if ( $key == 'deposit_payment_amount' ) {
                            if ( empty( $allow_deposit ) ) {
                                $deposit_html[] = '';
                            } elseif ( $allow_deposit == 'amount' ) {
                                $deposit_html[] = sprintf( $val, WPBooking_Currency::format_money( $meta ) );
                            } else {
                                $deposit_html[] = sprintf( $val, $meta . '%' );
                            }
                            continue;
                        }
                        if ( $key == 'cancel_guest_payment' ) {
                            $deposit_html[] = sprintf( $val, $cancel_guest_payment[ $meta ] );
                            continue;
                        }
                        if ( $key == 'cancel_free_days_prior' ) {
                            if ( $meta == 'day_of_arrival' )
                                $deposit_html[] = sprintf( $val, esc_html__( 'Day of arrival (6 pm)', 'wpbooking' ) );
                            else
                                $deposit_html[] = sprintf( $val, $meta . esc_html__( ' day', 'wpbooking' ) );

                            continue;
                        }

                    }
                    if ( $key == 'allow_cancel' ) {
                        $deposit_html[] = $val;
                        continue;
                    }
                }

                if ( !empty( $deposit_html ) ) {
                    ?>
                    <div class="service-detail-item">
                        <div
                                class="service-detail-title"><?php esc_html_e( 'Prepayment / Cancellation', 'wpbooking' ) ?></div>
                        <div class="service-detail-content">
                            <?php
                                foreach ( $deposit_html as $value ) {
                                    if ( !empty( $value ) ) echo ( $value ) . '<br>';
                                }
                            ?>
                        </div>
                    </div>
                <?php } ?>


            <?php
                $tax_html         = [];
                $array            = [
                    'vat_excluded'     => '',
                    'vat_unit'         => '',
                    'vat_amount'       => esc_html__( 'V.A.T: %s &nbsp;&nbsp;', 'wpbooking' ),
                    'citytax_excluded' => '',
                    'citytax_unit'     => '',
                    'citytax_amount'   => esc_html__( 'City tax: %s', 'wpbooking' ),
                ];
                $citytax_unit     = [
                    'stay'             => esc_html__( ' /stay', 'wpbooking' ),
                    'person_per_stay'  => esc_html__( ' /person per stay', 'wpbooking' ),
                    'night'            => esc_html__( ' /night', 'wpbooking' ),
                    'percent'          => esc_html__( '%', 'wpbooking' ),
                    'person_per_night' => esc_html__( ' /person per night', 'wpbooking' ),
                ];
                $vat_excluded     = '';
                $citytax_excluded = '';
                $ct_unit          = '';
                foreach ( $array as $key => $val ) {
                    $value = get_post_meta( $hotel_id, $key, true );
                    if ( !empty( $value ) ) {
                        switch ( $key ) {
                            case 'vat_excluded':
                                $vat_excluded = $value;
                                break;
                            case 'vat_unit':
                                $ct_unit = $value;
                                break;
                            case 'vat_amount':
                                $amount = '';
                                if ( !empty( $ct_unit ) ) {
                                    if ( $ct_unit == 'percent' ) {
                                        $amount = $value . '%';
                                    } else {
                                        $amount = WPBooking_Currency::format_money( $value );
                                    }
                                }

                                if ( $vat_excluded == 'yes_included' ) {
                                    $tax_html[] = sprintf( $val, $amount . ' &nbsp;&nbsp;<span class="enforced_red">' . wp_kses( esc_html__( 'included', 'wpbooking' ), [ 'span' => [ 'class' => [] ] ] ) . '</span>' );
                                } elseif ( $vat_excluded != '' ) {
                                    $tax_html[] = sprintf( $val, $amount );
                                }
                                break;
                            case 'citytax_excluded':
                                $citytax_excluded = $value;
                                break;
                            case 'citytax_unit':
                                $ct_unit = $value;
                                break;
                            case 'citytax_amount':
                                if ( !empty( $ct_unit ) ) {
                                    if ( $ct_unit == 'percent' ) {
                                        $str_citytax = sprintf( $val, $value ) . $citytax_unit[ $ct_unit ];
                                    } else {
                                        $str_citytax = sprintf( $val, WPBooking_Currency::format_money( $value ) ) . $citytax_unit[ $ct_unit ];
                                    }
                                }
                                if ( $citytax_excluded != '' ) {
                                    if ( $citytax_excluded == 'yes_included' ) {
                                        $tax_html[] = $str_citytax . '&nbsp;&nbsp; <span class="enforced_red">' . esc_html__( 'included', 'wpbooking' ) . '</span>';
                                    } else {
                                        $tax_html[] = $str_citytax;
                                    }
                                }
                                break;
                        }
                    }
                }

                if ( !empty( $tax_html ) ) {
                    ?>
                    <div class="service-detail-item">
                        <div
                                class="service-detail-title"><?php esc_html_e( 'Tax', 'wpbooking' ) ?></div>
                        <div class="service-detail-content">
                            <?php foreach ( $tax_html as $value ) {
                                echo ( $value ) . '<br>';
                            } ?>
                        </div>
                    </div>
                <?php } ?>



            <?php
                var_dump($hotel_id);
                if ( $terms_conditions = get_post_meta( $hotel_id, 'terms_conditions', true ) ) { ?>
                    <div class="service-detail-item">
                        <div class="service-detail-title"><?php esc_html_e( 'Term & Condition', 'wpbooking' ) ?></div>
                        <div class="service-detail-content">
                            <?php echo( $terms_conditions ); ?>
                        </div>
                    </div>
                <?php } ?>

            <?php
                $card       = get_post_meta( $hotel_id, 'creditcard_accepted', true );
                $card_image = [
                    'americanexpress'    => 'wb-americanexpress',
                    'visa'               => 'wb-visa',
                    'euromastercard'     => 'wb-euromastercard',
                    'dinersclub'         => 'wb-dinersclub',
                    'jcb'                => 'wb-jcb',
                    'maestro'            => 'wb-maestro',
                    'discover'           => 'wb-discover',
                    'unionpaydebitcard'  => 'wb-unionpaydebitcard',
                    'unionpaycreditcard' => 'wb-unionpaycreditcard',
                    'bankcard'           => 'wb-bankcard',
                ];
                if ( !empty( $card ) ) {
                    ?>
                    <div class="service-detail-item">
                        <div class="service-detail-title"><?php esc_html_e( 'Accepted Cards', 'wpbooking' ) ?></div>
                        <div class="service-detail-content">
                            <ul class="wb-list-card-acd">
                                <?php foreach ( $card as $key => $val ) {
                                    if ( !empty( $val ) ) {
                                        echo '<li class="' . esc_attr( $card_image[ $key ] ) . '">';
                                        echo '</li>';
                                    }
                                } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

        </div>
    </div>

    <div class="service-content-section comment-section">
        <?php
            if ( comments_open( $hotel_id ) || get_comments_number() ) :
                comments_template();
            endif;
            wp_reset_query();
        ?>
    </div>
<?php echo wpbooking_load_view( 'single/related' ) ?>