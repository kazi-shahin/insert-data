<?php
if(isset($_POST['custom_plugin'])&&$_POST['custom_plugin']=='custom_plugin'){
    print_r($_POST);
    exit();
}


/** Step 2 (from text above). */
add_action('admin_menu', 'my_plugin_menu');


/** Step 1. */
function my_plugin_menu()
{
    add_options_page('Configure Schedule', 'My Plugin', 'manage_options', 'configure-schedule', 'my_plugin_options');
}

/** Step 3. */
function my_plugin_options()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    include 'templates/index.php';
}



function plugin_setting_string() {
    $options = get_option('plugin_options');
    echo "<input id='plugin_text_string' name='plugin_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}
