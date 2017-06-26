<style>
    .form-inline .select2-container--bootstrap{
        width: 300px !important;
    }
    
</style>

<style type="text/css" media="print">
  @page { 
      /*size: landscape;*/
      size: A4;
      margin: 0;
  }
</style>

<div class="page-content">

    <?php if(!isset($download)) { ?>
        <!-- BEGIN PAGE HEADER-->
        <div class="breadcrumbs">
            <h1>
                Fool-Proof Report
            </h1>
        </div>
        <!-- END PAGE HEADER-->
    <?php } ?>
    
    <!-- BEGIN PAGE CONTENT-->
    
    <div class="row" style="margin-top:15px;"  id="part_insp_table">
        
        <div class="col-md-12">

            <div class="portlet light bordered">
                <div class="portlet-body">
                    
                    <?php if(empty($foolproofs)) { ?>
                        <p class="text-center">No Fool-Proof Report.</p>
                    <?php } else { ?>
                        <form method="post">
                            <div class="table-responsive">
                                <table class="table table-hover table-light">
                                    <thead>
                                        <tr>
                                            <th style="vertical-align:middle;">Sr.No.</th>
                                            <th style="vertical-align:middle;">Supplier</th>
                                            <th style="vertical-align:middle;">Date</th>
                                            <th style="vertical-align:middle;">Stage</th>
                                            <th style="vertical-align:middle;">Sub Stage</th>
                                            <th style="vertical-align:middle;">Major Control Parameter</th>
                                            <th style="vertical-align:middle;">LSL</th>
                                            <th style="vertical-align:middle;">USL</th>
                                            <th style="vertical-align:middle;">TGT</th>
                                            <th style="vertical-align:middle;">Unit</th>
                                            <th style="vertical-align:middle;">Measuring Equipment</th>
                                            <th style="vertical-align:middle;">Image</th>
                                            <th style="vertical-align:middle;">Input Value</th>
                                            <th style="vertical-align:middle;">Result</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0; foreach($foolproofs as $foolproof) { $i++; ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $foolproof['supplier_name']; ?></td>
                                                <td><?php echo date('jS M, Y', strtotime($foolproof['created'])); ?></td>
                                                <td><?php echo $foolproof['stage']; ?></td>
                                                <td><?php echo $foolproof['sub_stage']; ?></td>
                                                <td><?php echo $foolproof['major_control_parameters']; ?></td>
                                                <td><?php echo $foolproof['lsl']; ?></td>
                                                <td><?php echo $foolproof['tgt']; ?></td>
                                                <td><?php echo $foolproof['usl']; ?></td>
                                                <td><?php echo $foolproof['unit']; ?></td>
                                                <td><?php echo $foolproof['measuring_equipment']; ?></td>
                                                <td>
                                                    <?php if($foolproof['image'] == NULL){ echo 'NA'; }else{ ?>
                                                    <img src="<?php echo base_url().'assets/foolproof_captured/'.$foolproof['image']; ?>" 
                                                         height="70" width="100" alt="<?php $foolproof['image']; ?>" />
                                                    <?php } ?>
                                                </td>
                                                <?php 
                                                    if($foolproof['lsl'] == NULL && $foolproof['usl'] == NULL){
                                                        $value = 'NA';
                                                    }
                                                    else if($foolproof['lsl'] == '' && $foolproof['usl'] == ''){
                                                        $value = $foolproof['all_results'];
                                                    }else{
                                                        $value = $foolproof['all_values'];
                                                    }
                                                ?>
                                                <td><?php echo $value; ?></td>
                                                <td><?php if($foolproof['result'] == NULL){ echo 'NA'; }else{ echo $foolproof['result']; } ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
