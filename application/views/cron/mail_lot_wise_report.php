<p style="font-size: 16px;"><b>
Timecheck - Completed Inspection Report</br>
Date: <?php echo $yesterday; ?>
</b></p>
<p style="font-size: 20px;"><b>
</b></p>
<?php if(!empty($audits)) { ?>
	<table class="table table-hover table-light">
		<thead>
			<tr>
				<th rowspan="2" class="merged-cell text-center">Last Inspect Date</th>
				<th rowspan="2" class="merged-cell text-center">Product</th>
				<th rowspan="2" class="merged-cell text-center">Supplier</th>
				<th rowspan="2" class="merged-cell text-center">Part</th>
				<th colspan="3" class="merged-cell text-center">No. Of Lots</th>
			</tr>
			<tr>
				<th class="text-center">Inspected</th>
				<th class="text-center">OK</th>
				<th class="text-center">NG</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($audits as $audit) {
				if($audit['ng_lots'] > 0)
					$bg = 'background-color:red;';
			?>
				<tr>
					<td nowrap><?php echo date('jS M, y', strtotime($audit['audit_date'])); ?></td>
					<td><?php echo $audit['product_name']; ?></td>
					<td><?php echo $audit['supplier_no'].' - '.$audit['supplier_name']; ?></td>
					<td><?php echo $audit['part_no'].' - '.$audit['part_name']; ?></td>
					<td class="text-center"><?php echo $audit['no_of_lots']; ?></td>
					<td class="text-center"><?php echo $audit['ok_lots']; ?></td>
					<?php
						if($audit['ng_lots'] > 0) { ?>
							<td class="text-center" style='background-color:red'>
								<?php echo $audit['ng_lots']; ?>
							</td>
					<?php } else {?>
							<td class="text-center">
								<?php echo $audit['ng_lots']; ?>
							</td>					
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php }else{ ?>
<p>
No Timechecks has been done yesterday.
</p>
<?php } ?>
<br>
<br>
<p>
Regards,</br>
SQIM Administrator
<br>
<br>
<i><b>Note:</b>&nbsp;This is a system generated mail. Please do not reply.</i>
</p>
<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
table{
    margin: 0% 0% 0% 5%;
    width: 90%;
th{
		text-align: center;
	    font-size: 30px !important;
		padding:10px
}
tr{
	    padding: 0 auto;
}
</style>
