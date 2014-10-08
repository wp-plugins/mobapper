<?php
class MOBAPPER {

    function __construct() {
        add_action('template_redirect', array(&$this, 'template_redirect'));
        $this->fetch_data = new MOBAPPER_FETCH_DATA();
    }

    function template_redirect() {
        $opt = get_option('mobapper_settings');
        $server = $_SERVER['REQUEST_URI'];
        $server = explode("/", $server);
        $count = count($server);
        $mobapp =$server[$count-2];
        $cur = $server[$count-1];
        $cur = explode('?', $cur);
        if ($mobapp == "mobapperapi") {
            if ($cur[0] == "get_categories") {
                $args = array("exclude" => $opt['exclude_categories']);
                $this->fetch_data->get_categories($args);
            }
            if ($cur[0] == "get_category_posts") {
                if (!isset($_GET['id']) && !isset($_GET['slug'])) {
                    $this->fetch_data->print_this_error('Please provide an id or slug');
                } else {
                    $this->fetch_data->get_category_posts();
                }
            }
            if ($cur[0] == "get_recent_posts") {
                $this->fetch_data->get_recent_posts();
            }
            if ($cur[0] == "get_post") {
                if (!isset($_GET['id']) && !isset($_GET['slug'])) {
                    $this->fetch_data->print_this_error('Please provide an id or slug');
                } else {
                    $this->fetch_data->get_single_post();
                }
            }
            
            if ($cur[0] == "search_posts") {
                if (!isset($_GET['search_value'])) {
                    $this->fetch_data->print_this_error('Please provide a search value');
                } else {
                    $this->fetch_data->get_search_posts();
                }
            }
            
            if ($cur[0] == "get_pages") {
                    $ex_pages = $opt['exclude_pages'];
                    $this->fetch_data->get_pages($ex_pages);
                
            }
            
            if ($cur[0] == "info") {
                    $this->fetch_data->info();
                
            }
            
        }

        if (isset($_REQUEST['mobapper'])) {

            if ($_REQUEST['mobapper'] == "get_categories") {
                $args = array("exclude" => $opt['exclude_categories']);
                $this->fetch_data->get_categories($args);
            }

            if ($_REQUEST['mobapper'] == "get_category_posts") {
                if (!isset($_GET['id']) && !isset($_GET['slug'])) {
                    $this->fetch_data->print_this_error('Please provide an id or slug');
                } else {
                    $this->fetch_data->get_category_posts();
                }
            }

            if ($_REQUEST['mobapper'] == "get_recent_posts") {
                $this->fetch_data->get_recent_posts();
            }

            if ($_REQUEST['mobapper'] == "get_post") {

                if (!isset($_GET['id']) && !isset($_GET['slug'])) {
                    $this->fetch_data->print_this_error('Please provide an id or slug');
                } else {
                    $this->fetch_data->get_single_post();
                }
            }
            
            if ($_REQUEST['mobapper'] == "search_posts") {
                if (!isset($_GET['search_value'])) {
                    $this->fetch_data->print_this_error('Please provide a search value');
                } else {
                    $this->fetch_data->get_search_posts();
               }
           }
           
           if ($_REQUEST['mobapper'] == "get_pages") {
               $ex_pages = $opt['exclude_pages'];
               $this->fetch_data->get_pages($ex_pages);
           }
           
           if ($_REQUEST['mobapper'] == "info") {
               $this->fetch_data->info();
           }
           
     }
      
    }

}

?>
