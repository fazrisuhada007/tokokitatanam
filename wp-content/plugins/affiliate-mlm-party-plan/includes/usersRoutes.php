<?php 
namespace AffiliateUsers\Includes;
use AffiliateUsers\Includes\DB;
use AffiliateUsers\Includes\PluginOptions;
class UsersRoutes
{
    /*
     * Add routes
     */
    public function addRoutes()
    {
        global $wp_rewrite;
        $options = PluginOptions::getPluginOptions();
        add_rewrite_rule(
            "^({$options['prefix']})/(.+?)$",
            'index.php?page=properties&affiliate=$matches[1]&username=$matches[2]',
            'top'
        );
    }
    /*
     * Set routes
     */
    public function setRoutes()
    {
        global $wp_rewrite;
        $this->addRoutes();
        $wp_rewrite->flush_rules(false);
    }
    /*
     * Add query vars
     */
    public function addQueryVars($query_vars)
    {
        $query_vars[] = 'affiliate';
        $query_vars[] = 'username';
        $query_vars[] = 'shopname';
        return $query_vars;
    }
    /*
     * Get user
     */
    public function checkUser()
    {
        $options = PluginOptions::getPluginOptions();
        $affiliate = get_query_var('affiliate');
		$username = get_query_var('username');
		$shopname = get_query_var('shopname');
		$qs = $_SERVER['QUERY_STRING'];
		//$page = $_SERVER['REDIRECT_URL'];

		$useTracker = isset($options['use_tracker']) ? $options['use_tracker'] : '';
		$trackUrl = isset($options['use_tracker_url']) ? $options['use_tracker_url'] : '';

		if ($useTracker == 'on' && $shopname) {
			/*
			$redirect_url = $trackUrl . $shopname . '?SendBack=1';

			parse_str($qs, $params);
			unset($params["shopname"]);
			$redirect_url = $trackUrl . $shopname . '?returnUrl=' . substr($page, 1);
			
			if ($params.count > 0) {
				$redirect_url = $redirect_url . '?' . http_build_query($params);
			}*/
			$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
			$redirect_url = $trackUrl . $shopname . '?returnUrl=' . $escaped_url ;

			wp_redirect($redirect_url);
			exit;
		}

        if ($affiliate && $username && ($affiliate == $options['prefix'])) {
            $db = new DB();
            $user = $db->getUserByField('username', $username);

            if ($user) {
                sb_set_affiliate_id($user->affiliate_id);
                sb_set_shopping_with_exec($username, null);
                set_query_var('shopname', $user->name);
            } else {
                $affiliate = get_basic_affiliate_info($username);
                if ($affiliate->AffiliateId && $affiliate->AffiliateId > 0) {
                    $name = $affiliate->FirstName . ' ' . $affiliate->LastName;
                    $db->addUser(
                        array(
                            'affiliate_id' => $affiliate->AffiliateId,
                            'username' => $username,
                            'name' => $name,
                        )
                    );
                    sb_set_affiliate_id($affiliate->AffiliateId);
                    sb_set_shopping_with_exec($username, null);
                    set_query_var('shopname', $name);
                } else {
                    wp_redirect(home_url());
                    exit;
                }
            }

			if ($useTracker == 'on') {
				if(empty($qs))
					$trackerURL = $trackUrl . $username;
				else
					$trackerURL = $trackUrl . $username . '?' . $qs;
				header('Location: ' . $trackerURL);
			} else {
				sb_set_shopping_with_exec($username, false);
				header('Location: /');
			}
            exit;
        }
    }
    /*
     * Ob start
     */
    public function buffer()
    {
        ob_start();
    }
    /*
     * Insert the code after body tag
     */
    public function insertShoppingWith(){
        $buffer = ob_get_clean();
        $pattern ='/<[bB][oO][dD][yY]\s[^>]+>|<body>/';
        ob_start();
        include(AFFILIATE_USERS_DIR . 'views/shopping-with.php');
        $code = ob_get_clean();
        ob_start();
        if (preg_match($pattern, $buffer, $body)) {
            $code = $body[0] . $code;
            echo preg_replace($pattern, $code, $buffer);
        }
        ob_flush();
    }
}