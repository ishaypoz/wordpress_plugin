<?php
/**
 * Plugin Name: WooCommerce Orders
 * Description: Sends an API request for each order to an endpoint with data as json object.
 * Developer: Ishay.
 */

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;
//Should i split the files apart admin and function?
function woocommerce_send_orders(){
    add_menu_page('API request ','API request','manage_options','menu_slug','woocommerce_orders_data','https://i.ibb.co/DzTWGsC/api-3.png',200);
}
add_action('admin_menu','woocommerce_send_orders');

function woocommerce_orders_data(){
    $result ='';
    //Should use ajax to not stuck the page?
    if(isset($_POST['submit_url']))
    {
        $url = $_POST['url_end_point'];
        //Should i divide the order object to little object like 5 each time?
        $orders = wc_get_orders( array('numberposts' => -1) );
        foreach($orders as $order) {
            
            $coupons_code = null;
            $coupons_quantity = 0;
            foreach( $order->get_used_coupons() as $coupon_code ){
                array_push($coupons_code, $coupon_code->code);
                $coupons_quantity += 1;
            }
            $coupons = array( "copuns_code" => $coupons_code, "coupons_quantity" => $coupons_quantity );
            
            $order_amount = 0;
            $order_products = array();
            foreach( $order->get_items() as $order_item ){
                $product_details = array(
                    "product_title" => $order_item->get_product()->name,
                    "id" => $order_item->get_product()->id,
                    "total" => $order_item->get_total()
                );
                array_push($order_products, $product_details);
                //didnt want to use SQL query its take to long get to long to get data from wp_wc_order_stats and didnt find any other function that's count all the items.
                $order_amount += $order_item->get_quantity();
            }
            
            $data = array(
                "billing_details" => array(
                    "first_name" => $order->get_billing_first_name(),
                    "last_name"  => $order->get_billing_last_name(),
                    "company"    => $order->get_billing_company(),
                    "address_1"  => $order->get_billing_address_1(),
                    "address_2"  => $order->get_billing_address_2(),
                    "city"       => $order->get_billing_city(),
                    "state"      => $order->get_billing_state(),
                    "postcode"   => $order->get_billing_postcode(),
                    "country"    => $order->get_billing_country()
                    ),
                "order_currency" => $order->get_currency(),
                "order_amount" => $order_amount,
                "order_products" => $order_products,
                "payment_method" => $order->get_payment_method(),
                "transaction_id" => $order->get_transaction_id(),
                "paid_date" => $order->get_date_paid()->date,
                "coupon_code" => $coupons
            ); 
            $data_string = json_encode($data);
            $ch = curl_init($url);                                                                      
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string))                                                                       
            );
            $result = curl_exec($ch);
        }
        //Should i check each Post if result that api return are 2XX? and cut the session if it is not 2XX instead of keep sending?
        ?>
        <div id="setting-error-settings-updated" class="updated settings_error notice is-dismissible"><strong>Request Sent.<hr>
        Results are:
        <p><?php echo $result ?></p></strong></div>
        <?php
    }
    ?>
        <div class="wrap"> 
        <h2>Sends an API request to URL</h2>
        <form method="post" action="">
        <input type="text" name="url_end_point" class="form-control col-md-9 m-3" placeholder="Insert valit url end point" required />
        <input type="submit" name="submit_url" class="button button-primary" value="Send">
        </form>
        </div>	
    <?php
}
?>



