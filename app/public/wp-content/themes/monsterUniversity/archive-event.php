<?php
get_header();
pageBanner(array(
	'title' => 'All Events',
	'subtitle' => 'See what\'s going on.'
));
?>

<div class="container container--narrow page-section">

	<?php
	while(have_posts()) {
		the_post();
		get_template_part('/template-parts/content-event');
	}	
	echo paginate_links();
	?>

	<?php wp_reset_postdata();
	?>
	
	<hr class="section-break">
	
	<p>If you want to see our past events please click <a href="<?php echo home_url( '/past-events/' );
		?>">here</a></p>

</div>

<?php
get_footer();
?>