<?php get_header(); ?>
			<div id="content">
				<div id="inner-content" class="wrap page-full wide cf">
					<main id="main" class="m-all t-all d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
						<article id="post-not-found" class="hentry cf">
							<header class="article-header">
								<h1>ページが見つかりませんでした。</h1>
							</header>
							<section class="entry-content">
								<p>お探しのページが見つかりませんでした。</p>
							<div class="search">
								<h2>記事を検索する</h2>
								<?php get_search_form(); ?>
							</div>
							<div class="search">
								<h2>ページを検索する</h2>
								<form role="search" method="get" id="searchform" class="searchform cf" action="/">
									<input type="search" placeholder="検索する" value="" name="s" id="s" />
									<button type="submit" id="searchsubmit" ><i class="fa fa-search"></i></button>
									<input type="hidden" name="post_type" value="page">
								</form>
							</div>
							<div class="cat-list cf">
								<h2>記事のカテゴリから探す</h2>
							<ul>
							<?php $args = array(
								'title_li' => '',
							); ?>
							<?php wp_list_categories($args); ?>
							</ul>
							</div>
							</section>
						</article>
					</main>
				</div>
			</div>
<?php get_footer(); ?>
