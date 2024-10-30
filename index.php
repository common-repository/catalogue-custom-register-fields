<?php
/**
 * Plugin Name:       Catalogue Custom Register Fields
 * Description:       Get your own custom fields to the registration form
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Benjamin Drake
 * Author URI:        https://paintcatalogue.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       catalog-custom-registerfields
 */

//Prevent access directly, abort.
if (!defined('WPINC')){
    die;
}
if(!defined('CCRFPLUGIN_VERSION')){
    define('CCRFPLUGIN_VERSION', '1.0.0');
}

if(!defined('CCRFPLUGIN_DIR')){
    define('CCRFPLUGIN_DIR', plugin_dir_url(__FILE__));
}

// To add front end of custom field
add_action('woocommerce_register_form_start', 'ccrffront_end_field');

if(!function_exists('ccrffront_end_field')){
function ccrffront_end_field()
{
    //TODO
?>
    <p class="form-row form-row-first">
        <label for="billing_first_name"><?php _e('First name', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="billing_first_name" value="<?php if (!empty($_POST['billing_first_name'])) esc_attr_e($_POST['billing_first_name']); ?>" />
    </p>
    <p class="form-row form-row-last">
        <label for="billing_last_name"><?php _e('Last name', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="billing_last_name" value="<?php if (!empty($_POST['billing_last_name'])) esc_attr_e($_POST['billing_last_name']); ?>" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_address_1"><?php _e('Address', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_address_1" id="billing_address_1" value="<?php if (!empty($_POST['billing_address_1'])) esc_attr_e($_POST['billing_address_1']); ?>" />
    </p>
    <p class="form-row form-row-first">
        <label for="billing_city"><?php _e('City', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_city" id="billing_city" value="<?php if (!empty($_POST['billing_city'])) esc_attr_e($_POST['billing_city']); ?>" />
    </p>
    <p class="form-row form-row-last">
        <label for="billing_postcode"><?php _e('Postal Code', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_postcode" id="billing_postcode" value="<?php if (!empty($_POST['billing_postcode'])) esc_attr_e($_POST['billing_postcode']); ?>" />
    </p>
    <p class="form-row form-row-first">
        <label for="billing_phone"><?php _e('Phone Number', 'text_domain'); ?><span class="required">*</span></label>
        <input type="number" class="input-text" name="billing_phone" id="billing_phone" value="<?php if (!empty($_POST['billing_phone'])) esc_attr_e($_POST['billing_phone']); ?>" />
    </p>
    <!-- country -->
    <p class="form-row form-row-last">
        <label for="billing_country"><?php _e('Country', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_country" id="billing_country" value="<?php if (!empty($_POST['billing_country'])) esc_attr_e($_POST['billing_country']); ?>" />
    </p>
    <p class="form-row form-row-last">
        <label for="billing_state"><?php _e('State', 'text_domain'); ?></label>
        <input type="text" class="input-text" name="billing_state" id="billing_state" value="<?php if (!empty($_POST['billing_state'])) esc_attr_e($_POST['billing_state']); ?>" />
    </p>
    <p class="form-row form-row-first">
        <label for="billing_company"><?php _e('Company', 'text_domain'); ?></label>
        <input type="text" class="input-text" name="billing_company" id="billing_company" value="<?php if (!empty($_POST['billing_company'])) esc_attr_e($_POST['billing_company']); ?>" />
    </p>
    <div class="clear"></div>
<?php
}
add_action('woocommerce_register_form_start', 'ccrffront_end_field');
}
//Validating the input from field
add_action('woocommerce_register_post', 'ccrfvalidate_input', 3, 10);

function ccrfvalidate_input($username, $email, $validation_errors)
{
    if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {
        $validation_errors->add('billing_first_name_error', __('<strong>Error</strong>: First name is required!', 'text_domain'));
    }

    if (isset($_POST['billing_last_name']) && empty($_POST['billing_last_name'])) {
        $validation_errors->add('billing_last_name_error', __('<strong>Error</strong>: Last name is required!.', 'text_domain'));
    }
    if (isset($_POST['billing_address_1']) && empty($_POST['billing_address_1'])) {
        $validation_errors->add('billing_address_1_error', __('<strong>Error</strong>: Address is required!.', 'text_domain'));
    }
    if (isset($_POST['billing_city']) && empty($_POST['billing_city'])) {
        $validation_errors->add('billing_city_error', __('<strong>Error</strong>: City is required!.', 'text_domain'));
    }
    if (isset($_POST['billing_postcode']) && empty($_POST['billing_postcode'])) {
        $validation_errors->add('billing_postcode_error', __('<strong>Error</strong>: Postal Code is required!.', 'text_domain'));
    }
    if (isset($_POST['billing_phone']) && empty($_POST['billing_phone'])) {
        $validation_errors->add('billing_phone_error', __('<strong>Error</strong>: Phone number is required!.', 'text_domain'));
    }
    if (isset($_POST['billing_country']) && empty($_POST['billing_country'])) {
        $validation_errors->add('billing_country_error', __('<strong>Error</strong>: Country number is required!.', 'text_domain'));
    }
     
    return $validation_errors;
}

//save data
add_action('woocommerce_created_customer', 'ccrfsave_data', 1);

function ccrfsave_data($customer_id)
{
    //First name field
    if (isset($_POST['billing_first_name'])) {
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));
        update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
    }

    //First name field
    if (isset($_POST['billing_last_name'])) {
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));
        update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
    }
    //address field
    if (isset($_POST['billing_address_1'])) {
        update_user_meta($customer_id, 'Address', sanitize_text_field($_POST['billing_address_1']));
        update_user_meta($customer_id, 'billing_address_1', sanitize_text_field($_POST['billing_address_1']));
    }
    if (isset($_POST['billing_city'])) {
        update_user_meta($customer_id, 'City', sanitize_text_field($_POST['billing_city']));
        update_user_meta($customer_id, 'billing_city', sanitize_text_field($_POST['billing_city']));
    }
    if (isset($_POST['billing_postcode'])) {
        update_user_meta($customer_id, 'postcode', sanitize_text_field($_POST['billing_postcode']));
        update_user_meta($customer_id, 'billing_postcode', sanitize_text_field($_POST['billing_postcode']));
    }
    if (isset($_POST['billing_phone'])) {
        update_user_meta($customer_id, 'phone_number', sanitize_text_field($_POST['billing_phone']));
        update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
    }
    if (isset($_POST['billing_country'])) {
        update_user_meta($customer_id, 'billing country', sanitize_text_field($_POST['billing_country']));
        update_user_meta($customer_id, 'billing_country', sanitize_text_field($_POST['billing_country']));
    }
    if (isset($_POST['billing_state'])) {
        update_user_meta($customer_id, 'billing state', sanitize_text_field($_POST['billing_state']));
        update_user_meta($customer_id, 'billing_state', sanitize_text_field($_POST['billing_state']));
    }
    if (isset($_POST['billing_company'])) {
        update_user_meta($customer_id, 'billing company', sanitize_text_field($_POST['billing_company']));
        update_user_meta($customer_id, 'billing_company', sanitize_text_field($_POST['billing_company']));
    }
}



?>