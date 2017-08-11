
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Supplier Timecheck Counts 
        </h1>
           <span class='caption' style='float:right'>Date:<?php echo $yesterday; ?></span>  
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <?php if($this->session->flashdata('error')) {?>
                <div class="alert alert-danger">
                   <i class="fa fa-times"></i>
                   <?php echo $this->session->flashdata('error');?>
                </div>
            <?php } else if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <i class="fa fa-check"></i>
                   <?php echo $this->session->flashdata('success');?>
                </div>
            <?php } ?>

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>List of Supplier Timecheck Count 
                    </div>
						
                    <div class="actions">
                        <?php if(!empty($plans)) { ?>
						<a class="button normals btn-circle" href="<?php echo base_url()."reports/timecheck_count_by_supplier_download"; ?>">
                            <i class="fa fa-download"></i> Excel Export
                        </a>
						<?php } ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($plans)) { ?>
                        <p class="text-center">No Supplier has done timecheck yesterday.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Supplier Id</th>
                                    <th>Supplier Name</th>
                                    <th>Counts</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
								foreach($plans as $plan) { ?>
                                    <tr>
										<td><?php echo $plan['supplier_no']; ?></td>
										<td><?php echo $plan['name']; ?></td>
										<td><?php echo $plan['cnt']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>