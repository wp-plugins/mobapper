<?php
/*
  Plugin Name: Mobapper
  Plugin URI: http://www.mobapper.com/
  Description: Create native mobile apps for WordPress in 3 simple steps. No coding required.
  Version: 1.0
  Author: Mobapper
  Author URI: http://www.mobapper.com
 */

$dir = dirname(__FILE__);
@include_once "$dir/classes/mobapper.php";
@include_once "$dir/classes/jsonbuilder.php";
@include_once "$dir/classes/fetchdata.php";

function mobapper_init() {
    global $mobapper;
    add_filter('rewrite_rules_array', 'mobapper_rewrites');
    $mobapper = new MOBAPPER();
}

function mobapper_activation() {
    global $wp_rewrite;
    add_filter('rewrite_rules_array', 'moabapper_rewrites');
    $wp_rewrite->flush_rules();
}

function mobapper_deactivation() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

function mobapper_rewrites($wp_rules) {
    $base = get_option('mobapper_base', 'mobapper');
    if (empty($base)) {
        return $wp_rules;
    }
    $mobapper_rules = array(
        "$base\$" => 'index.php?mobapper=info',
        "$base/(.+)\$" => 'index.php?mobapper=$matches[1]'
    );
    return array_merge($mobapper_rules, $wp_rules);
}

function mobapper_admin_menu() {
    add_menu_page('Mobapper', 'Mobapper', 'administrator', basename(__FILE__), 'mobapper_admin');
}

function fetch_sub_cat_list($child2) {

    echo'<li>
          <label class="selectit">
           <input value="' . $child2->term_id . '" type="checkbox" name="post_category[]" id="in-category-' . $child2->term_id . '"> ' . $child2->name . '
          </label>';
    echo'</li>';
}

function mobapper_admin() {
    ?>

    <?php
    global $options;
    $category_values = $options['exclude_categories'];
    $cat_values_array = explode(",", $category_values);
    ?>
    <div class="wrap nosubsub">
        <h2>Mobapper Dashboard</h2>

        <div id="ajax-response"></div>


<!--        <br class="clear">-->

        <div id="col-container">

            <div id="col-left">
                <div class="col-wrap">
                    <?php
                    if (isset($_POST['submit_category'])) {
                        $values = "";
                        $cats = $_POST['post_category'];
                        $str = implode(", ", $cats);
                        $options = array('exclude_categories' => $str);
                        $category_values = $options['exclude_categories'];
                        $cat_values_array = explode(",", $category_values);
                        update_option("mobapper_settings", $options);
                        echo '<div class="alert alert-warning">
                                <strong>Mobapper settings saved</strong> 
                              </div>';
                    }
                    ?>
                    
                    <h3>Select categories to exclude from mobile apps</h3>
                    <form id="category-exclude" action="" method="post" name="categoryexcludeform">
                            <table class="wp-list-table widefat fixed tags">
                            <thead>
                               
                                <tr>
<!--                                    <th scope="col" id="name" class="manage-column column-name sortable desc" style="">
                            <h4>&nbsp;&nbsp;ID</h4>
                            </th>-->
                            <th scope="col" id="name" class="manage-column column-name sortable desc" style="width:50%">
                            <h4>&nbspCategory</h4>
                            </th>
                            <th scope="col" id="name" class="manage-column column-name sortable desc" style="width:50%">
                            <h4>Exclude</h4> 
                            </th>

                            </tr>
                            </thead>
                            <tbody id="the-list" data-wp-lists="list:tag">
                                <?php
                                $categories = get_categories();
                                $c = 0;
                                foreach ($categories as $category) {
                                    $c++;
                                    if ($category->term_id == 1 && $category->slug == 'uncategorized') {
                                        $c--;
                                        continue;
                                    }
                                    if ($c % 2 == 0) {
                                        $num = "alternate";
                                    } else {
                                        $num = "";
                                    }

                                    if (in_array($category->term_id, $cat_values_array)) {
                                        $checked = "checked";
                                    } else {
                                        $checked = "";
                                    }
                                    echo'<tr id="tag-2" class="' . $num . '">
                                      <td class="name column-name">
                                      <strong>
                                       ' . $category->name . '
                                      </strong>  
                                    </td>
                                    <td class="check-column">
                                       <input value="' . $category->term_id . '" type="checkbox" name="post_category[]" id="in-category-' . $category->term_id . '" ' . $checked . '/>
                                    </td>
                                  </tr>';
                                }
                                ?>
                                <tr class="alternate">
                                    <td></td>
                                    <td>
                                        <input type="submit" name="submit_category" value="Save" style="float: right" class="button button-primary button-large" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <br class="clear">
                    </form>

                </div>
            </div>
        </div>
    </div>
    <?php
}

$default = array('exclude_categories' => '0');
if (!is_array(get_option('mobapper_settings'))) {
    add_option('mobapper_settings', $default);
}

$options = get_option('mobapper_settings');
add_action('init', 'mobapper_init');
add_action('admin_menu', 'mobapper_admin_menu');
register_activation_hook("$dir/mobapper.php", 'mobapper_activation');
register_deactivation_hook("$dir/mobapper.php", 'mobapper_deactivation');
?>
