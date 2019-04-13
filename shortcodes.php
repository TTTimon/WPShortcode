<?php
function show_posts_func( $atts ){
    $args = shortcode_atts( array(
    'post_type' => 'page',
    'posts_class' => 'custom-post-class',
    'container_class' => '',
    'item_class' => '',
    'posts_per_page' => ''
	), $atts );

    $tmp_taxonomy = explode(', ', $atts['taxonomy']);
    $tmp_term = explode(', ',$atts['term']);
    for ($i = 0; $i < count($tmp_taxonomy); $i++){
        $args += [$tmp_taxonomy[$i] => $tmp_term[$i]];
    }

    $result = '<div class="'.$args['posts_class'].' '.$args['container_class'].'">';  
        $property = new WP_Query( $args );
        if( $property->have_posts() ) :
            while( $property->have_posts() ) : $property->the_post();
                $title = get_the_title();
                $post_id = get_the_ID();
                $perm = get_permalink();

                $result .= '<div class="'.$args['posts_class'].'-item '.$args['item_class'].'">';
                // изображение записи со ссылкой
                $result .= '<a href="'.$perm.'"><img class="'.$args['posts_class'].'-img" src="'.wp_get_attachment_image_src( get_post_thumbnail_id(),'medium' )[0].'" alt="'.$title.'"></a>';

                // заголовок статьи
                $result .= '<a href="'.$perm.'"><h3 class="'.$args['posts_class'].'-title">'.$title.'</h3></a>';

                // ссылка на редактирование записи
                if( current_user_can( 'edit_posts' ) ) {
                    $result .= '<a class="edit-url" href="'.get_edit_post_link($post_id).'">Редактировать</a>';
                }
                $result .= '</div>'; //конец класса
                
            endwhile;
        endif;
        wp_reset_postdata();
	$result .= "</div>"; //конец класса
	return $result;
}
add_shortcode('posts', 'show_posts_func');