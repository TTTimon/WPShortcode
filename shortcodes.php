<?php
function show_posts_func( $atts ){
	$args = shortcode_atts( array(
    'post_type' => 'page',                //тип постов
    'posts_class' => 'custom-post-class', //класс-обертка для постов и всех внутренностей
    //список таксономий (через ", ")
    //список терминов (через ", ")
    'container_class' => '',              //дополнительные классы для обертки (через " ")
    'item_class' => '',                 //дополнительные классы для каждого item'а (через " ")
    'posts_per_page' => ''
	), $atts );

  /* 
    Для вывода статей из нескольких таксономий и нескольких терминов этих таксономий
    используем explode для разбиения строки taxonomy и term на подстроки.
    Далее в цикле добавляем в ассоциативный массив args нашу пару taxonomy => term
    ВАЖНО: подстроки должны быть разбиты ", " (запятая и пробел)
  */
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
        $result .= '</div>'; //конец класса offer-item
        
      endwhile;
    endif;
    wp_reset_postdata();
	$result .= "</div>"; //конец класса offer
	return $result;
}
add_shortcode('posts', 'show_posts_func');