<?php get_header(); ?>
<div id="content">
<div id="inner-content" class="wrap page-full wide cf">
<main id="main" class="m-all t-all d-5of7 cf" role="main">
<div class="archivettl">
<h1 class="archive-title"><span>キーワード</span> <?php echo esc_attr(get_search_query()); ?></h1>
</div>

<?php get_template_part( 'parts_archive_simple' ); ?>

<?php pagination(); ?>
</main>
</div>
</div>
<?php get_footer(); ?>