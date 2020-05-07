<?php
// sort1
// sort2
// sort3
function fct_sort3($id, $by1, $by2, $by3) {
	?>
			<table id="<?php print $id; ?>" name="<?php print $id; ?>" style="border-collapse:collapse;" border="1" bordercolor="#808080" cellspacing="1" cellpadding="1" onclick="sortColumn(event)">
			<thead>
		<tr>
	<!-- cursor compatibility -->
	<td class="sort">
		Sort by <?php print $by1; ?>
	</td>
	<td class="sort">
		Sort by <?php print $by2; ?>
	</td>
	<td class="sort">
		Sort by <?php print $by3; ?>
	</td>
		</tr>
			</thead>
			<tbody>
			<?php
}
?>