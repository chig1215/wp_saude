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

<div style="display: block;" class="description"><?php the_excerpt(); ?></div>

</section>
</a>
</article>

<?php endwhile; ?>


<?php elseif(is_search()): ?>
<article id="post-not-found" class="hentry cf">
<header class="article-header">
<h1>MNNが見つかりませんでした。</h1>
</header>

<section class="entry-content">

<p>お探しのMNNが見つかりませんでした。</p>

<div class="search">
<h2>MNNを検索する</h2>
<form role="search" method="get" id="searchform" class="searchform cf" action="/">
	<input type="search" placeholder="検索する" value="" name="s" id="s" />
	<button type="submit" id="searchsubmit" ><i class="fa fa-search"></i></button>
	<input type="hidden" name="post_type" value="mnn">
</form>
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