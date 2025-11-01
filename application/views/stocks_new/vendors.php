<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Vendors Management
            <small>Manage medicine vendors and suppliers</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>stocks_new/dashboard">Stock Management</a></li>
            <li class="active">Vendors</li>
        </ol>
    </section>

    <section class="content">
        <div class="panel panel-default pt-5">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header clearfix">
                            <h3 class="box-title">Vendors List</h3>
                            <a href="<?php echo base_url(); ?>stocks_new/add_vendor" class="btn btn-primary btn-sm pull-right">
                                <i class="fa fa-plus"></i> Add New Vendor
                            </a>
                        </div>
                        <div class="box-body">
                            <?php if($this->session->flashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table id="vendorsTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Vendor Number</th>
                                            <th>Vendor Name</th>
                                            <th>Company</th>
                                            <th>Contact Person</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>GST Number</th>
                                            <th>Drug License</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($vendors)): ?>
                                            <?php foreach($vendors as $vendor): ?>
                                                <tr>
                                                    <td><?php echo isset($vendor->ID) ? $vendor->ID : (isset($vendor->id) ? $vendor->id : 'N/A'); ?></td>
                                                    <td>
                                                        <strong><?php echo isset($vendor->vendor_number) ? $vendor->vendor_number : 'N/A'; ?></strong>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo isset($vendor->name) ? $vendor->name : 'N/A'; ?></strong>
                                                        <?php if(isset($vendor->companies_type) && !empty($vendor->companies_type)): ?>
                                                            <br><small class="text-muted"><?php echo $vendor->companies_type; ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($vendor->company_name) ? $vendor->company_name : 'N/A'; ?>
                                                        <?php if(isset($vendor->company_address) && !empty($vendor->company_address)): ?>
                                                            <br><small class="text-muted" title="<?php echo $vendor->company_address; ?>">
                                                                <i class="fa fa-map-marker"></i> <?php echo substr($vendor->company_address, 0, 30) . (strlen($vendor->company_address) > 30 ? '...' : ''); ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($vendor->contact_person_name) ? $vendor->contact_person_name : 'N/A'; ?>
                                                        <?php if(isset($vendor->contact_person_designation) && !empty($vendor->contact_person_designation)): ?>
                                                            <br><small class="text-muted"><?php echo $vendor->contact_person_designation; ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if(isset($vendor->phone_number) && !empty($vendor->phone_number)): ?>
                                                            <a href="tel:<?php echo $vendor->phone_number; ?>" class="text-primary">
                                                                <i class="fa fa-phone"></i> <?php echo $vendor->phone_number; ?>
                                                            </a>
                                                        <?php else: ?>
                                                            N/A
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if(isset($vendor->email) && !empty($vendor->email)): ?>
                                                            <a href="mailto:<?php echo $vendor->email; ?>" class="text-primary">
                                                                <i class="fa fa-envelope"></i> <?php echo $vendor->email; ?>
                                                            </a>
                                                        <?php else: ?>
                                                            N/A
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($vendor->gst_number) ? $vendor->gst_number : 'N/A'; ?>
                                                        <?php if(isset($vendor->pan_number) && !empty($vendor->pan_number)): ?>
                                                            <br><small class="text-muted">PAN: <?php echo $vendor->pan_number; ?></small>
                                                        <?php endif; ?>
                                                        
                                                        <!-- GST Document Links -->
                                                        <?php if(isset($vendor->gst_file) && !empty($vendor->gst_file)): ?>
                                                            <br>
                                                            <div class="document-buttons">
                                                                <a href="<?php echo $vendor->gst_file; ?>" 
                                                                class="btn btn-xs btn-info" title="View GST Certificate" target="_blank">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                <a href="<?php echo $vendor->gst_file; ?>" 
                                                                class="btn btn-xs btn-success" title="Download GST Certificate" download>
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <br><small class="text-muted">No document uploaded</small>
                                                        <?php endif; ?>
                                                        
                                                        <!-- PAN Document Links -->
                                                        <?php if(isset($vendor->pan_file) && !empty($vendor->pan_file)): ?>
                                                            <br>
                                                            <div class="document-buttons">
                                                                <a href="<?php echo $vendor->pan_file; ?>" 
                                                                class="btn btn-xs btn-info" title="View PAN Card" target="_blank">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                <a href="<?php echo $vendor->pan_file; ?>" 
                                                                class="btn btn-xs btn-success" title="Download PAN Card" download>
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <br><small class="text-muted">No PAN document uploaded</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($vendor->drug_license_number) ? $vendor->drug_license_number : 'N/A'; ?>
                                                        <?php if(isset($vendor->fssai_number) && !empty($vendor->fssai_number)): ?>
                                                            <br><small class="text-muted">FSSAI: <?php echo $vendor->fssai_number; ?></small>
                                                        <?php endif; ?>
                                                        
                                                        <!-- Drug License Document Links -->
                                                        <?php if(isset($vendor->drug_license_file) && !empty($vendor->drug_license_file)): ?>
                                                            <br>
                                                            <div class="document-buttons">
                                                                <a href="<?php echo $vendor->drug_license_file; ?>" 
                                                                class="btn btn-xs btn-info" title="View Drug License" target="_blank">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                <a href="<?php echo $vendor->drug_license_file; ?>" 
                                                                class="btn btn-xs btn-success" title="Download Drug License" download>
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <br><small class="text-muted">No license document uploaded</small>
                                                        <?php endif; ?>
                                                        
                                                        <!-- FSSAI Document Links -->
                                                        <?php if(isset($vendor->fssai_file) && !empty($vendor->fssai_file)): ?>
                                                            <br>
                                                            <div class="document-buttons">
                                                                <a href="<?php echo $vendor->fssai_file; ?>" 
                                                                class="btn btn-xs btn-info" title="View FSSAI Certificate" target="_blank">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                <a href="<?php echo $vendor->fssai_file; ?>" 
                                                                class="btn btn-xs btn-success" title="Download FSSAI Certificate" download>
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <br><small class="text-muted">No FSSAI document uploaded</small>
                                                        <?php endif; ?>
                                                        
                                                        <!-- MSME Document Links -->
                                                        <?php if(isset($vendor->msme_file) && !empty($vendor->msme_file)): ?>
                                                            <br>
                                                            <div class="document-buttons">
                                                                <a href="<?php echo $vendor->msme_file; ?>" 
                                                                class="btn btn-xs btn-info" title="View MSME Certificate" target="_blank">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                <a href="<?php echo $vendor->msme_file; ?>" 
                                                                class="btn btn-xs btn-success" title="Download MSME Certificate" download>
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <br><small class="text-muted">No MSME document uploaded</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $status = isset($vendor->status) ? $vendor->status : 'inactive';
                                                        if($status == 'active' || $status == '1'): ?>
                                                            <span class="label label-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="label label-danger">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="<?php echo base_url(); ?>stocks_new/edit_vendor/<?php echo isset($vendor->ID) ? $vendor->ID : $vendor->id; ?>" 
                                                            class="btn btn-info btn-sm" title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-info btn-sm" title="View Details" 
                                                                    onclick="showVendorDetails(<?php echo isset($vendor->ID) ? $vendor->ID : $vendor->id; ?>)">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <?php 
                                                            $status = isset($vendor->status) ? $vendor->status : 'inactive';
                                                            if($status == 'active' || $status == '1'): ?>
                                                                <a href="<?php echo base_url(); ?>stocks_new/delete_vendor/<?php echo isset($vendor->ID) ? $vendor->ID : $vendor->id; ?>" 
                                                                class="btn btn-danger btn-sm" title="Deactivate"
                                                                onclick="return confirm('Are you sure you want to deactivate this vendor?')">
                                                                    <i class="fa fa-ban"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="11" class="text-center">No vendors found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Vendor Details Modal -->
<div class="modal fade" id="vendorDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="closeVendorDetails()">&times;</button>
                <h4 class="modal-title">Vendor Details</h4>
            </div>
            <div class="modal-body" id="vendorDetailsContent">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Loading...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="closeVendorDetails()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#vendorsTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 25,
        "order": [[1, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 10 }
        ]
    });
});

function showVendorDetails(vendorId) {
    // Try Bootstrap 3 modal method first
    try {
        $('#vendorDetailsModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    } catch(e) {
        // Fallback: manually show the modal
        $('#vendorDetailsModal').addClass('in').css('display', 'block');
        $('body').addClass('modal-open');
        $('.modal-backdrop').remove();
        $('body').append('<div class="modal-backdrop fade in"></div>');
    }
    
    $('#vendorDetailsContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
    
    // AJAX call to get vendor details
    $.ajax({
        url: '<?php echo base_url(); ?>stocks_new/get_vendor_details/' + vendorId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                var vendor = response.vendor;
                var html = '<div class="row">';
                
                // Basic Information
                html += '<div class="col-md-6">';
                html += '<h4><i class="fa fa-info-circle text-primary"></i> Basic Information</h4>';
                html += '<table class="table table-condensed">';
                html += '<tr><td><strong>Vendor Name:</strong></td><td>' + (vendor.name || 'N/A') + '</td></tr>';
                html += '<tr><td><strong>Company Name:</strong></td><td>' + (vendor.company_name || 'N/A') + '</td></tr>';
                html += '<tr><td><strong>Company Type:</strong></td><td>' + (vendor.companies_type || 'N/A') + '</td></tr>';
                html += '<tr><td><strong>Vendor Number:</strong></td><td>' + (vendor.vendor_number || 'N/A') + '</td></tr>';
                html += '<tr><td><strong>Status:</strong></td><td>' + (vendor.status == '1' || vendor.status == 'active' ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>') + '</td></tr>';
                html += '</table>';
                html += '</div>';
                
                // Contact Information
                html += '<div class="col-md-6">';
                html += '<h4><i class="fa fa-phone text-primary"></i> Contact Information</h4>';
                html += '<table class="table table-condensed">';
                html += '<tr><td><strong>Phone:</strong></td><td>' + (vendor.phone_number || 'N/A') + '</td></tr>';
                html += '<tr><td><strong>Email:</strong></td><td>' + (vendor.email || 'N/A') + '</td></tr>';
                html += '<tr><td><strong>Contact Person:</strong></td><td>' + (vendor.contact_person_name || 'N/A') + '</td></tr>';
                html += '<tr><td><strong>Designation:</strong></td><td>' + (vendor.contact_person_designation || 'N/A') + '</td></tr>';
                html += '</table>';
                html += '</div>';
                
                html += '</div>';
                
                // Company Address
                if(vendor.company_address) {
                    html += '<div class="row">';
                    html += '<div class="col-md-12">';
                    html += '<h4><i class="fa fa-map-marker text-primary"></i> Company Address</h4>';
                    html += '<p>' + vendor.company_address + '</p>';
                    html += '</div>';
                    html += '</div>';
                }
                
                // Banking Information
                if(vendor.bank_name || vendor.account_no) {
                    html += '<div class="row">';
                    html += '<div class="col-md-12">';
                    html += '<h4><i class="fa fa-bank text-primary"></i> Banking Information</h4>';
                    html += '<table class="table table-condensed">';
                    html += '<tr><td><strong>Bank Name:</strong></td><td>' + (vendor.bank_name || 'N/A') + '</td></tr>';
                    html += '<tr><td><strong>Branch:</strong></td><td>' + (vendor.branch_name || 'N/A') + '</td></tr>';
                    html += '<tr><td><strong>Account Number:</strong></td><td>' + (vendor.account_no || 'N/A') + '</td></tr>';
                    html += '<tr><td><strong>IFSC Code:</strong></td><td>' + (vendor.ifsc_code || 'N/A') + '</td></tr>';
                    html += '<tr><td><strong>Account Type:</strong></td><td>' + (vendor.account_type || 'N/A') + '</td></tr>';
                    html += '</table>';
                    html += '</div>';
                    html += '</div>';
                }
                
                // Legal Documents
                html += '<div class="row">';
                html += '<div class="col-md-12">';
                html += '<h4><i class="fa fa-file-text text-primary"></i> Legal Documents</h4>';
                html += '<table class="table table-condensed">';
                html += '<tr><td><strong>GST Number:</strong></td><td>' + (vendor.gst_number || 'N/A');
                if(vendor.gst_file) {
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/view_document/gst/' + vendorId + '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-eye"></i> View</a>';
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/download_document/gst/' + vendorId + '" class="btn btn-xs btn-success"><i class="fa fa-download"></i> Download</a>';
                }
                html += '</td></tr>';
                html += '<tr><td><strong>PAN Number:</strong></td><td>' + (vendor.pan_number || 'N/A');
                if(vendor.pan_file) {
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/view_document/pan/' + vendorId + '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-eye"></i> View</a>';
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/download_document/pan/' + vendorId + '" class="btn btn-xs btn-success"><i class="fa fa-download"></i> Download</a>';
                }
                html += '</td></tr>';
                html += '<tr><td><strong>Drug License:</strong></td><td>' + (vendor.drug_license_number || 'N/A');
                if(vendor.drug_license_file) {
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/view_document/drug_license/' + vendorId + '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-eye"></i> View</a>';
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/download_document/drug_license/' + vendorId + '" class="btn btn-xs btn-success"><i class="fa fa-download"></i> Download</a>';
                }
                html += '</td></tr>';
                html += '<tr><td><strong>FSSAI Number:</strong></td><td>' + (vendor.fssai_number || 'N/A');
                if(vendor.fssai_file) {
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/view_document/fssai/' + vendorId + '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-eye"></i> View</a>';
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/download_document/fssai/' + vendorId + '" class="btn btn-xs btn-success"><i class="fa fa-download"></i> Download</a>';
                }
                html += '</td></tr>';
                html += '<tr><td><strong>MSME Number:</strong></td><td>' + (vendor.msme_number || 'N/A');
                if(vendor.msme_file) {
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/view_document/msme/' + vendorId + '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-eye"></i> View</a>';
                    html += ' <a href="<?php echo base_url(); ?>stocks_new/download_document/msme/' + vendorId + '" class="btn btn-xs btn-success"><i class="fa fa-download"></i> Download</a>';
                }
                html += '</td></tr>';
                html += '</table>';
                html += '</div>';
                html += '</div>';
                
                $('#vendorDetailsContent').html(html);
            } else {
                $('#vendorDetailsContent').html('<div class="alert alert-danger">Error loading vendor details.</div>');
            }
        },
        error: function() {
            $('#vendorDetailsContent').html('<div class="alert alert-danger">Error loading vendor details.</div>');
        }
    });
}

function closeVendorDetails() {
    // Try Bootstrap 3 modal method first
    try {
        $('#vendorDetailsModal').modal('hide');
    } catch(e) {
        // Fallback: manually hide the modal
        $('#vendorDetailsModal').removeClass('in').css('display', 'none');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }
}

// Add some styling for document buttons
$(document).ready(function() {
    // Handle modal backdrop click to close modal
    $(document).on('click', '.modal-backdrop', function() {
        closeVendorDetails();
    });
    
    // Handle escape key to close modal
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27) { // Escape key
            closeVendorDetails();
        }
    });
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .document-buttons {
                margin-top: 8px;
                display: flex;
                flex-wrap: wrap;
                gap: 4px;
            }
            .document-buttons .btn-xs {
                font-size: 10px;
                padding: 4px 8px;
                border-radius: 3px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                border: none;
                min-width: 60px;
                text-align: center;
            }
            .btn-xs.btn-info {
                background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
                color: white;
            }
            .btn-xs.btn-success {
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                color: white;
            }
            .btn-xs:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                color: white;
                text-decoration: none;
            }
            .btn-xs.btn-info:hover {
                background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
            }
            .btn-xs.btn-success:hover {
                background: linear-gradient(135deg, #20c997 0%, #1e7e34 100%);
            }
            .document-buttons i {
                margin-right: 3px;
            }
            /* Responsive adjustments */
            @media (max-width: 768px) {
                .document-buttons {
                    flex-direction: column;
                    gap: 2px;
                }
                .document-buttons .btn-xs {
                    width: 100%;
                    margin-bottom: 2px;
                }
            }
        `)
        .appendTo('head');
});
</script>
