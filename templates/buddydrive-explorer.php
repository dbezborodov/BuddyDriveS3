<?php

/**
 * BuddyDrive - ExplorerTemplate
 *
 * @package BuddyDrive
 */
?>
<?php if( buddydrive_is_bp_default() ): ?>

	<?php get_header( 'buddypress' ); ?>

		<div id="content">
			<div class="padder">

				<?php do_action( 'buddydrive_before_member_content' ); ?>

				<div id="item-header" role="complementary">

					<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

				</div><!-- #item-header -->

				<div id="item-nav">
					<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
						<ul>

							<?php bp_get_displayed_user_nav(); ?>

							<?php do_action( 'bp_member_options_nav' ); ?>

						</ul>
					</div>
				</div><!-- #item-nav -->

<?php endif;?>

			<div id="item-body">
				
				<div class="item-list-tabs buddydrive-type-tabs no-ajax" id="subnav">
					<form action="" method="get" id="buddydrive-form-filter">
					<ul>
						
						<?php do_action( 'buddydrive_member_before_nav' ); ?>
						
						<?php bp_get_options_nav() ?>
						
						<?php do_action( 'buddydrive_member_before_toolbar' ); ?>

						<?php if ( buddydrive_is_user_buddydrive() ):?>

							<li id="buddydrive-action-nav" class="last">

								<a href="#" id="buddy-new-file" title="<?php _e('New File', 'buddydrive');?>"><i class="bd-icon-createfile"></i></a>
								<a href="#" id="buddy-new-folder" title="<?php _e('New Folder', 'buddydrive');?>"><i class="bd-icon-addfolder"></i></a>
								<a href="#" id="buddy-edit-item" title="<?php _e('Edit Item', 'buddydrive');?>"><i class="bd-icon-uniF47C"></i></a>
								<a href="#" id="buddy-delete-item" title="<?php _e('Delete Item(s)', 'buddydrive');?>"><i class="bd-icon-remove"></i></a>
								<a><i class="bd-icon-analytics3"></i> <?php buddydrive_user_used_quota();?></a>
								
							</li>

						<?php endif;?>
					</ul>
					</form>
				</div>
				
				<div id="buddydrive-forms">
						<div class="buddydrive-crumbs"><a href="<?php buddydrive_component_home_url();?>" name="home" id="buddydrive-home"><i class="bd-icon-home"></i> <span id="folder-0" class="buddytree current"><?php _e( 'Root folder', 'buddydrive' );?></span></a></div>
				
					<?php if ( buddydrive_is_user_buddydrive() ):?>
					
						<div id="buddydrive-file-uploader" class="hide">
							<?php buddydrive_upload_form();?>
						</div>
						<div id="buddydrive-folder-editor" class="hide">
							<?php buddydrive_folder_form()?>
						</div>
						<div id="buddydrive-edit-item" class="hide"></div>
					
					<?php endif;?>
					
				</div>
				
				<?php do_action( 'buddydrive_after_member_upload_form' ); ?>
				<?php do_action( 'buddydrive_before_member_body' );?>
				
				<div class="buddydrive single-member" role="main">
					<?php buddydrive_get_template('buddydrive-loop');?>
				</div><!-- .buddydrive.single-member -->

				<?php do_action( 'buddydrive_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'buddydrive_after_member_content' ); ?>

<?php if( buddydrive_is_bp_default() ):?>
			</div><!-- .padder -->
		</div><!-- #content -->

	<?php get_sidebar( 'buddypress' ); ?>
	<?php get_footer( 'buddypress' ); ?>

<?php endif;?>