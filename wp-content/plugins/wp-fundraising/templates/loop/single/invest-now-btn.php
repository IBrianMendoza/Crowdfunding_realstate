<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $post, $woocommerce, $product;
$currency = '$';
if ($product->get_type() == 'wp_fundraising') {
    if(is_campaign_valid($post->ID)){
        $recomanded_price = get_post_meta($post->ID, '_wf_funding_recommended_price', true);
        $min_price = get_post_meta($post->ID, '_wf_funding_minimum_price', true);
        $max_price = get_post_meta($post->ID, '_wf_funding_maximum_price', true);
        $porconstruccion     = get_post_meta( $post->ID, '_wf_porconstruccion', true );
        $plazo               = get_post_meta( $post->ID, '_wf_plazo', true );
        $rendimiento         = get_post_meta( $post->ID, '_wf_rendimiento', true );
        $garantia            = get_post_meta( $post->ID, '_wf_garantia', true );
        $porcentaje          = get_post_meta( $post->ID, '_wf_porcentaje', true );
    ?>

        <form enctype="multipart/form-data" method="post" class="cart">
            <div class="xs-single-sidebar">
                <div class="xs-btn-wraper">
                    <?php echo get_woocommerce_currency_symbol(); ?>
                    <input type="number" step="50000" placeholder="<?php esc_attr_e('Amount','wp-fundraising');?>" name="wp_donate_amount_field" class="input-text amount wp_donate_amount_field text" value="<?php echo $recomanded_price; ?>" value="<?php echo number_format($recomanded_price,wc_get_price_decimals(),wc_get_price_decimal_separator(), wc_get_price_thousand_separator()); ?>"  min="<?php echo $min_price ?>" max="<?php echo $max_price ?>" >
                    <?php do_action('after_wf_donate_field'); ?>
                    <input type="hidden" value="<?php echo esc_attr($post->ID); ?>" name="add-to-cart">
                    <button type="submit" style="margin-top: 10px" class="icon-btn xs-btn radius-btn green-btn xs-btn-medium <?php echo apply_filters('add_to_donate_button_class', 'wp_donate_button'); ?>"><?php echo wf_single_invest_now_button_text(); ?></button>
                </div>
                <div class="fundpress-item-content" style="color: black;">
                <div id="contenedor1">
                    <div class="div-alaizquierda"> 
                        <?php echo "Porcentaje solicitado: "; ?>
                    </div>
                    <div class="div-aladerecha" style="color: black; font-size: 16px; font-weight: bold">
                        <?php echo $porcentaje; echo "%" ?>
                    </div>
                </div>
                <div id="contenedor2">
                    <div class="div-alaizquierda1">
                        <?php echo "Garantía en meses: " ?>
                    </div>
                    <div class="div-aladerecha1" style="color: green; font-size: 16px; font-weight: bold;">
                        <?php echo $garantia; echo " meses"; ?>
                    </div>
                </div>
                <div id="contenedor3">
                    <div class="div-alaizquierda">
                        <?php echo "Rendimiento obtenido: ";?>
                    </div>
                    <div class="div-aladerecha" style="color: #4CC899; font-size: 16px; font-weight: bold;">
                        <?php echo $rendimiento; echo "%"; ?>
                </div>
                </div>
                <div id="contenedor4">
                    <div class="div-alaizquierda">
                        <?php echo "Plazo estimado: ";?>
                    </div>
                    <div class="div-aladerecha">
                        <?php echo $plazo; echo " meses"; ?>
                    </div>
                </div>
                <div id="contenedor5">
                    <div class="div-alaizquierda">
                        <?php echo "Porcentaje de construcción: "; ?>
                    </div>
                    <div class="div-aladerecha">
                        <?php echo $porconstruccion; echo "%"; ?>
                    </div>
                </div>
                <div id="contenedor8">
                    <div class="div-alaizquierda">
                        <?php echo "Inversión minima: ";?>
                    </div>
                    <div class="div-aladerecha">
                        <?php echo"$";echo $min_price; ?>
                    </div>
                </div>
                <ul class="xs-list-with-content fundpress-list-item-content" >
                    <?php if ($show_end_date == 'off') { ?>
                        <?php if ($days_remaining) { ?>
                            <li>
                                <?php echo $days_remaining; ?>
                                    <span>
                                        <?php echo wf_archive_days_remaining_text(); ?>
                                    </span>
                            </li>
                            <?php } ?>
                    <?php } ?> 
                </ul>
                </div>
            </div>
        </form>

    <?php }else{
        echo wf_single_expired_text();
    }

}elseif ($product->get_type() == 'wf_donation') { 
    ?>
    <form enctype="multipart/form-data" method="post" class="cart xs-donation-form" >
        <div class="xs-input-group">
            <label for="xs-donate-name"><?php esc_html_e('Donation Amount ','wp-fundraising');?><span class="color-light-red">**</span></label>
            <input type="text" name="wp_donate_amount_field" id="xs-donate-name" class="form-control" placeholder="<?php esc_attr_e('Custom Amount','wp-fundraising');?>">
        </div>
        <?php
        $donation_level_fields = get_post_meta($post->ID, 'repeatable_donation_level_fields', true);
        ?>
        <?php if ( $donation_level_fields ) : ?>
            <div class="xs-input-group">
                <label for="xs-donate-charity"><?php esc_html_e('List of Donation Level ','wp-fundraising');?><span class="color-light-red" >**</span></label>
                <select id="xs-donate-charity" class="form-control">
                    <option value=""><?php esc_html_e('Select Amount','wp-fundraising');?></option>
                    <?php foreach ( $donation_level_fields as $field ) { ?>
                        <option value="<?php echo esc_attr( $field['_wf_donation_level_amount'] ); ?>"><?php echo wf_price(esc_attr( $field['_wf_donation_level_amount'] )); ?></option>
                    <?php } ?>
                    <option value="custom"><?php esc_html_e('Give a Custom Amount','wp-fundraising');?></option>
                </select>
            </div>
        <?php endif; ?>
        <?php do_action('after_wf_donate_field'); ?>
        <input type="hidden" value="<?php echo esc_attr($post->ID); ?>" name="add-to-cart">
        <button type="submit" class="btn btn-primary"><span class="badge"><i class="fa fa-heart"></i></span> <?php echo wf_donate_now_button_text(); ?></button>
    </form>
    <?php 
}