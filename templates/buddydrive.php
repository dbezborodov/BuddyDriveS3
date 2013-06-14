<?php if( buddydrive_is_bp_default() ): ?>

	<?php get_header( 'buddypress' ); ?>

		<?php do_action( 'buddydrive_before_directory_page' ); ?>

		<div id="content">
			<div class="padder">

				<?php do_action( 'buddydrive_before_directory_content' ); ?>

				

				<h3><?php _e( 'BuddyDrive', 'buddydrive' ); ?></h3>

			
<?php else:?>

		<div id="buddypress">

				<?php do_action( 'buddydrive_before_directory_content' ); ?>

<?php endif;?>

			<?php do_action( 'template_notices' ); ?>


			<div class="buddydrive" role="main">
				
				<?php do_action( 'buddydrive_directory_content' ); ?>

			</div><!-- .buddydrive -->

			<?php do_action( 'buddydrive_after_directory_content' ); ?>


<?php if( buddydrive_is_bp_default() ):?>

			</div><!-- .padder -->
		</div><!-- #content -->

	<?php get_sidebar( 'buddypress' ); ?>
	<?php get_footer( 'buddypress' ); ?>

<?php else:?>

		</div>

<?php endif;?>