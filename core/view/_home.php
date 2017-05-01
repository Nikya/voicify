<?php
	if (!Setup::isOk() ) { ?>
		<div class="ccc">
			<h3>Setup required </h3>
			<div class="content alert alert-danger">
				Please run the <strong><?php echo ViewUtils::SETUP_MENU_LINK ?></strong> to initialize the system and all its modules.
			</div>
		</div>
	<?php } else { ?>
		<div class="ccc">
			<h3>Play with</h3>
			<div class="content">
				<?php echo ViewUtils::buildPlayMenu(true); ?>
			</div>
		</div>
<?php }
