<?php
$name = 'wpbooking_'.$data['id'];

if(!empty($data['element_list_item'])){
    $name = $data['custom_name'];
}
if(!empty($data['element_list_item'])){
    $data_value = $data['custom_value'];
}

$class = $name;
$data_class = '';
if(!empty($data['condition'])){
    $class .= ' wpbooking-condition wpbooking-form-group ';
    $data_class .= ' data-condition=wpbooking_'.$data['condition'].' ' ;
}
$list_currency = apply_filters('wpbooking_get_all_currency', array());

$data_value = wpbooking_get_option($data['id']);

$data_value_currency = wp_parse_args($data_value, array(
    'currency'     => '' ,
    'symbol'       => '' ,
    'position'     => '' ,
    'thousand_sep' => '' ,
    'decimal_sep'  => '' ,
    'decimal'      => '' ,
));

?>
<tr class="<?php echo esc_html($class) ?>" <?php echo esc_attr($data_class) ?>>
    <th scope="row">
        <label for="<?php echo esc_html($name) ?>"><?php echo esc_html($data['label']) ?>:</label>
    </th>
    <td>
        <table>
            <tr>
                <td>
                    <label><?php esc_html_e("Currency","wpbooking") ?></label>
                    <select id="<?php echo esc_html($name) ?>[currency]" class="form-control  min-width-250" name="<?php echo esc_html($name) ?>[currency]">
                        <?php if(!empty($list_currency)){
                            foreach($list_currency as $k=>$v){
                                $check = '';
                                if($data_value_currency['currency'] ==$k){
                                    $check = "selected";
                                }
                                echo '<option '.esc_html($check).' value="'.esc_attr($k).'">'.esc_html($v).'</option>';
                            }
                        } ?>
                    </select>
                    <i class="wpbooking-desc"><?php esc_html_e("Currency","wpbooking") ?></i>
                </td>
                <td>
                    <label for="symbol"><?php esc_html_e("Symbol","wpbooking") ?></label>
                    <input id="<?php echo esc_html($name) ?>[symbol]" class="form-control  min-width-250" value="<?php echo esc_attr($data_value_currency['symbol']) ?>" name="<?php echo esc_html($name) ?>[symbol]"  type="text">
                    <i class="wpbooking-desc"><?php esc_html_e("Symbol of currency. For example: $","wpbooking") ?></i>
                </td>
            </tr>
            <tr class="<?php echo esc_html($name) ?>[position]">
                <td>
                    <label for="symbol"><?php esc_html_e("Position of Symbol","wpbooking") ?></label>
                    <select id="<?php echo esc_html($name) ?>[position]" class="form-control  min-width-250" name="<?php echo esc_html($name) ?>[position]">
                        <option <?php selected($data_value_currency['position'],'left') ?> value="left">$99</option>
                        <option <?php selected($data_value_currency['position'],'right') ?> value="right">99$</option>
                        <option <?php selected($data_value_currency['position'],'left_with_space') ?> value="left_with_space">$ 99</option>
                        <option <?php selected($data_value_currency['position'],'right_with_space') ?> value="right_with_space">99 $</option>
                    </select>
                    <i class="wpbooking-desc"><?php esc_html_e("Position of Symbol","wpbooking") ?></i>
                </td>
                <td>
                    <label for="thousand_sep"><?php esc_html_e("Thousand Separator","wpbooking") ?></label>
                    <input id="<?php echo esc_html($name) ?>[thousand_sep]" class="form-control  min-width-250" value="<?php echo esc_attr($data_value_currency['thousand_sep']) ?>" name="<?php echo esc_html($name) ?>[thousand_sep]"   type="text">
                    <i class="wpbooking-desc"><?php esc_html_e("Thousand Separator","wpbooking") ?></i>
                </td>
            </tr>
            <tr class="<?php echo esc_html($name) ?>[decimal_sep]">
                <td>
                    <label for="decimal_sep"><?php esc_html_e("Decimal Separator","wpbooking") ?></label>
                    <input id="<?php echo esc_html($name) ?>[decimal_sep]" class="form-control  min-width-250" value="<?php echo esc_attr($data_value_currency['decimal_sep']) ?>" name="<?php echo esc_html($name) ?>[decimal_sep]"   type="text">
                    <i class="wpbooking-desc"><?php esc_html_e("Decimal Separator","wpbooking") ?></i>
                </td>
                <td>
                    <label for="decimal"><?php esc_html_e("Decimal","wpbooking") ?></label>
                    <input id="<?php echo esc_html($name) ?>[decimal]" class="form-control min-width-250" value="<?php echo esc_attr($data_value_currency['decimal']) ?>" name="<?php echo esc_html($name) ?>[decimal]"  type="number">
                    <i class="wpbooking-desc"><?php esc_html_e("Decimal","wpbooking") ?></i>
                </td>
            </tr>
        </table>
    </td>
</tr>
