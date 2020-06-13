<?php

if (!class_exists('WooCommerce')){
    require ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php';
}
function purchased_products() {
 	global $wpdb;
	$purchased_products_ids = $wpdb->get_col( $wpdb->prepare(
		"
		SELECT      itemmeta.meta_value
		FROM        " . $wpdb->prefix . "woocommerce_order_itemmeta itemmeta
		INNER JOIN  " . $wpdb->prefix . "woocommerce_order_items items
		            ON itemmeta.order_item_id = items.order_item_id
		INNER JOIN  $wpdb->posts orders
		            ON orders.ID = items.order_id
		INNER JOIN  $wpdb->postmeta ordermeta
		            ON orders.ID = ordermeta.post_id
		WHERE       itemmeta.meta_key = '_product_id'
		            AND ordermeta.meta_key = '_customer_user'
		            AND ordermeta.meta_value = %s
		ORDER BY    orders.post_date DESC
		",
		get_current_user_id()
	));
	return array_unique( $purchased_products_ids );
}

function buyer_by_product_id( $producrs = array() ) {
    global $wpdb;

    foreach ($producrs as $key => $product_id) {
        $orders = $wpdb->get_col( "
            SELECT DISTINCT woi.order_id
            FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim, 
                {$wpdb->prefix}woocommerce_order_items as woi, 
                {$wpdb->prefix}posts as p
            WHERE  woi.order_item_id = woim.order_item_id
            AND woi.order_id = p.ID
            AND woim.meta_key IN ( '_product_id', '_variation_id' )
            AND woim.meta_value LIKE '$product_id'
            ORDER BY woi.order_item_id DESC"
        );
        foreach ($orders as $key => $order_id) {
            $order = new WC_Order($order_id);
            $buyers[] = $order->customer_id;
        }
    }
    return $buyers;
}

function all_buyer_files( $buyers = array() ) {
    global $wpdb;
    if($buyers){
        $buyers = implode(',', array_unique($buyers));
    }
    
    # Get All defined statuses Orders IDs for a defined product ID (or variation ID)
    if(current_user_can('administrator')){
        $query = 'SELECT * FROM '._buddy_table;
    }else{
        $query = "SELECT * FROM "._buddy_table." WHERE `user_id` in (".$buyers.")";
    }
    $orders = $wpdb->get_results($query);
    // echo $wpdb->last_query;
    return $orders;
}
