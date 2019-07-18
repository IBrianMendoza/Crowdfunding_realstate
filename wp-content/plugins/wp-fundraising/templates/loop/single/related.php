<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$paged = 1;
if (get_query_var('paged')) {
    $paged = absint(get_query_var('paged'));
} elseif (get_query_var('page')) {
    $paged = absint(get_query_var('page'));
}
global $post;
$query_args = array(
    'post_type' => 'product',
    'post__not_in' => array(get_the_ID()),
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'product_type',
            'field' => 'slug',
            'terms' => 'wp_fundraising',
        ),
    ),
    'posts_per_page' => 3,
    'paged' => $paged
);

ob_start();
query_posts($query_args);
if (have_posts()): ?> 
    <section class="waypoint-tigger xs-gray-bg xs-section-padding">
        <div class="container">
            <div class="xs-section-heading row xs-margin-0">
                <div class="fundpress-heading-title xs-padding-0 col-xl-9 col-md-9">
                    <?php echo wf_single_related_campaign_title();?>
                    <?php echo wf_single_related_campaign_description();?>
                </div>
            </div>
            <div class="row">
                <?php while (have_posts()) : the_post();

                    $funding_goal   = wf_get_total_goal_by_campaign(get_the_ID());
                    $fund_raised_percent   = wf_get_fund_raised_percent(get_the_ID());
                    $image_link = wp_get_attachment_url(get_post_thumbnail_id());
                    $postId = get_the_id();
                    $funding_goal   = wf_get_total_goal_by_campaign($postId);
                    $raised_percent   = wf_get_fund_raised_percent($postId);
                    $fund_raised_percent   = wf_get_fund_raised_percentFormat($postId);
                    $image_link = wp_get_attachment_url(get_post_thumbnail_id());
                    $backers_count = wf_backers_count($postId);
                    $wp_country  = get_post_meta( $postId, '_wf_country', true);
                    $total_sales    = get_post_meta( $postId, 'total_sales', true );
                    $enddate        = get_post_meta( $postId, '_wf_duration_end', true );
                    $show_end_date = wf_get_option('_wf_hide_campaign_expiry_from_listing', 'wf_basics');

                    //Custom Fields
                    $recomanded_price = get_post_meta($postId, '_wf_funding_recommended_price', true);//$post->ID
                    $min_price = get_post_meta($postId, '_wf_funding_minimum_price', true);
                    $max_price = get_post_meta($postId, '_wf_funding_maximum_price', true); //$post->ID
                    $porconstruccion     = get_post_meta( $postId, '_wf_porconstruccion', true );
                    $plazo               = get_post_meta( $postId, '_wf_plazo', true );
                    $rendimiento         = get_post_meta( $postId, '_wf_rendimiento', true );
                    $garantia            = get_post_meta( $postId, '_wf_garantia', true );
                    $porcentaje          = get_post_meta( $postId, '_wf_porcentaje', true );

                    $raised = 0;
                    $total_raised = wf_get_total_fund_raised_by_campaign(get_the_ID());

                    if ($total_raised){
                        $raised = $total_raised;
                    }

                    //Get order sales value by product

                    $days_remaining = apply_filters('date_expired_msg', esc_html__('El proyecto Finalizó', 'wp-fundraising'));
                    if (wf_date_remaining(get_the_ID())){
                        $days_remaining = apply_filters('date_remaining_msg', esc_html__(wf_date_remaining(get_the_ID()), 'wp-fundraising'));
                    }



                    ?>
                    <!-- <div class="col-lg-4">
                        <div class="xs-box-shadow fundpress-popular-item xs-bg-white">
                            <div class="fundpress-item-header">
                                <img src="<?php echo $image_link; ?>" alt="">
                                <div class="xs-item-header-content">
                                    <div class="xs-skill-bar">
                                        <div class="xs-skill-track">
                                            <p><span class="number-percentage-count number-percentage" data-value="<?php echo $fund_raised_percent; ?>" data-animation-duration="3500">0</span>%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fundpress-item-content xs-content-padding bg-color-white">
                                <?php
                                $categories = get_the_terms( get_the_ID(), 'product_cat' );
                                ?>
                                <ul class="xs-simple-tag fundpress-simple-tag">
                                    <?php
                                    foreach($categories as $category){
                                        ?><li><a href="<?php echo get_category_link($category->term_id);?>"><?php echo $category->name; ?></a></li><?php
                                    }
                                    ?>
                                </ul>
                                <a href="<?php the_permalink();?>" class="d-block color-navy-blue fundpress-post-title"><?php the_title();?></a>
                                <ul class="xs-list-with-content fundpress-list-item-content">
                                    <?php if ($funding_goal) { ?>
                                        <li><?php echo wc_price($funding_goal); ?><span><?php echo wf_archive_fund_goal_text(); ?></span></li>
                                    <?php } ?>

                                    <?php if ($raised) { ?>
                                        <li><span class="number-percentage-count"><?php echo wc_price($raised); ?></span><span><?php echo wf_archive_fund_raised_text(); ?></span></li>
                                    <?php } ?>

                                    <?php if ($days_remaining) { ?>
                                        <li><?php echo $days_remaining; ?><span><?php echo wf_archive_days_remaining_text(); ?></span></li>
                                    <?php } ?>
                                </ul>
                                <span class="xs-separetor border-separetor xs-separetor-v2 fundpress-separetor"></span>
                                <div class="row xs-margin-0">
                                    <div class="full-round fundpress-avatar">
                                        <?php echo get_avatar( get_the_author_meta( get_the_ID() ), 100 ); ?>
                                    </div>
                                    <div class="xs-avatar-title">
                                        <a href="#"><span>By</span><?php the_author(); ?> </a>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    <div class="used-for-colors col-lg-4 col-md-6 col-sm-6">
                        <div class="fundpress-grid-item-content">
                            <div class="fundpress-item-header xs-mb-30">
                                <a href="<?php the_permalink();?>" ><img id="img-secction"src="<?php echo $image_link; ?>" alt=""></a>
                                <div class="xs-item-header-content">
                                    <div class="xs-skill-bar-v3" data-percent="<?php echo $fund_raised_percent; ?>">
                                        <div class="xs-skill-track">
                                            <p><?php echo $fund_raised_percent; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fundpress-item-content">
                                <?php
                                    $categories = get_the_terms( $postId, 'product_cat' );
                                ?>
                                <a href="<?php the_permalink();?>" class="d-block color-navy-blue fundpress-post-title" style="text-align: center;"><?php the_title();?></a>
                                <div id="contenedor1">
                                    <div class="div-alaizquierda"> 
                                        <?php echo wf_archive_fund_goal_text(); ?>
                                    </div>
                                    <div class="div-aladerecha" style="color: black; font-size: 16px; font-weight: bold">
                                        <?php echo wc_price($funding_goal); ?>
                                    </div>
                                </div>
                                <div id="contenedor2">
                                    <div class="div-alaizquierda1">
                                        <?php echo "% Solicitado del valor del proyecto"; ?>
                                    </div>
                                    <div class="div-aladerecha1" style="color: green; font-size: 16px; font-weight: bold;">
                                        <?php echo $porcentaje;echo "%"; ?>
                                    </div>
                                </div>
                                <div id="contenedor3">
                                    <div class="div-alaizquierda">
                                        <?php echo "Garantía";?>
                                    </div>
                                    <div class="div-aladerecha" style="color: #4CC899; font-size: 16px; font-weight: bold;">
                                        <?php echo $garantia;echo" meses"; ?>
                                    </div>
                                </div>
                                <div id="contenedor4">
                                    <div class="div-alaizquierda">
                                        <?php echo "Rendiemiento obtenido"; ?>
                                    </div>
                                    <div class="div-aladerecha">
                                        <?php echo $rendimiento; echo "%"; ?>
                                    </div>
                                </div>
                                <div id="contenedor5">
                                    <div class="div-alaizquierda">
                                        <?php echo "Plazo estimado";?>
                                    </div>
                                    <div class="div-aladerecha">
                                        <?php echo $plazo;echo" meses";?>
                                    </div>
                                </div>
                                <div id="contenedor8">
                                    <div class="div-alaizquierda">
                                        <?php echo "% de construcción" ?>
                                    </div>
                                    <div class="div-aladerecha">
                                        <?php echo $porconstruccion; echo"%";?>
                                    </div>
                                </div>
                                <div id="contenedor9">
                                    <div class="div-alaizquierda">
                                        <?php echo "Mínimo a invertir"?>
                                    </div>
                                    <div class="div-aladerecha">
                                        <?php echo"$";echo $min_price?>
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
                                <div id="contenedor7" >
                                    <div><a class="btn btn-success button-campaings-secction" href="<?php the_permalink();?>">Listo para invertir</a></div>
                                </div>
                                <?php if($args['author'] == 'yes'){ ?>
                                    <span class="xs-separetor border-separetor xs-separetor-v2 fundpress-separetor xs-mb-20 xs-mt-30"></span>
                                    <div class="row xs-margin-0">
                                        <div>
                                            <i class="icon icon-man"></i>
                                        </div>
                                        <div class="xs-avatar-title">
                                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'nickname' ) ); ?>"><span><?php esc_html_e('Por', 'wp-fundraising'); ?></span><?php echo get_the_author_meta( 'nickname' ); ?></a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php
endif;


$html = ob_get_clean();
wp_reset_query();
echo $html;