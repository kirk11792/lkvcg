<?php

global $woocommerce;


/*
* Check if Woocommerce plugin is enabled.
*/
function pacz_woocommerce_enabled() {
	if ( class_exists( 'woocommerce' ) ) { return true; }
	return false;
}

if ( !pacz_woocommerce_enabled() ) { return false; }
/******************/



require_once (PACZ_THEME_PLUGINS_CONFIG . "/woocommerce-quantity-increment/woocommerce-quantity-increment.php");


/*
* Declares support to woocommerce
*/
add_theme_support( 'woocommerce' );
/******************/




/*
* Overrides woocommerce styles and scripts modified and created by theme
*/
if(!function_exists('pacz_woocommerce_assets')) {
function pacz_woocommerce_assets() {
	$theme_data = wp_get_theme("classiadspro");
	wp_enqueue_style( 'pacz-woocommerce', PACZ_THEME_STYLES.'/pacz-woocommerce.css', false, $theme_data['Version'], 'all'  );
}
}

add_filter( 'woocommerce_enqueue_styles', 'pacz_woocommerce_assets' );
/******************/






/*
Adds Woocommerce Payment process in cart, checkout and order recieved page
*/

if(!function_exists('pacz_woocommerce_cart_process_steps')) {
	function pacz_woocommerce_cart_process_steps() {

		$cart = $checkout = $complete = '';

		if(is_cart() || is_checkout() || is_order_received_page()) {


		if(is_cart()) {
			$cart = 'active';
		}

		if(is_checkout()) {
			$checkout = 'active';
			$cart = 'active';	
		}
		if(is_order_received_page()) {
			$checkout = 'active';
			$cart = 'active';	
			$complete = 'active';		
		}

		?>

		<div class="woocommerce-process-steps">
			<ul>
				<li class="<?php echo esc_attr($cart); ?>">
					<i class="pacz-icon-close"></i>
					<span><?php esc_html_e('SHOPPING CART', 'classiadspro'); ?></span>
					
				</li>
				<li class="<?php echo esc_attr($checkout); ?>">
					<i class="pacz-icon-close"></i>
					<span><?php esc_html_e('PROCEED TO CHECKOUT', 'classiadspro'); ?></span>
					
				</li>
				<li class="<?php echo esc_attr($complete); ?>">
					<i class="pacz-icon-close"></i>
					<span><?php esc_html_e('SUBMIT ORDER', 'classiadspro'); ?></span>
					
				</li>
			</ul>
		</div>

		<?php

			}	
	}
}
//add_action('woocommerce_before_cart', 'pacz_woocommerce_cart_process_steps');
//add_action('woocommerce_before_checkout_form', 'pacz_woocommerce_cart_process_steps');

/******************/


/*
* Removes woocommerce defaults
*/
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_pagination', 'woocommerce_catalog_ordering', 20 );
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_before_single_product', array( $woocommerce, 'show_messages' ), 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
//remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_related_products', 20);
function custom_my_account_menu_items( $items ) {
   // unset($items['downloads']);
	//unset($items['orders']);
	//unset($items['edit-address']);
	//unset($items['edit-account']);
	//unset($items['customer-logout']);
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'custom_my_account_menu_items' );

remove_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_excerpt', 20 );
//remove_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_rating');
//remove_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_add_to_cart', 30 );
//remove_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_meta', 40 );

add_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_price', 5 );
add_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_title', 7 );
add_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_excerpt', 8 );
//add_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_rating', 6 );



//add_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_add_to_cart', 30 );
//add_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_meta', 40 );

remove_action ('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action ('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action( 'woocommerce_before_shop_loop_item_title',  'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'pacz_woocommerce_loop_thumbnail', 10 );

function pacz_woocommerce_loop_thumbnail() {
	global $product, $pacz_settings;
	$column = wc_get_loop_prop( 'columns' );
	$grid_width = $pacz_settings['grid-width'];
	$grid_margin = ($column * 30) -30;
    $margin_adjust = $grid_margin/$column;           
    $width = ($grid_width/$column) -$margin_adjust;
    $height = $pacz_settings['woo-loop-thumb-height'];     
	$loop_image_size = isset($pacz_settings['woo_loop_image_size']) ? $pacz_settings['woo_loop_image_size'] : 'crop';
    $quality = $pacz_settings['woo-image-quality'] ? $pacz_settings['woo-image-quality'] : 1;
	//if ( has_post_thumbnail() ) {
            switch ($loop_image_size) {
                case 'full':
                    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
                    $image_output_src = $image_src_array[0];
                    break;
                case 'crop':
                    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
                    $image_output_src = bfi_thumb($image_src_array[0], array(
                        'width' => $width*$quality,
                        'height' => $height*$quality,
						'crop' => true
                    ));
                    break;            
                case 'large':
                    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true);
                    $image_output_src = $image_src_array[0];
                    break;
                case 'medium':
                    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium', true);
                    $image_output_src = $image_src_array[0];
                    break;        
                default:
                    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
                    $image_output_src = bfi_thumb($image_src_array[0], array(
                        'width' => $width*$quality,
                        'height' => $height*$quality
                    ));
                    break;
            }
	do_action('woocommerce_template_loop_product_link_close');
	echo '<div class="pacz-product-thumbnail">';
		pacz_love_this(false);
		echo '<img src="'.pacz_thumbnail_image_gen($image_output_src, $width, $height).'" class="product-loop-image" alt="'.get_the_title().'" title="'.get_the_title().'" itemprop="image" />';
		//echo get_the_post_thumbnail( $product->get_id(), array( 370, 270) );
	echo '</div>';
}
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'pacz_woocommerce_loop_product_title', 10 );
function pacz_woocommerce_loop_product_title() {
	global $product;
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	echo '<div class="pacz-product-content">';
		echo '<div class="pacz-product-terms">';
			foreach($terms AS $term){
			echo esc_html($term->name);
		}
		echo '</div>';
		echo '<div class="pacz-product-title">';
			echo '<h2 class="pacz-loop-product_title"><a href="'.get_the_permalink().'">' . get_the_title() . '</a></h2>';
		echo '</div>';
		echo '<div class="product_bottom clearfix">';
			echo '<div class="product_bottom_left clearfix">';
				if ( $rating_html = wc_get_rating_html( $product->get_average_rating() ) ) {
					echo '<div class="product-item-rating">'.$rating_html.'</div>';
				}else{
					
					echo '<span class="product-item-rating"><span class="star-rating"></span></span>';
				}
				woocommerce_template_loop_price();
			echo '</div>';
			echo '<div class="product_bottom_right clearfix">';
				woocommerce_template_loop_add_to_cart();
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

add_filter('loop_shop_columns', 'pacz_product_loop_columns', 999);
if (!function_exists('pacz_product_loop_columns')) {
	function pacz_product_loop_columns() {
		global $pacz_settings;
		$columns = (isset($pacz_settings['woo-shop-columns']))? $pacz_settings['woo-shop-columns']: 3;
		return $columns;
	}
}

add_filter('woocommerce_reviews_title', 'pacz_product_review_tab_title', 10);
if (!function_exists('pacz_product_review_tab_title')) {
	function pacz_product_review_tab_title() {
		echo esc_html__( 'Customer Reviews', 'classiadspro' );
		woocommerce_template_single_rating();
	}
}
//add_filter('woocommerce_after_single_product_summary', 'pacz_product_after_single_summary_wrapper', 10);
if (!function_exists('pacz_product_after_single_summary_wrapper')) {
	function pacz_product_after_single_summary_wrapper() {
		echo '</div>';
	}
}
/******************/




/*
* Create theme global HTML wrappers.
*/
add_action( 'woocommerce_before_main_content', 'pacz_woocommerce_output_content_wrapper', 10 );
add_action( 'woocommerce_after_main_content', 'pacz_woocommerce_output_content_wrapper_end', 10 );


function pacz_woocommerce_output_content_wrapper() {
	global $post, $pacz_settings;


	if ( is_page() ) {
		$layout = get_post_meta( $post->ID, '_layout', true );
	} else if(is_single()) {
		$layout = $pacz_settings['woo-single-layout'];
	} else {
		$layout = $pacz_settings['woo-shop-layout'];
	}


?>
<div id="theme-page">

  	<div class="background-img background-img--page"></div>
  	
	<div class="pacz-main-wrapper-holder">
		<div class="theme-page-wrapper pacz-main-wrapper <?php echo esc_attr($layout); ?>-layout pacz-grid vc_row-fluid">
		<div class="inner-page-wrapper">
			<div class="theme-content">
			<div class="theme-content-inner">
<?php
}


function pacz_woocommerce_output_content_wrapper_end() {
	global $post, $pacz_settings;

	if ( is_page() ) {
		$layout = get_post_meta( $post->ID, '_layout', true );
	} else if(is_single()) {
		$layout = $pacz_settings['woo-single-layout'];
	} else {
		$layout = $pacz_settings['woo-shop-layout'];
	}


?>
		</div>
		</div>
		<?php if ( $layout != 'full' ) get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
	</div>
	</div>
</div>

<?php
}

/******************/





/*
* Add woommerce share buttons
*/

//add_action( 'woocommerce_share', 'pacz_woocommerce_share' );

function pacz_woocommerce_share() {
	global $post;
	$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );

	$output = '<div class="woocommerce-share"><ul class="single-social-share">';
		$output .= '<li><a class="facebook-share" data-title="'.get_the_title().'" data-url="'.get_permalink().'" href="#"><i class="pacz-icon-facebook"></i></a></li>';
		$output .= '<li><a class="twitter-share" data-title="'.get_the_title().'" data-url="'.get_permalink().'" href="#"><i class="pacz-icon-twitter"></i></a></li>';
		$output .= '<li><a class="googleplus-share" data-title="'.get_the_title().'" data-url="'.get_permalink().'" href="#"><i class="pacz-icon-google-plus"></i></a></li>';
		$output .= '<li><a class="linkedin-share" data-title="'. get_the_title() .'" data-url="'.get_permalink().'" href="#"><i class="pacz-icon-linkedin"></i></a></li>';
		$output .= '<li><a class="pinterest-share" data-image="'.$image_src_array[0].'" data-title="'.get_the_title().'" data-url="'.get_permalink().'" href="#"><i class="pacz-icon-pinterest"></i></a></li>';
	$output .= '</ul><div class="clearboth"></div></div>';

	echo '<div>'.$output.'</div>';

}

add_action( 'woocommerce_view_demo', 'pacz_woocommerce_view_demo' );

function pacz_woocommerce_view_demo() {
	
	/**
 * Create Custom Meta Boxes for WooCommerce Product CPT
 *
 * Using Custom Metaboxes and Fields for WordPress library from 
 * Andrew Norcross, Jared Atchinson, and Bill Erickson
 *
 * @link http://blackhillswebworks.com/?p=5453
 * @link https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
 */		
 
	add_filter( 'cmb_meta_boxes', 'pacz_woo_metaboxes' );
 
	function pacz_woo_metaboxes( $meta_boxes ) {
	
		//global $prefix;
		$prefix = '_pacz_'; // Prefix for all fields
		
		// Add metaboxes to the 'Product' CPT
		$meta_boxes[] = array(
			'id'         => 'pacz_woo_metabox',
			'title'      => 'demo url',
			'pages'      => array( 'product' ), // Which post type to associate with?
			'context'    => 'normal',
			'priority'   => 'default',
			'show_names' => true, 					
			'fields'     => array(
				array(
					'name'    => __( 'url', 'classiadspro' ),
					'desc'    => __( 'demo url', 'classiadspro' ),
					'id'      => $prefix . 'pacz_demo_url',
					'type'    => 'textfield',
				),
			),
		);
 
		return $meta_boxes;
		
}
?>
<div class="demo-button">
	<a href="">View Demo</a>
</div>
<?php
}

/*
* Updates Header Shopping cart fragment
*/
add_filter('woocommerce_add_to_cart_fragments', 'pacz_header_add_to_cart_fragment');
if ( ! function_exists( 'pacz_header_add_to_cart_fragment' ) ) { 
    function pacz_header_add_to_cart_fragment( $fragments ) {
        ob_start();
			echo '<span class="pacz-header-cart-count">'. esc_html(WC()->cart->cart_contents_count) .'</span>';
        $fragments['.pacz-header-cart-count'] = ob_get_clean();        
        return $fragments;
    }
}





/*
* Header Checkout box.
*/
if ( !function_exists( 'pacz_header_checkout' ) ) {
function pacz_header_checkout($location) {

	global $woocommerce, $pacz_settings;

	if ( !$woocommerce || is_cart() || is_checkout() ) { return false; }

		if($pacz_settings['checkout-box']){
			echo '<li class="pacz-shopping-cart '. $pacz_settings['checkout-box-align'] .'">';
				echo '<a href="'. esc_url(wc_get_cart_url()) .'" class="pacz-cart-link">';
					echo '<i class="pacz-flaticon-shopping63"></i>';
					echo '<span class="pacz-header-cart-count">'. esc_html(WC()->cart->cart_contents_count) .'</span>';
				echo '</a>';
			echo '</li>';
		}
	}
}

add_action( 'header_checkout', 'pacz_header_checkout' );
/***************************************/

/*
* Header Checkout box.
*/
if ( !function_exists( 'pacz_cart_panel_function' ) ) {
function pacz_cart_panel_function($location) {

	global $woocommerce, $pacz_settings;

	if ( !$woocommerce || is_cart() || is_checkout() ) { return false; }

		if($pacz_settings['checkout-box']) :

		?>
	<?php /* Shopping Box, the content will be updated via ajax, you can edit @pacz_header_add_to_cart_fragment() */ ?>
	<div class="pacz-shopping-box-wrapper">
	<div class="pacz-shopping-box">
		<div class="shopping-box-header"><span><span class="pacz-skin-color"><i class="pacz-icon-shopping-cart"></i><?php echo WC()->cart->cart_contents_count; ?> <?php esc_html_e('Items', 'classiadspro'); ?></span> <?php esc_html_e('In your Shopping Bag', 'classiadspro'); ?></span></div>
			<?php if (WC()->cart->cart_contents_count == 0) {
				echo '<p class="empty">'.esc_html__('No products in the cart.','classiadspro').'</p>';
			?>
			<?php } ?>
	</div>
	</div>
	<?php /***********/ ?>

	<?php /* Shopping Box, the content will be updated via ajax, you can edit @pacz_header_add_to_cart_fragment() */ ?>
	<div class="pacz-shopping-box-mobile-wrapper">
	<div class="pacz-shopping-box">
		<div class="shopping-box-header"><span><span class="pacz-skin-color"><i class="pacz-icon-shopping-cart"></i><?php echo WC()->cart->cart_contents_count; ?> <?php esc_html_e('Items', 'classiadspro'); ?></span> <?php esc_html_e('In your Shopping Bag', 'classiadspro'); ?></span></div>
			<?php if (WC()->cart->cart_contents_count == 0) {
				echo '<p class="empty">'.esc_html__('No products in the cart.','classiadspro').'</p>';
			?>
			<?php } ?>
	</div>
	</div>
	<?php /***********/ ?>

		<?php 
		endif;	
	}
}

//add_action( 'wp_footer', 'pacz_cart_panel_function' );

remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );

add_action( 'woocommerce_proceed_to_checkout', 'pacz_woocommerce_button_proceed_to_checkout', 20 );

if ( ! function_exists( 'pacz_woocommerce_button_proceed_to_checkout' ) ) {

	/**
	 * Output the proceed to checkout button.
	 *
	 * @subpackage	Cart
	 */
	function pacz_woocommerce_button_proceed_to_checkout() {
		$checkout_url = wc_get_checkout_url();

		?>
		<div class="button-icon-holder alt checkout-button-holder"><a href="<?php echo esc_url($checkout_url); ?>" class="checkout-button"><i class="pacz-icon-shopping-cart"></i><?php esc_html_e( 'Proceed to Checkout', 'classiadspro' ); ?></a></div>
		<?php
	}
}



function pacz_woocommerce_pagination($pages = '', $range = 2)
{  
	 ob_start();
     $showitems = ($range * 2)+1;  
     global $paged;
     if(empty($paged)) $paged = 1;
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
     if(1 != $pages)
     {
         echo "<ul>";
         if($paged > 1 && $showitems < $pages) echo "<li><a class='page-numbers prev' href='".get_pagenum_link($paged - 1)."'><i class='pacz-theme-icon-prev-big'></i></a></li>";
         for ($i=1; $i <= $pages; $i++)
         {
              if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                if($paged == $i){
					echo "<li><span class='page-numbers current'>".$i."</span></li>";
				 }else{
					 echo "<li><a class='page-numbers' href='".get_pagenum_link($i)."' >".$i."</a></li>";
				 }
             }
         }
         if ($paged < $pages && $showitems < $pages) echo "<li><a class='page-numbers next' href='".get_pagenum_link($paged + 1)."'><i class='pacz-theme-icon-next-big'></i></a></li>"; 
         echo "</ul>\n";
     }
	 
	 return ob_get_clean();
}
