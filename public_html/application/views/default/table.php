<?php

	if(count($results) > 0)
	{

?>
<?if(isset($anchor)) {?>
<a name="<?=$anchor?>"></a>
<?}?>

<?if(isset($title)) {?>
<h2><?=$title?></h2>
<?}?>
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
		$po = 12;
		$pi = 0;
		foreach($results as $row)
		{
			?>	
				<tr>
					<td>
					<a href="/torrent/<?=$row['hash']?>"><?=$row['name']?></a>
					</td>
					<td>
						<a href="/search/?s=<?=$row['category']?>"><?=$row['category']?></a>
					</td>
					<td>
						<?=$row['hash']?>
					</td>
					<td>
						<a href="<?=$row['download']?>">[Download]</a>
					</td>
				</tr>
			<?
			
			$pi++;
			
			if($po === $pi)
			{
				?>
				<tr>
				  <th>Name</th>
				  <th>Category</th>
				  <th>Hash</th>
				  <th>Download</th>
				</tr>
				<?	
				$pi = 0;
			}
			
			
		}
	?>
  </tbody>
</table>

<?
	}
?>
