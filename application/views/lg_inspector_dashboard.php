<div class="page-content">
    <div class="breadcrumbs">
        <h1>
            <?php echo $this->session->userdata('name'); ?>
            <small>Welcome to your dashboard</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">Dashboard</li>
        </ol>
        
    </div>
        
    <?php if($this->session->flashdata('error')) {?>
        <div class="alert alert-danger">
           <i class="icon-remove"></i>
           <?php echo $this->session->flashdata('error');?>
        </div>
    <?php } else if($this->session->flashdata('success')) { ?>
        <div class="alert alert-success">
            <i class="icon-ok"></i>
           <?php echo $this->session->flashdata('success');?>
        </div>
    <?php } ?>
    
	<div class="row">
        <div class="col-md-12">
            <div class="mt-element-ribbon bg-grey-steel" id="dashboard-on-going-insp">                
                <div class="ribbon ribbon-clip ribbon-color-danger uppercase">
                    <div class="ribbon-sub ribbon-clip"></div> Current Part Inspection Status 
                </div>
                
                <div class="ribbon-content">
                    <table class="table table-hover table-light dashboard-on-going-insp-table" id="nmake-data-table" style="background-color:inherit;">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Total</th>
                                <th>Completed </th>
                                <th>Pending</th>
                            </tr>
                        </thead>
                        <tbody>
						       <tr style='background-color:white'>
                                    <td>#1</td>
                                    <td><?php if(!empty($inspection_count_total)) { echo $inspection_count_total; }else{
										echo '0';
									} ?></td>
                                    <td><?php if(!empty($inspection_count_completed)) { echo $inspection_count_completed; }else{
										echo '0';
									} ?></td>
                                    <td><?php if(!empty($inspection_count_total) && !empty($inspection_count_completed)) {echo $pending_inspection = $inspection_count_total - $inspection_count_completed; } else { echo '0'; } ?></td>
                                </tr>
						   </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
	
	
	<div class="row">
        <div class="col-md-12">
            <div class="mt-element-ribbon bg-grey-steel" id="dashboard-on-going-insp">                
                <div class="ribbon ribbon-clip ribbon-color-danger uppercase">
                    <div class="ribbon-sub ribbon-clip"></div> Current Timechecks Status 
                </div>
                
                <div class="ribbon-content">
                    <table class="table table-hover table-light dashboard-on-going-insp-table" id="nmake-data-table" style="background-color:inherit;">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Total</th>
                                <th>Completed </th>
                                <th>Pending</th>
                            </tr>
                        </thead>
                        <tbody>
								<tr style='background-color:white'>
                                    <td>#1</td>
                                    <td><?php if(!empty($tc_all)){ echo $tc_all;}else{ echo '0';} ?></td>
                                    <td><?php if(!empty($tc_completed)){ echo $tc_completed; }else{ echo '0';}?></td>
                                    <td><?php if(!empty($tc_all)){ echo $pending_tc = $tc_all - $tc_completed;}else{ echo '0';} ?></td>
                                </tr>
						   </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
	<div class="row">
        <div class="col-md-12">
            <div class="mt-element-ribbon bg-grey-steel" id="dashboard-on-going-insp">                
                <div class="ribbon ribbon-clip ribbon-color-danger uppercase">
                    <div class="ribbon-sub ribbon-clip"></div> Current Foolproof Status 
                </div>
                
                <div class="ribbon-content">
                    <table class="table table-hover table-light dashboard-on-going-insp-table" id="nmake-data-table" style="background-color:inherit;">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Total</th>
                                <th>Completed </th>
                                <th>Pending</th>
                            </tr>
                        </thead>
                        <tbody>
						       <tr style='background-color:white'>
                                    <td>#1</td>
                                    <td><?php if(!empty($foolproof_total)){ echo $foolproof_total; }else{ echo '0'; } ?></td>
                                    <td><?php if(!empty($foolproof_completed)){ echo $foolproof_completed; }else{ echo '0'; } ?></td>
                                    <td><?php if(!empty($foolproof_total)){ echo $pending_foolproof = $foolproof_total - $foolproof_completed; }else{ echo '0'; } ?></td>
                                </tr>
						   </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
	
	
</div>
