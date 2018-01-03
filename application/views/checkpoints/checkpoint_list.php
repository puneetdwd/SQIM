<style>
    .form-inline .select2-container--bootstrap{
        width: 300px !important;
    }
	
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>


<div class="page-content">
    <div class="row" style="margin-top:15px;">
        
        <div class="col-md-12">

            <div class="portlet light bordered">
                <div class="portlet-body">
                    
                        <div class="table-responsive">
                            <table class="table table-hover table-light">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>INSPECTION_SPEC_MASTER_ID</th>
                                        <th>INSPECTION_SPEC_DETAIL_ID</th>
                                        <th>MODULE_CODE</th>
                                        <th>ORG_CODE</th>
                                        <th>INVENTORY_ITEM_ID</th>
                                        <th>PART_NO</th>
                                        <th>PART_NAME</th>
                                        <th>ITEM_TYPE</th>
                                        <th>LOT_JUDGEMENT_LINK_FLAG</th>
                                        <th>USER_OPINION_DESC</th>
                                        <th>ATTACH_GROUP_ID</th>
                                        <th>INSPECTION_CLASS_ID</th>
                                        <th>INSPECTION_CLASS_NAME</th>
                                        <th>INSPECTION_TYPE_ID</th>
                                        <th>INSPECTION_TYPE_NAME</th>
                                        <th>CTQ_FLAG</th>
                                        <th>CTP_FLAG</th>
                                        <th>ELT_FLAG</th>
                                        <th>PERIODIC_FLAG</th>
                                        <th>PERIOD</th>
                                        <th>INSPECTION_CYCLE</th>
                                        <th>INSPECTION_ITEM_ID</th>
                                        <th>INSPECTION_ITEM_NAME</th>
                                        <th>MEASURE_TYPE_CODE</th>
                                        <th>MEASURE_TYPE</th>
                                        <th>JUDGEMENT_METHOD_CODE</th>
                                        <th>JUDGEMENT_METHOD</th>
                                        <th>SPECIFICATION_DESC</th>
                                        <th>UOM_NAME</th>
                                        <th>USL_VALUE</th>
                                        <th>LSL_VALUE</th>
                                        <th>TARGET_VALUE</th>
                                        <th>ZST_TARGET_VALUE</th>
                                        <th>ZLT_TARGET_VALUE</th>
                                        <th>ZLT_JUDGEMENT_GROUP_CODE</th>
                                        <th>ZLT_JUDGEMENT_GROUP</th>
                                        <th>SAMPLING_STD_CODE</th>
                                        <th>SAMPLING_STD</th>
                                        <th>INSPECTION_STRICTNESS_CODE</th>
                                        <th>INSPECTION_STRICTNESS</th>
                                        <th>AQL_CODE</th>
                                        <th>AQL</th>
                                        <th>INSPECTION_LEVEL_CODE</th>
                                        <th>INSPECTION_LEVEL</th>
                                        <th>SAMPLE_QTY</th>
                                        <th>ALLOWABLE_DEFECT_QTY</th>
                                        <th>MEASURE_EQUIPMENT_NAME</th>
                                        <th>DISPLAY_SEQ_NO</th>
                                        <th>INSPECTION_SPEC_CHANGE_DATE</th>
                                        <th>CHANGE_REASON_DESC</th>
                                        <th>USE_FLAG</th>
                                        <th>USE_FLAG_NAME</th>
                                        <th>LAST_UPDATE_DATE</th>
                                        <th>LAST_UPDATED_BY</th>
                                        <th>CREATION_DATE</th>
                                        <th>CREATED_BY</th>
                                        <th>LAST_UPDATE_LOGIN</th>
                                        <th>UPDATE_PERSON</th>
                                        <th>CREATION_PERSON</th>
                                        <th>Image Name</th>
                                        <th>Vendor Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                   
                                    <?php foreach($checkpoints as $checkpoint) { ?>
                                    
                                        <tr class="checkpoint-<?php echo $checkpoint['id']; ?>">
                                        <td><?php echo $checkpoint['checkpoint_no']; ?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?php echo $checkpoint['part_no']; ?></td>
                                        <td><?php echo $checkpoint['part_name']; ?></td>
                                            
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?php echo $checkpoint['insp_item']?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?php echo $checkpoint['insp_item2']; ?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?php echo $checkpoint['spec']; ?></td>
                                        <td>&nbsp;</td>
                                        
										<td >
											<?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : ''; ?>
										</td>
										<td>
											<?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : ''; ?>
										</td>
										<td >
											<?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : ''; ?>
										</td>
										
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?php echo $checkpoint['created']; ?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?php echo $checkpoint['images']; ?></td>
                                        <td>&nbsp;</td>
										
                                            
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
