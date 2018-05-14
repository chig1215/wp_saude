<?php

// 子テーマのstyle.cssを後から読み込む
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('style')
    );
}

//Child Pages ShortcodeのHTMLをstorkのカード型にあわせる
add_filter("child-pages-shortcode-template", "saude_child_pages_shortcode_template");
function saude_child_pages_shortcode_template($template) {
    $template = <<<EOF
<article class="post-list cf animated fadeIn post-%post_id% post type-post status-publish format-standard article cf" role="article">
<a href="%post_url%" rel="bookmark" title="%post_title%" class="no-icon">


<figure class="eyecatch noimg">
%post_thumb%
</figure>

<section class="entry-content cf">
<h1 class="h2 entry-title">%post_title%</h1>

<div class="description"><p>%post_excerpt%</p>
</div>

</section>
</a>
</article>
EOF;
    return $template;
}

//カスタム投稿設定
add_action( 'init', 'saude_create_post_type' );
function saude_create_post_type() {
    register_post_type( 'mnn', [ // 投稿タイプ名の定義
        'labels' => [
            'name'          => 'MNN', // 管理画面上で表示する投稿タイプ名
            'singular_name' => 'mnn',    // カスタム投稿の識別名
        ],
        'public'        => true,  // 投稿タイプをpublicにするか
        'has_archive'   => true, // アーカイブ機能ON/OFF
        'menu_position' => 5,     // 管理画面上での配置場所
        'rewrite'            => array(  // パーマリンク設定
			'slug' => 'mnn',
			'with_front' => false,
		),
    ]);
}
add_filter('post_type_link', 'saude_mnn_permalink', 1, 3);
function saude_mnn_permalink($post_link, $id = 0, $leavename) {
    global $wp_rewrite;
    $post = &get_post($id);
    if ( is_wp_error( $post ) )
        return $post;
    $newlink = $wp_rewrite->get_extra_permastruct($post->post_type);
    $newlink = str_replace('%'.$post->post_type.'%', $post->ID, $newlink);
    $newlink = home_url(user_trailingslashit($newlink));
    return $newlink;
}
add_action('init', 'saude_mnn_rewrite');
function saude_mnn_rewrite() {
    global $wp_rewrite;
    $wp_rewrite->add_rewrite_tag('%mnn%', '([0-9]+)', 'post_type=mnn&p=');
}

