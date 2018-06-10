<div class="top-post-list">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<article <?php post_class('post-list animated fadeIn'); ?> role="article">
<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="cf">

<section class="entry-content">
<h1 class="h2 entry-title"><?php the_title(); ?></h1>

<p class="byline entry-meta vcard">
<span class="date gf updated"><?php the_time('Y.m.d'); ?></span>
<span class="writer name author"><span class="fn"><?php the_author(); ?></span></span>
</p>

</section>
</a>
</article>

<?php endwhile; ?>


<?php elseif(is_search()): ?>
<article id="post-not-found" class="hentry cf">
<header class="article-header">
<h1>記事が見つかりませんでした。</h1>
</header>

<section class="entry-content">

<p>お探しのキーワードで記事が見つかりませんでした。別のキーワードで再度お探しいただくか、カテゴリ一覧からお探し下さい。</p>

<div class="search">
<h2>キーワード検索</h2>
<?php get_search_form(); ?>
</div>


<div class="cat-list cf">
<h2>カテゴリーから探す</h2>
<ul>
<?php $args = array(
'title_li' => '',
); ?>
<?php wp_list_categories($args); ?>
</ul>
</div>

</section>

</article>

<?php else : ?>

<article id="post-not-found" class="hentry cf">
<header class="article-header">
<h1>まだ投稿がありません！</h1>
</header>
<section class="entry-content">
<p>表示する記事がまだありません。</p>
</section>
</article>

<?php endif; ?>
</div>