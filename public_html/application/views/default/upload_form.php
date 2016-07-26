<h2>Share Your Torrent!</h2>
<?php echo form_open_multipart('share/upload'); ?>

<?php echo form_label('Title / Name',  'name'); ?>
<?php echo form_input([
	'type' => 'text', 
	'maxlength' => '225', 
	'name' => 'name', 
	'required' => 'required', 
	'autocomplete' => 'no', 
	'class' => 'u-full-width', 
	'placeholder' => 
	'Name or Title of Torrent']
	); ?>
<?php echo form_dropdown('category', $options, 'anime'); ?>
<?php echo form_label('Torrent',  'file'); ?>
<?php echo form_upload('file'); ?>
<?php echo form_submit([
	'value' => 'Upload', 
	'class' => 'button button-primary'
	]); ?>
<?php echo form_close(); ?>