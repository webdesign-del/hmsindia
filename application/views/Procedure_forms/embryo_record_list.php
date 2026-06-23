<div class="col-md-12">

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h4 class="mb-0">Embryo Records</h4>
</div>
<div class="row mb-3">

<div class="col-md-5">

<form method="get" action="<?php echo base_url('Procedure_forms/embryo_record_list'); ?>">

<div class="input-group">

<input type="text"
name="patient_id"
class="form-control"
placeholder="Search Patient ID"
value="<?php echo $this->input->get('patient_id'); ?>">

<div class="input-group-append">

<button class="btn btn-primary" type="submit">
<i class="fa fa-search"></i> Search
</button>

<a href="<?php echo base_url('Procedure_forms/embryo_record_list'); ?>" class="btn btn-secondary">
<i class="fa fa-refresh"></i> Reset
</a>


</div>

</div>

</form>

</div>

</div>
<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-striped table-hover">

<thead class="thead-dark">

<tr>
<th>ID</th>
<th>Patient ID</th>
<th>Receipt</th>
<th>Date</th>
<th>Embryologist</th>
<th>Status</th>
<th width="150">Action</th>
</tr>

</thead>

<tbody>

<?php if(!empty($records)){ ?>

<?php foreach($records as $row){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<strong><?php echo $row['patient_id']; ?></strong>
</td>

<td><?php echo $row['receipt_number']; ?></td>

<td><?php echo date('d-m-Y',strtotime($row['date0'])); ?></td>

<td><?php echo $row['procedure_id']; ?></td>

<td>

<?php if($row['status']=='approved'){ ?>

<span class="badge badge-success">Approved</span>

<?php }elseif($row['status']=='rejected'){ ?>

<span class="badge badge-danger">Rejected</span>

<?php }else{ ?>

<span class="badge badge-warning">Pending</span>

<?php } ?>

</td>

<td>
    <?php if($row['status'] != 'approved'){ ?>
        <a class="btn btn-sm btn-success" href="<?php echo base_url('Procedure_forms/embryo_record_list/approve/'.$row['id']); ?>">
            Approve
        </a>
        <a class="btn btn-sm btn-danger" href="<?php echo base_url('Procedure_forms/embryo_record_list/reject/'.$row['id']); ?>">
            Reject
        </a>
    <?php } ?>
    
    <button type="button" class="btn btn-sm btn-info" onclick="printtable();">
    <i class="fa fa-print"></i> Print
</button>
</td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>
<td colspan="7" class="text-center">
No Records Found
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

<div class="mt-3">

<?php echo $pagination; ?>

</div>

</div>

</div>

</div>


<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OOCYTE EMBRYO RECORD SHEET TILL D3 SECOND CYCLE</h3></td>
   </tr>
</table>

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
     				  <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="3" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="3" width="100%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="6" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>	
	<table class="table table-bordered table-hover mt-2 table-sm red-field" style="width:100%; border:1px solid #cdcdcd;"  >
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Day O (OPU)</th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Day 1</th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Day 2</th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Day 3</th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">FATE</th>
									<th colspan="2" style="border:1px solid #cdcdcd;">ADD ONS USED AT THE TIME OF TRANSFER</th>
									<th colspan="1" style="border:1px solid #cdcdcd;">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date0'])?$select_result['date0']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date1'])?$select_result['date1']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date8'])?$select_result['date8']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date9'])?$select_result['date9']:""; ?></th>
								    <th colspan="1" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date10'])?$select_result['date10']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time0'])?$select_result['time0']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Diss.Time: <?php echo isset($select_result['time1'])?$select_result['time1']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time2'])?$select_result['time2']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time3'])?$select_result['time3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time8'])?$select_result['time8']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time9'])?$select_result['time9']:""; ?></th>
									<th colspan="1" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time10'])?$select_result['time10']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb'])?$select_result['emb']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Score Time: <?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs0'])?$select_result['hrs0']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs1'])?$select_result['hrs1']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs6'])?$select_result['hrs6']:""; ?></th>
									<th colspan="2" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs7'])?$select_result['hrs7']:""; ?></th>
									<th colspan="1" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs8'])?$select_result['hrs8']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Dr: <?php echo isset($select_result['dr'])?$select_result['dr']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb0'])?$select_result['emb0']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb1'])?$select_result['emb1']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb7'])?$select_result['emb7']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb8'])?$select_result['emb8']:""; ?></th>
									<th colspan="1" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb9'])?$select_result['emb9']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness0'])?$select_result['witness0']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['wit0'])?$select_result['wit0']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['wit5'])?$select_result['wit5']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['wit6'])?$select_result['wit6']:""; ?></th>
									<th colspan="1" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['wit7'])?$select_result['wit7']:""; ?></th>
								
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="padding: 0; background: #FFC000">
					        			<table style="width: 100%;">
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;"></td>
					        					<td  style="border:1px solid #cdcdcd;">IVF</td>
					        					<td  style="border:1px solid #cdcdcd;">ICSI</td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">No. COC inseminated</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_inseminated'])?$select_result['ivf_inseminated']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_inseminated'])?$select_result['icsi_inseminated']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">All oocyte injected</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_injected'])?$select_result['ivf_injected']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_injected'])?$select_result['icsi_injected']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">No. damaged or degenerated oocyte during ICSI</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_degenerated'])?$select_result['ivf_degenerated']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_degenerated'])?$select_result['icsi_degenerated']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">No.M2 oocytes at ICSI</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_oocytes'])?$select_result['ivf_oocytes']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_oocytes'])?$select_result['icsi_oocytes']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">No.M2 oocytes injected</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_oocytes_injected'])?$select_result['ivf_oocytes_injected']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_oocytes_injected'])?$select_result['icsi_oocytes_injected']:""; ?></td>
					        				</tr>
					        			</table>
					        		</th>
					        		<th colspan="6" style="padding: 0; border:1px solid #cdcdcd;">
					        			<table style="width: 100%; border:1px solid #cdcdcd;">
					        				<tr>
												<td  style="border:1px solid #cdcdcd;">Total No.of oocytes with no fertilization</td>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_fertilization'])?$select_result['no_fertilization']:""; ?></td>
											</tr>
											<tr>
												<td  style="border:1px solid #cdcdcd;">No.1 PN oocyte</td>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_1_pn_oocyte'])?$select_result['no_1_pn_oocyte']:""; ?></td>
											</tr>
											<tr>
												<td  style="border:1px solid #cdcdcd;">No.oocytes with 2 PN and PB</td>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_2pn_pb'])?$select_result['oocyte_2pn_pb']:""; ?></td>
											</tr>
											<tr>
												<td  style="border:1px solid #cdcdcd;">No.fertilized oocytes with >2PN</td>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_2pn'])?$select_result['oocyte_2pn']:""; ?></td>
											</tr>
					        			</table>
					        		</th>
					        		<th colspan="4" style="padding: 0; background: #C5E0B3">
					        			<table style="width: 100%;">
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">Total no of cleaved embryos Day2</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cleaved_embryos'])?$select_result['cleaved_embryos']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">Total no.of 4 cell embryos Day2</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_embryos_day2'])?$select_result['cell_embryos_day2']:""; ?></td>
					        				</tr>
					        			</table>
					        		</th>
					        		<th colspan="3" style="padding: 0;  border:1px solid #cdcdcd;">
					        			<table style="width: 100%;">
						        			<tr>
						        				<td  style="border:1px solid #cdcdcd;">Total no. of 8 cell embryos day 3</td>
						        				<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_embryos_day3'])?$select_result['cell_embryos_day3']:""; ?></td>
						        			</tr>
					        			</table>
					        		</th>
									<th colspan="2" style="padding: 0;  border:1px solid #cdcdcd;">
									<input type="checkbox" name="egg_quality_0_1" value="BLASTOCYST" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> BLASTOCYST<br>
							        <input type="checkbox" name="egg_quality_0_1" value="TRANSFER" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "TRANSFER"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="egg_quality_0_1" value="FREEZING" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "FREEZING"){echo 'checked="checked"'; }?>> FREEZING<br>
									<input type="checkbox" name="egg_quality_0_1" value="GDEGENERATE" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DEGENERATE<br>
							        <input type="checkbox" name="egg_quality_0_1" value="DISCARD" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "DISCARD"){echo 'checked="checked"'; }?>> DISCARD							       
									</th>
									<th colspan="4" style="padding: 0;  border:1px solid #cdcdcd;">
									<table style="width: 100%;">
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">TOTAL NO OF EMBRYOS ON WITH LAH DONE</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cleaved_embryos'])?$select_result['cleaved_embryos']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">EMBRYO GLUE</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_embryos_day2'])?$select_result['cell_embryos_day2']:""; ?></td>
					        				</tr>
					        			</table>
									</th>
					        		
					        	</tr>
					        </thead>
							<thead>
					        	<tr>
					        		<th colspan="3" style="padding: 0;border:1px solid #cdcdcd;">
					        			<table style="width:100%; border:1px solid #cdcdcd;" >
					        				<tr><td  style="border:1px solid #cdcdcd;">Hyal Time: <?php echo isset($select_result['hyal_time'])?$select_result['hyal_time']:""; ?></td></tr>
					        				<tr><td  style="border:1px solid #cdcdcd;">Emb. : <?php echo isset($select_result['hyal_time_emb'])?$select_result['hyal_time_emb']:""; ?></td></tr>
					        				<tr><td  style="border:1px solid #cdcdcd;">Inj Time :<?php echo isset($select_result['inj_time'])?$select_result['inj_time']:""; ?></td></tr>
					        				<tr><td  style="border:1px solid #cdcdcd;">Emb. :<?php echo isset($select_result['inj_time_emb'])?$select_result['inj_time_emb']:""; ?></td></tr>
					        			</table>
					        		</th>
					        			<th colspan="6" style="border:1px solid #cdcdcd;">
									    <?php if(!empty($upload_photo_0)) {?>
				 						<img src="<?php echo $upload_photo_0;?>" style="width:100px; height:100px;">
										<?php } else {echo " ";}?>	
									</th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">
					        			<?php if(!empty($upload_photo_1)) {?>
										 <img src="<?php echo $upload_photo_1;?>" style="width:100px; height:100px;">
										<?php } else {echo " ";}?>	
										</th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">
					        				<?php if(!empty($upload_photo_2)) {?>
				 							<img src="<?php echo $upload_photo_2;?>" style="width:100px; height:100px;">
											<?php } else {echo " ";}?>
					        		</th>
					        		<th colspan="5" style="border:1px solid #cdcdcd;"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td  style="border:1px solid #cdcdcd;">No</td>
									<td  style="border:1px solid #cdcdcd;">Egg Quality</td>
									<td  style="border:1px solid #cdcdcd;">comment</td>
									<td  style="border:1px solid #cdcdcd;">1PN</td>
									<td  style="border:1px solid #cdcdcd;">2PN</td>
									<td  style="border:1px solid #cdcdcd;">3PN</td>
									<td  style="border:1px solid #cdcdcd;">Degenerate</td>
									<td  style="border:1px solid #cdcdcd;">2PB</td>
									<td  style="border:1px solid #cdcdcd;">comments</td>
									<td  style="border:1px solid #cdcdcd;">Cell</td>
									<td  style="border:1px solid #cdcdcd;">Grade</td>
									<td  style="border:1px solid #cdcdcd;">Clevage Time</td>
									<td  style="border:1px solid #cdcdcd;">Frag%</td>
									<td  style="border:1px solid #cdcdcd;">Cell</td>
									<td  style="border:1px solid #cdcdcd;">Grade</td>
									<td  style="border:1px solid #cdcdcd;">Frag%</td>
									<td  style="border:1px solid #cdcdcd;">Date</td>
									<td  style="border:1px solid #cdcdcd;">Reason</td>
									<td  colspan="2" style="border:1px solid #cdcdcd;">Remarks</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_0'])?$select_result['no_0']:""; ?> NUMERAL DROP DOWN </td>
							        <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "M1"){echo 'M1'; }?> <br>
									<?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "GV"){echo 'GV'; }?><br>
									 <?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
								    </td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0'])?$select_result['comment_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0'])?$select_result['pn1_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0'])?$select_result['pn2_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0'])?$select_result['pn3_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate'])?$select_result['degenerate']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2'])?$select_result['pb2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments'])?$select_result['comments']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0'])?$select_result['cell_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0'])?$select_result['grade_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time'])?$select_result['clevage_time']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0'])?$select_result['frag_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1'])?$select_result['cell_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1'])?$select_result['grade_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1'])?$select_result['frag_1']:""; ?></td>
								    <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2'])?$select_result['date_2']:""; ?></td>
                                    <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason'])?$select_result['reason']:""; ?></td>
									<td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1'])?$select_result['empty_1']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_1'])?$select_result['no_1']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
							         <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
   									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_1'])?$select_result['comment_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_1'])?$select_result['pn1_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_1'])?$select_result['pn2_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_1'])?$select_result['pn3_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_1'])?$select_result['degenerate_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_1'])?$select_result['pb2_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_1'])?$select_result['comments_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_1'])?$select_result['cell_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_1'])?$select_result['grade_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_1'])?$select_result['clevage_time_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_1'])?$select_result['frag_0_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_1'])?$select_result['cell_1_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_1'])?$select_result['grade_1_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_1'])?$select_result['frag_1_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_1'])?$select_result['date_2_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_1'])?$select_result['reason_1']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_1'])?$select_result['empty_1_1']:""; ?></td>
                            	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_2'])?$select_result['no_2']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							        	
									<?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_2'])?$select_result['comment_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_2'])?$select_result['pn1_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_2'])?$select_result['pn2_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_2'])?$select_result['pn3_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_2'])?$select_result['degenerate_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_2'])?$select_result['pb2_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_2'])?$select_result['comments_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_2'])?$select_result['cell_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_2'])?$select_result['grade_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_2'])?$select_result['clevage_time_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_2'])?$select_result['frag_0_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_2'])?$select_result['cell_1_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_2'])?$select_result['grade_1_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_2'])?$select_result['frag_1_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_2'])?$select_result['date_2_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_2'])?$select_result['reason_2']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_2'])?$select_result['empty_1_2']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_3'])?$select_result['no_3']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
							        <?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_3'])?$select_result['comment_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_3'])?$select_result['pn1_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_3'])?$select_result['pn2_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_3'])?$select_result['pn3_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_3'])?$select_result['degenerate_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_3'])?$select_result['pb2_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_3'])?$select_result['comments_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_3'])?$select_result['cell_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_3'])?$select_result['grade_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_3'])?$select_result['clevage_time_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_3'])?$select_result['frag_0_3']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_3'])?$select_result['cell_1_3']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_3'])?$select_result['grade_1_3']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_3'])?$select_result['frag_1_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_3'])?$select_result['date_2_3']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_3'])?$select_result['reason_3']:""; ?></td>
							        <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_3'])?$select_result['empty_1_3']:""; ?></td>
									</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_4'])?$select_result['no_4']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							        	
									<?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_4'])?$select_result['comment_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_4'])?$select_result['pn1_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_4'])?$select_result['pn2_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_4'])?$select_result['pn3_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_4'])?$select_result['degenerate_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_4'])?$select_result['pb2_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_4'])?$select_result['comments_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_4'])?$select_result['cell_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_4'])?$select_result['grade_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_4'])?$select_result['clevage_time_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_4'])?$select_result['frag_0_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_4'])?$select_result['cell_1_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_4'])?$select_result['grade_1_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_4'])?$select_result['frag_1_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_4'])?$select_result['date_2_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_4'])?$select_result['reason_4']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_4'])?$select_result['empty_1_4']:""; ?></td>								 
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_5'])?$select_result['no_5']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							
									<?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
								 	</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_5'])?$select_result['comment_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_5'])?$select_result['pn1_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_5'])?$select_result['pn2_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_5'])?$select_result['pn3_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_5'])?$select_result['degenerate_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_5'])?$select_result['pb2_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_5'])?$select_result['comments_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_5'])?$select_result['cell_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_5'])?$select_result['grade_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_5'])?$select_result['clevage_time_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_5'])?$select_result['frag_0_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_5'])?$select_result['cell_1_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_5'])?$select_result['grade_1_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_5'])?$select_result['frag_1_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_5'])?$select_result['date_2_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_5'])?$select_result['reason_5']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_5'])?$select_result['empty_1_5']:""; ?></td>								  
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_6'])?$select_result['no_6']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
									<?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							        </td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_6'])?$select_result['comment_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_6'])?$select_result['pn1_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_6'])?$select_result['pn2_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_6'])?$select_result['pn3_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_6'])?$select_result['degenerate_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_6'])?$select_result['pb2_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_6'])?$select_result['comments_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_6'])?$select_result['cell_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_6'])?$select_result['grade_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_6'])?$select_result['clevage_time_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_6'])?$select_result['frag_0_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_6'])?$select_result['cell_1_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_6'])?$select_result['grade_1_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_6'])?$select_result['frag_1_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_6'])?$select_result['date_2_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_6'])?$select_result['reason_6']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_6'])?$select_result['empty_1_6']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_7'])?$select_result['no_7']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">						        
									<?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							        </td>
							        <td  style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['comment_0_7'])?$select_result['comment_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_7'])?$select_result['pn1_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_7'])?$select_result['pn2_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_7'])?$select_result['pn3_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_7'])?$select_result['degenerate_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_7'])?$select_result['pb2_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_7'])?$select_result['comments_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_7'])?$select_result['cell_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_7'])?$select_result['grade_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_7'])?$select_result['clevage_time_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_7'])?$select_result['frag_0_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_7'])?$select_result['cell_1_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_7'])?$select_result['grade_1_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_7'])?$select_result['frag_1_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_7'])?$select_result['date_2_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_7'])?$select_result['reason_7']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_7'])?$select_result['empty_1_7']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_8'])?$select_result['no_8']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
							    	<?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
								   </td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_8'])?$select_result['comment_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_8'])?$select_result['pn1_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_8'])?$select_result['pn2_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_8'])?$select_result['pn3_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_8'])?$select_result['degenerate_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_8'])?$select_result['pb2_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_8'])?$select_result['comments_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_8'])?$select_result['cell_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_8'])?$select_result['grade_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_8'])?$select_result['clevage_time_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_8'])?$select_result['frag_0_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_8'])?$select_result['cell_1_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_8'])?$select_result['grade_1_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_8'])?$select_result['frag_1_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_8'])?$select_result['date_2_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_8'])?$select_result['reason_8']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_8'])?$select_result['empty_1_8']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_9'])?$select_result['no_9']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_9'])?$select_result['comment_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_9'])?$select_result['pn1_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_9'])?$select_result['pn2_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_9'])?$select_result['pn3_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_9'])?$select_result['degenerate_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_9'])?$select_result['pb2_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_9'])?$select_result['comments_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_9'])?$select_result['cell_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_9'])?$select_result['grade_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_9'])?$select_result['clevage_time_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_9'])?$select_result['frag_0_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_9'])?$select_result['cell_1_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_9'])?$select_result['grade_1_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_9'])?$select_result['frag_1_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_9'])?$select_result['date_2_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_9'])?$select_result['reason_9']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_9'])?$select_result['empty_1_9']:""; ?></td>
					        	  </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_10'])?$select_result['no_10']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">						    
 									<?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							   		</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_10'])?$select_result['comment_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_10'])?$select_result['pn1_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_10'])?$select_result['pn2_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_10'])?$select_result['pn3_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_10'])?$select_result['degenerate_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_10'])?$select_result['pb2_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_10'])?$select_result['comments_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_10'])?$select_result['cell_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_10'])?$select_result['grade_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_10'])?$select_result['clevage_time_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_10'])?$select_result['frag_0_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_10'])?$select_result['cell_1_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_10'])?$select_result['grade_1_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_10'])?$select_result['frag_1_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_10'])?$select_result['date_2_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_10'])?$select_result['reason_10']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_10'])?$select_result['empty_1_10']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_11'])?$select_result['no_11']:""; ?> NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							        
 									<?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "GV"){echo 'GV'; }?><br>
									 <?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_11'])?$select_result['comment_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_11'])?$select_result['pn1_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_11'])?$select_result['pn2_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_11'])?$select_result['pn3_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_11'])?$select_result['degenerate_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_11'])?$select_result['pb2_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_11'])?$select_result['comments_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_11'])?$select_result['cell_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_11'])?$select_result['grade_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_11'])?$select_result['clevage_time_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_11'])?$select_result['frag_0_11']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_11'])?$select_result['cell_1_11']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_11'])?$select_result['grade_1_11']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_11'])?$select_result['frag_1_11']:""; ?></td>
							      <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_11'])?$select_result['date_2_11']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_11'])?$select_result['reason_11']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_11'])?$select_result['empty_1_11']:""; ?></td>
					        	  </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_12'])?$select_result['no_12']:""; ?>NUMERAL DROP DOWN</td>
									<td  style="border:1px solid #cdcdcd;">
									<?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							 	    <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_12'])?$select_result['comment_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_12'])?$select_result['pn1_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_12'])?$select_result['pn2_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['pn3_0_12'])?$select_result['pn3_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_12'])?$select_result['degenerate_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_12'])?$select_result['pb2_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_12'])?$select_result['comments_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_12'])?$select_result['cell_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_12'])?$select_result['grade_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_12'])?$select_result['clevage_time_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_12'])?$select_result['frag_0_12']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_12'])?$select_result['cell_1_12']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_12'])?$select_result['grade_1_12']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_12'])?$select_result['frag_1_12']:""; ?></td>
							      <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_12'])?$select_result['date_2_12']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_12'])?$select_result['reason_12']:""; ?></td>
							       <td colspan="2"  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_12'])?$select_result['empty_1_12']:""; ?></td>
					        	 </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_13'])?$select_result['no_13']:""; ?> NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							        	
 									<?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd; height:30px; height:30px;"><?php echo isset($select_result['comment_0_13'])?$select_result['comment_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_13'])?$select_result['pn1_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_13'])?$select_result['pn2_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_13'])?$select_result['pn3_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_13'])?$select_result['degenerate_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_13'])?$select_result['pb2_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_13'])?$select_result['comments_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_13'])?$select_result['cell_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_13'])?$select_result['grade_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_13'])?$select_result['clevage_time_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_13'])?$select_result['frag_0_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_13'])?$select_result['cell_1_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_13'])?$select_result['grade_1_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_13'])?$select_result['frag_1_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_13'])?$select_result['date_2_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_13'])?$select_result['reason_13']:""; ?></td>
							       <td colspan="2"  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_13'])?$select_result['empty_1_13']:""; ?></td>
					        	  </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_14'])?$select_result['no_14']:""; ?> NUMERAL DROP DOWN</td>
							       <td  style="border:1px solid #cdcdcd;">
							         <?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							        </td>
									<td  style="border:1px solid #cdcdcd; height:30px;"><?php echo isset($select_result['comment_0_14'])?$select_result['comment_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_14'])?$select_result['pn1_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_14'])?$select_result['pn2_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_14'])?$select_result['pn3_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_14'])?$select_result['degenerate_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_14'])?$select_result['pb2_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_14'])?$select_result['comments_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_14'])?$select_result['cell_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_14'])?$select_result['grade_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_14'])?$select_result['clevage_time_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_14'])?$select_result['frag_0_14']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_14'])?$select_result['cell_1_14']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_14'])?$select_result['grade_1_14']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_14'])?$select_result['frag_1_14']:""; ?></td>
							      	<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_14'])?$select_result['date_2_14']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_14'])?$select_result['reason_14']:""; ?></td>
							       <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_14'])?$select_result['empty_1_14']:""; ?></td>					        	
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_15'])?$select_result['no_15']:""; ?> NUMERAL DROP DOWN </td>
							        <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd; height:30px;"><?php echo isset($select_result['comment_0_15'])?$select_result['comment_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_15'])?$select_result['pn1_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_15'])?$select_result['pn2_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_15'])?$select_result['pn3_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_15'])?$select_result['degenerate_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_15'])?$select_result['pb2_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_15'])?$select_result['comments_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_15'])?$select_result['cell_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_15'])?$select_result['grade_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_15'])?$select_result['clevage_time_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_15'])?$select_result['frag_0_15']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_15'])?$select_result['cell_1_15']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_15'])?$select_result['grade_1_15']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_15'])?$select_result['frag_1_15']:""; ?></td>
							      	<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_15'])?$select_result['date_2_15']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_15'])?$select_result['reason_15']:""; ?></td>
							       <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_15'])?$select_result['empty_1_15']:""; ?></td>
					        	  </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_16'])?$select_result['no_16']:""; ?> NUMERAL DROP DOWN </td>
							        <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							        </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_16'])?$select_result['comment_0_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_16'])?$select_result['pn1_0_16']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_16'])?$select_result['pn2_0_16']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_16'])?$select_result['pn3_0_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_16'])?$select_result['degenerate_16']:""; ?> </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_16'])?$select_result['pb2_16']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_16'])?$select_result['comments_16']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_16'])?$select_result['cell_0_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_16'])?$select_result['grade_0_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_16'])?$select_result['clevage_time_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_16'])?$select_result['frag_0_16']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_16'])?$select_result['cell_1_16']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_16'])?$select_result['grade_1_16']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_16'])?$select_result['frag_1_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_16'])?$select_result['date_2_16']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_8'])?$select_result['reason_16']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_16'])?$select_result['empty_1_16']:""; ?></td>				        	
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_17'])?$select_result['no_17']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;">					
 									<?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "M2"){echo 'M2'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
 									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_17'])?$select_result['comment_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_17'])?$select_result['pn1_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_17'])?$select_result['pn2_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_17'])?$select_result['pn3_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_17'])?$select_result['degenerate_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_17'])?$select_result['pb2_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_17'])?$select_result['comments_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_17'])?$select_result['cell_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_17'])?$select_result['grade_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_17'])?$select_result['clevage_time_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_17'])?$select_result['frag_0_17']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_17'])?$select_result['cell_1_17']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_17'])?$select_result['grade_1_17']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_17'])?$select_result['frag_1_17']:""; ?></td>
								   <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_17'])?$select_result['date_2_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_17'])?$select_result['reason_17']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_17'])?$select_result['empty_1_17']:""; ?></td>				        	
								   </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_18'])?$select_result['no_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;">	
									<?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "M2"){echo 'M2'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "M1"){echo 'M1'; }?><br>
									<?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
								   </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_18'])?$select_result['comment_0_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_18'])?$select_result['pn1_0_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_18'])?$select_result['pn2_0_18']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_18'])?$select_result['pn3_0_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_18'])?$select_result['degenerate_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_18'])?$select_result['pb2_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_18'])?$select_result['comments_18']:""; ?></td>
	  								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_18'])?$select_result['cell_0_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_18'])?$select_result['grade_0_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_18'])?$select_result['clevage_time_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_18'])?$select_result['frag_0_18']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_18'])?$select_result['cell_1_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_18'])?$select_result['grade_1_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_18'])?$select_result['frag_1_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_18'])?$select_result['date_2_18']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_18'])?$select_result['reason_18']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_18'])?$select_result['empty_1_18']:""; ?></td>				        	
								 </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_19'])?$select_result['no_19']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;">
 									<?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "M2"){echo 'M2'; }?><br>
									<?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "M1"){echo 'M1'; }?><br>
									<?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
 									</td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_19'])?$select_result['comment_0_19']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_19'])?$select_result['pn1_0_19']:""; ?></td>
  									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_19'])?$select_result['pn2_0_19']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_19'])?$select_result['pn3_0_19']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_19'])?$select_result['degenerate_19']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_19'])?$select_result['pb2_19']:""; ?></td>
  									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_19'])?$select_result['comments_19']:""; ?></td>
  									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_19'])?$select_result['cell_0_19']:""; ?></td>
  									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_19'])?$select_result['grade_0_19']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_19'])?$select_result['clevage_time_19']:""; ?>"</td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_19'])?$select_result['frag_0_19']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_19'])?$select_result['cell_1_19']:""; ?>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_19'])?$select_result['grade_1_19']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_19'])?$select_result['frag_1_19']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_19'])?$select_result['date_2_19']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_19'])?$select_result['reason_19']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_19'])?$select_result['empty_1_19']:""; ?></td>				        	
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_20'])?$select_result['no_20']:""; ?>NUMERAL DROP DOWN</td>
									 <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "M2"){echo 'M2'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "M1"){echo 'M1'; }?><br>
									<?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "GV"){echo 'GV'; }?><br>
									<?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_20'])?$select_result['comment_0_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_20'])?$select_result['pn1_0_20']:""; ?>  </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_20'])?$select_result['pn2_0_20']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_20'])?$select_result['pn3_0_20']:""; ?>  </td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_20'])?$select_result['degenerate_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_20'])?$select_result['pb2_20']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_20'])?$select_result['comments_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_20'])?$select_result['cell_0_20']:""; ?>  </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_20'])?$select_result['grade_0_20']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_20'])?$select_result['clevage_time_20']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_20'])?$select_result['frag_0_20']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_20'])?$select_result['cell_1_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_20'])?$select_result['grade_1_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_20'])?$select_result['frag_1_20']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_20'])?$select_result['date_2_20']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_20'])?$select_result['reason_20']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_20'])?$select_result['empty_1_20']:""; ?></td>				        	
	</tr>
					        </thead>
					    </table>
	<table class="table table-bordered table-hover mt-2 table-sm red-field"  style="width:100%; border:1px solid #cdcdcd;">
							<ul><li>Emb/Gr = Embryo/Grade</li></ul>
					    	<thead>
						        <th  style="border:1px solid #cdcdcd;"> Embryo Grading (Fragmentation): 1- Best </th>
					    	</thead>
					    	<thead>
					        	<th  style="border:1px solid #cdcdcd;"> Day 3 </th>
					         	<th  style="border:1px solid #cdcdcd;"> 1</th>
					          	<th  style="border:1px solid #cdcdcd;">2 </th>
					           	<th  style="border:1px solid #cdcdcd;">3 </th>
					           	<th  style="border:1px solid #cdcdcd;">4</th>
					    	</thead>
					    	<thead>
					        	<th  style="border:1px solid #cdcdcd;"> Fragmentation</th>
					         	<th  style="border:1px solid #cdcdcd;"> No Fragmentation</th>
					          	<th  style="border:1px solid #cdcdcd;">0-20%</th>
					           	<th  style="border:1px solid #cdcdcd;">20-30% </th>
					           	<th  style="border:1px solid #cdcdcd;">50-100%</th>
					    	</thead>
					    	<thead>
						        <th  style="border:1px solid #cdcdcd;">Blastocyst Grade (Expansion): 4&A- Best,1 &D -Poor </th>
						    </thead>
					    	<thead>
					        	<th  style="border:1px solid #cdcdcd;"> Grade </th>
					         	<th  style="border:1px solid #cdcdcd;"> 1</th>
					          	<th  style="border:1px solid #cdcdcd;">2 </th>
					           	<th  style="border:1px solid #cdcdcd;">3 </th>
					           	<th  style="border:1px solid #cdcdcd;">4</th>
					    	</thead>
					    	<thead>
					         	<th  style="border:1px solid #cdcdcd;">ICM</th>
					          	<th  style="border:1px solid #cdcdcd;">A</th>
					           	<th  style="border:1px solid #cdcdcd;">B</th>
					           	<th  style="border:1px solid #cdcdcd;">C</th>
					           	<th  style="border:1px solid #cdcdcd;">D</th>
					    	</thead>
					    	<thead>
					         	<th  style="border:1px solid #cdcdcd;"> Trophoblast</th>
					          	<th  style="border:1px solid #cdcdcd;">A</th>
					           	<th  style="border:1px solid #cdcdcd;">B</th>
					           	<th  style="border:1px solid #cdcdcd;">C</th>
					           	<th  style="border:1px solid #cdcdcd;">D</th>
					    	</thead>
					    </table>				
</div>
 <style type="text/css">
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static!important;
    left: -9999px;
    opacity: 1!important;
}
 </style>
						
<script> 
 function printtable() 
{    //alert();
  $('.searchform').hide();
   $('.printbtn').hide();
  $('.printbtn').css('display', 'hide');
  $('.prtable').css('display', 'block');
  var divToPrint=document.getElementById('printtable');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>	

<style type="text/css">
    /* Normal screen ke checkbox ki style (Jo aapne pehle se lagai hai) */
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
        position: static!important;
        left: auto;
        opacity: 1!important;
    }

    /* 🖨️ PRINT SCREEN KE LIYE CSS (Jisse same page par print hoga) */
    @media print {
        /* 1. Website ka baaki sab kuch hide kar do */
        body * {
            visibility: hidden;
        }
        
        /* 2. Sirf 'printtable' wale div ko aur uske elements ko visible rakho */
        #printtable, #printtable * {
            visibility: visible;
        }
        
        /* 3. Print wale form ko page ke bilkul top-left corner par set kar do */
        #printtable {
            position: absolute;
            left: 0;
            top: 0;
            display: block !important; /* inline display:none ko override karne ke liye */
            width: 100%;
        }
    }
</style>

<script> 
function printtable() {
    // Ye line automatically CSS ka @media print activate kar degi 
    // aur same page par form ka print nikal degi
    window.print();
}
</script>