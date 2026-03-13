<div class="col-md-12">

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h4 class="mb-0">Trigger Records</h4>
</div>
<div class="row mb-3">

<div class="col-md-5">

<form method="get" action="<?php echo base_url('Procedure_forms/trigger_module_list'); ?>">

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

<a href="<?php echo base_url('Procedure_forms/trigger_module_list'); ?>" class="btn btn-secondary">
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

<td><?php echo date('d-m-Y',strtotime($row['last_inj_fsh'])); ?></td>

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

<?php if($row['status']!='approved'){ ?>

<a class="btn btn-sm btn-success"
href="<?php echo base_url('Procedure_forms/trigger_module_list/trigger_approve/'.$row['id']); ?>">

Approve

</a>

<a class="btn btn-sm btn-danger"
href="<?php echo base_url('Procedure_forms/trigger_module_list/trigger_reject/'.$row['id']); ?>">

Reject

</a>

<?php } ?>

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