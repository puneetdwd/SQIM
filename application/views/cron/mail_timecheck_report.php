<p style="font-size: 16px;"><b>
Timecheck - Completed Inspection Report</br>
Date: <?php echo $yesterday; ?>
</b></p>
<p style="font-size: 20px;"><b>
</b></p>
<?php if(!empty($plans)) { ?>
	<table class="table table-hover table-light">
		<thead>
			<tr style="background-color:#D3D3D3">
				<th>Part</th>
				<th>Date</th>
				<th>From Time</th>
				<th>To Time</th>
				<th>Result</th>
			</tr>
		</thead>
		<tbody>
				<?php foreach($plans as $plan) { ?>
					<tr>
						<td><?php echo $plan['part_name'].' ('.$plan['part_no'].')'; ?></td>
						<td><?php echo date('jS M, Y', strtotime($plan['plan_date'])); ?></td>
						<td><?php echo $plan['from_time']; ?></td>
						<td><?php echo $plan['to_time']; ?></td>
						<td><?php echo $plan['ng_count'] > 0 ? 'NG' : 'OK'; ?></td>
					
				
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