<?php if(!empty($results) && is_array($results)): ?>

	<?php if(isset($anchor)): ?>
		<a name="<?=$anchor?>"></a>
	<?php endif; ?>

	<?php if(isset($title)): ?>
		<h2><?php echo $title?></h2>
	<?php endif; ?>
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
			foreach($results as $row): ?>
					<tr>
						<td>
							<a href="/torrent/<?=$row['hash']?>"><?php echo $row['name']?></a>
						</td>
						<td>
							<a href="/search/?s=<?=$row['category']?>"><?php echo $row['category']?></a>
						</td>
						<td>
							<?php echo $row['hash']?>
						</td>
						<td>
							<a href="<?php echo $row['download']?>">[Download]</a>
						</td>
					</tr>
				<?php
				$pi++;
				if($po == $pi): ?>
					<tr>
						<th>Name</th>
						<th>Category</th>
						<th>Hash</th>
						<th>Download</th>
					</tr>
				<?php	
					$pi = 0;
				endif;
			endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
