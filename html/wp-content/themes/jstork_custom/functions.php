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
            'all_items' => 'MNN一覧',    // カスタム投稿の識別名
        ],
        'public'        => true,  // 投稿タイプをpublicにするか
        'has_archive'   => true, // アーカイブ機能ON/OFF
        'menu_position' => 5,     // 管理画面上での配置場所
        'supports' => array('title','editor','thumbnail','custom-fields','excerpt','author','revisions'),  /* 機能を有効化 */
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
add_filter( 'getarchives_where', 'saude_mnn_archive_where', 10, 2 );
function saude_mnn_archive_where( $where, $args ){  
    $post_type  = isset( $args['post_type'] ) ? $args['post_type'] : 'post';
    $where = "WHERE post_type = '$post_type' AND post_status = 'publish'";
    return $where;
}

//MNNのbasic認証設定用関数（header.phpで呼ぶ）
function saude_mnn_basic_auth($auth_list,$realm="Restricted Area",$failed_text="ログインに失敗したか、期限が切れてしまいました。この画面をリロードして、もう一度ログインを試してみてください。<br/><br/>何度リロードしてもログイン画面が出ない方へ。FacebookやLINEなど、アプリから開くブラウザでの閲覧はできません。普通のブラウザでお試しください。<br/><br/>G.R.E.S.Saúde Yokohamangueira"){ 
	if (isset($_SERVER['PHP_AUTH_USER']) and isset($auth_list[$_SERVER['PHP_AUTH_USER']])){
	if ($auth_list[$_SERVER['PHP_AUTH_USER']] == $_SERVER['PHP_AUTH_PW']){
	return $_SERVER['PHP_AUTH_USER'];
	}
	}
	header('WWW-Authenticate: Basic realm="'.$realm.'"');
	header('HTTP/1.0 401 Unauthorized');
	header('Content-type: text/html; charset='.mb_internal_encoding());
	die($failed_text);
}

/*********************
パンくずナビ
*********************/
if (!function_exists('breadcrumb')) {
    function breadcrumb($divOption = array("id" => "breadcrumb", "class" => "breadcrumb inner wrap cf")){
        global $post;
        $str ='';
        $post_type = get_post_type();
        if(!get_option('side_options_pannavi')){
            if(!is_home()&&!is_front_page()&&!is_admin() ){
                $tagAttribute = '';
                foreach($divOption as $attrName => $attrValue){
                    $tagAttribute .= sprintf(' %s="%s"', $attrName, $attrValue);
                }
                $str.= '<div'. $tagAttribute .'>';
                $str.= '<ul>';
                $str.= '<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><a href="'. home_url() .'/" itemprop="url"><i class="fa fa-home"></i><span itemprop="title"> HOME</span></a></li>';
         
                if(is_category()) {
                    $cat = get_queried_object();
                    if($cat -> parent != 0){
                        $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
                        foreach($ancestors as $ancestor){
                            $str.='<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($ancestor) .'" itemprop="url"><span itemprop="title">'. get_cat_name($ancestor) .'</span></a></li>';
                        }
                    }
                    $str.='<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><span itemprop="title">'. $cat -> name . '</span></li>';
                } elseif(is_single()){
                    if($post_type == 'mnn') {
                        $str.= '<li><a href="/mnn">MNN</a></li>';
                    }
                    $categories = get_the_category($post->ID);
                    $cat = $categories[0];
                    if($cat -> parent != 0){
                        $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
                        foreach($ancestors as $ancestor){
                            if(!empty(get_category_link($ancestor))) {
                                $str.='<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($ancestor).'" itemprop="url"><span itemprop="title">'. get_cat_name($ancestor). '</span></a></li>';
                            }
                        }
                    }
                    if(!empty(get_category_link($cat -> term_id))) {
                        $str.='<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($cat -> term_id). '" itemprop="url"><span itemprop="title">'. $cat-> cat_name . '</span></a></li>';
                    }
                    $str.= '<li>'. $post -> post_title .'</li>';
                } elseif(is_page()){
                    if($post -> post_parent != 0 ){
                        $ancestors = array_reverse(get_post_ancestors( $post->ID ));
                        foreach($ancestors as $ancestor){
                            $str.='<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><a href="'. get_permalink($ancestor).'" itemprop="url"><span itemprop="title">'. get_the_title($ancestor) .'</span></a></li>';
                        }
                    }
                    $str.= '<li>'. $post -> post_title .'</li>';
                } elseif(is_date()){
                    if($post_type == 'mnn') {
                        $str.= '<li><a href="/mnn">MNN</a></li>';
                    }
                    add_filter('year_link', 'saude_mnn_fix_date_archive_links');
                    add_filter('month_link', 'saude_mnn_fix_date_archive_links');
                    add_filter('day_link', 'saude_mnn_fix_date_archive_links');
                    if( is_year() ){
                        $str.= '<li>' . get_the_time('Y') . '年</li>';
                    } else if( is_month() ){
                        $str.= '<li><a href="' . get_year_link(get_the_time('Y')) .'">' . get_the_time('Y') . '年</a></li>';
                        $str.= '<li>' . get_the_time('n') . '月</li>';
                    } else if( is_day() ){
                        $str.= '<li><a href="' . get_year_link(get_the_time('Y')) .'">' . get_the_time('Y') . '年</a></li>';
                        $str.= '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('n') . '月</a></li>';
                        $str.= '<li>' . get_the_time('j') . '日</li>';
                    }
                    if(is_year() && is_month() && is_day() ){
                        $str.= '<li>' . wp_title('', false) . '</li>';
                    }
                    remove_filter('year_link', 'saude_mnn_fix_date_archive_links');
                    remove_filter('month_link', 'saude_mnn_fix_date_archive_links');
                    remove_filter('day_link', 'saude_mnn_fix_date_archive_links');
                } elseif(is_search()) {
                    $str.='<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><span itemprop="title">「'. get_search_query() .'」で検索した結果</span></li>';
                } elseif(is_author()){
                    $str .='<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><span itemprop="title">投稿者 : '. get_the_author_meta('display_name', get_query_var('author')).'</span></li>';
                } elseif(is_tag()){
                    $str.='<li itemscope itemtype="//data-vocabulary.org/Breadcrumb"><span itemprop="title">タグ : '. single_tag_title( '' , false ). '</span></li>';
                } elseif(is_attachment()){
                    $str.= '<li><span itemprop="title">'. $post -> post_title .'</span></li>';
                } elseif(is_404()){
                    $str.='<li>ページがみつかりません。</li>';
                } else {
                    if($post_type == 'mnn') {
                        $str.= '<li>MNN</li>';
                    }
                }
                $str.='</ul>';
                $str.='</div>';
            }
        }
        echo $str;
    }
}

function saude_mnn_fix_date_archive_links($string){
    $post_type = get_post_type();
    if($post_type != 'post') {
        $string = str_replace('blog/', $post_type . '/', $string);
    }
    return $string;
}

//PDF Embedderビューアの横幅調整
add_filter( 'wp_footer', function() {
    ?>
    <script>
        var timeoutId ;
        window.addEventListener( "load", function () {
            fixPdfEmbedderWidth(timeoutId);
        } ) ;
        window.addEventListener( "resize", function () {
            fixPdfEmbedderWidth(timeoutId);
        } ) ;
        function fixPdfEmbedderWidth(myTimeoutId) {
            // setTimeout()がセットされていたら無視
            if ( myTimeoutId ) return ;
            myTimeoutId = setTimeout( function () {
                myTimeoutId = 0 ;
                $('.pdfemb-viewer').css('width', '-=2');
            }, 500 ) ;
        }
    </script>
    <?php
}, 100 );