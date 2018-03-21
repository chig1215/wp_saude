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