<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="row">
    <div class="col s12">
        <h4>
            <i class="material-icons left">shopping_cart</i>
            Purchase Order Logs
        </h4>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <table class="highlight bordered responsive-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Changed By</th>
                            <th>Date</th>
                            <th class="center-align">Old Data</th>
                            <th class="center-align">New Data</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if(!empty($logs)) : ?>
                        <?php foreach($logs as $log) : ?>
                            <tr>
                                <td>
                                    <?php 
                                        $color = 'grey';
                                        if($log['action_type'] == 'created') $color = 'green';
                                        if($log['action_type'] == 'updated') $color = 'orange';
                                        if($log['action_type'] == 'items_added') $color = 'blue';
                                    ?>
                                    <span class="new badge <?= $color; ?>" data-badge-caption="<?= ucfirst($log['action_type']); ?>"></span>
                                </td>

                                <td>
                                    <i class="material-icons tiny">person</i>
                                    <?= htmlspecialchars($log['changed_by']); ?>
                                </td>

                                <td>
                                    <?= date('d M Y h:i A', strtotime($log['changed_at'])); ?>
                                </td>

                                <td class="center-align">
                                    <?php if(!empty($log['old_data'])): ?>
                                        <button class="btn-small red lighten-1 waves-effect log-btn"
                                           data-log='<?= htmlspecialchars($log['old_data'], ENT_QUOTES, 'UTF-8'); ?>'>
                                           View
                                        </button>
                                    <?php else: ?>
                                        <span class="grey-text">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="center-align">
                                    <?php if(!empty($log['new_data'])): ?>
                                        <button class="btn-small blue lighten-1 waves-effect log-btn"
                                           data-log='<?= htmlspecialchars($log['new_data'], ENT_QUOTES, 'UTF-8'); ?>'>
                                           View
                                        </button>
                                    <?php else: ?>
                                        <span class="grey-text">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="center-align grey-text">No logs found</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="logModal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h5 id="modalTitle">Log Details</h5>
        <hr>
        <pre id="logContent" style="background:#272822; color:#f8f8f2; padding:15px; border-radius:5px; overflow:auto; font-family: monospace;"></pre>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<script>
$(document).ready(function(){
    
    // Initialize all modals
    var modalElems = document.querySelectorAll('.modal');
    var modalInstances = M.Modal.init(modalElems);

    // Click event for log buttons
    $(document).on('click', '.log-btn', function(e){
        e.preventDefault();

        // Get the raw data string
        var rawData = $(this).attr('data-log');
        var displayContent = "";

        try {
            // Attempt to parse and prettify JSON
            var jsonObj = JSON.parse(rawData);
            displayContent = JSON.stringify(jsonObj, null, 4);
        } catch (err) {
            // If it's not JSON, just show the raw string
            displayContent = rawData;
        }

        // Set content into the pre tag
        $('#logContent').text(displayContent);

        // Get specific modal instance and open it
        var instance = M.Modal.getInstance(document.getElementById('logModal'));
        if(instance) {
            instance.open();
        } else {
            console.error("Modal instance not found. Ensure Materialize JS is loaded.");
        }
    });
});
</script>

