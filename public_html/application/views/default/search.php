<?if(isset($_GET['s']))
{
	echo '<p>Your results for <strong>'.$_GET['s'].'</strong>...</p>';
}?>

<?php
	if(isset($results) && count($results) > 0)
	{

?>

<div class="row container">
<div class="one">

<table class="u-full-width">
  <thead>
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Hash</th>
      <th>Download</th>
    </tr>
  </thead>
  <tbody>
	<?php
		foreach($results as $row)
		{
			?>	
				<tr>
					<td>
					<?=$row['name']?>
					</td>
					<td>
						<?=$row['category']?>
					</td>
					<td>
						<?=$row['hash']?>
					</td>
					<td>
						<a href="<?=$row['download']?>">[Download]</a>
					</td>
				</tr>
			<?
		}
	?>
  </tbody>
  <tfoot>
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Hash</th>
      <th>Download</th>
    </tr>
  </tfoot>
</table>
</div>
</div>
<?
	}
?>
