<h2>Share Your Torrent!</h2>
<?=form_open_multipart('share/upload')?>

<?=form_label('Title / Name',  'name')?>
<?=form_input(['type' => 'text', 'maxlength' => '225', 'name' => 'name', 'required' => 'required', 'autocomplete' => 'no', 'class' => 'u-full-width', 'placeholder' => 'Name or Title of Torrent'])?>

<?php
	

?>
<?=form_dropdown('category', $options, 'anime')?>

<?=form_label('Torrent',  'file')?>
<?=form_upload('file')?>
<?=form_submit(['value' => 'Upload', 'class' => 'button button-primary'])?>
<?=form_close()?>