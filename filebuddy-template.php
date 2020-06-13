<?php
    /**
     * The template for displaying files for buddyfile
     */
    require_once(dirname( __FILE__ ) . '/buddy-functions.php');
    
    $upload_dir = wp_upload_dir()['baseurl'].'/filebuddy';
    $products = purchased_products();
    // print_r($products);
    $buyers = buyer_by_product_id($products);
    $buyer_files = all_buyer_files($buyers);

    #if no bought product & not admin
    if( ( (!is_user_logged_in()) || (!$products) ) && (!current_user_can('administrator')) ){
        wp_redirect(get_home_url());
    }
    
    get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
            <div class="page-content">
                
                <div>
                    <button id="filebuddy_uploader" data-upload="<?= get_home_url()."/wp-json/v1/upload";?>" type="button" class="file_upload btn btn-primary">Upload File..</button>
                </div>
                <ul class="file_list">
                <?php
                    // print_r($buyer_files);
                    if($buyer_files):
                        foreach ($buyer_files as $file):
                ?>
                <li>
                        <video width="320" height="240" controls>
                            <source src="<?= $file->file_path; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        
                    <a class="btn btn-download" href="<?= $file->file_path; ?>"> Download </a>
                </li>
                <?php 
                        endforeach;
                    endif;
                ?>
                </ul>
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->
    
<?php get_footer();?>