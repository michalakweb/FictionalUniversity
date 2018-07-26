<?php
get_header();
pageBanner();

while(have_posts()) {
	the_post(); 
?>

<div class="container container--narrow page-section">

	<div class="metabox metabox--position-up metabox--with-home-link">
		<p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
	</div>

	<div class="generic-content">
		<?php the_field('main_body_content'); ?>
	</div>
	
	<?php
	$today = date('Ymd');
	$eventPosts = new WP_Query(array(
		'posts_per_page' => 2,
		'post_type' => 'event',
		'meta_key' => 'event_date',
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => 'event_date',
				'compare' => '>=',
				'value' => $today,
				'type' => 'numeric'
			),
			array(
				'key' => 'related_program',
				'compare' => 'LIKE', //like means "contains"
				'value' => '"' . get_the_ID() . '"'
			)
		)
	));
	
	if($eventPosts->have_posts()) {
		echo '<hr class="section-break">';
		echo '<h2>Related upcoming events:</h2>';
	}
	
	
	
	while($eventPosts->have_posts()) {
		$eventPosts->the_post();
		get_template_part('/template-parts/content-event');
			
				
	} wp_reset_postdata();
	
		$related = get_field('related_campus');
		if($related) {
			echo '<hr class="section-break">';
			echo "<h2>This course is available at these campuses:";
			
			echo '<ul class="min-list link-list">';
			foreach($related as $campus) {
				?>
				<li>
					<a href="<?php echo the_permalink($campus) ?>"><?php echo get_the_title($campus) ?></a>
				</li>
				<?php
			} echo '</ul>';
		}
		?>

<?php } ?>

	<?php
	$relatedPrograms = new WP_Query(array(
		'posts_per_page' => -1,
		'post_type' => 'professor',
		'orderby' => 'title',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => 'related_program',
				'compare' => 'LIKE', //like means "contains"
				'value' => '"' . get_the_ID() . '"'
			)
		)
	));

	if($relatedPrograms->have_posts()) {
		echo '<hr class="section-break">';
		echo '<h2>Related Professors:</h2>';
	}
	
	echo '<ul class="professor-cards">';

	while($relatedPrograms->have_posts()) {
		$relatedPrograms->the_post(); ?>

	<li class="professor-card__list-item">
		<a class="professor-card" href="<?php the_permalink(); ?>">
			<img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
			<span class="professor-card__name">
				<?php the_title(); ?>
			</span>
		</a>
	</li>

	<?php	
	} 
	echo '</ul>';
	wp_reset_postdata();
	?>


</div>
<?php


get_footer();

?>