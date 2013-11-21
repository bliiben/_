<?php
	/*
		I get from the ctrl :
			$client # list of client of the data base
	*/
?>
<ul>
	<?php
		foreach ($client as $c) {
			?>
	<li><?php echo $c['idClient'].' '.$c['nom'].' '.$c['prenom']; ?></li>
			<?php
		}
	?>
</ul>