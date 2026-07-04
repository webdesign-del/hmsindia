<style>
    /* Clean Modern Design */
    .content-wrapper { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; color: #333; }
    
    /* Form Styles */
    .filter-container { background: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 25px; display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; border: 1px solid #e9ecef; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #495057; }
    .form-control { padding: 8px 12px; border: 1px solid #ced4da; border-radius: 4px; font-size: 14px; outline: none; transition: border-color 0.15s ease-in-out; }
    .form-control:focus { border-color: #80bdff; }
    
    /* Buttons */
    .btn { padding: 9px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; transition: background 0.2s; color: #fff; }
    .btn-primary { background-color: #007bff; }
    .btn-primary:hover { background-color: #0056b3; }
    .btn-success { background-color: #28a745; }
    .btn-success:hover { background-color: #218838; }

    /* Table Styles */
    .table-container { overflow-x: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #e9ecef; }
    .styled-table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .styled-table thead tr { background-color: #343a40; color: #ffffff; text-align: left; }
    .styled-table th, .styled-table td { padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; }
    .styled-table tbody tr:hover { background-color: #f8f9fa; }

    /* Pagination Styles */
    .pagination-wrapper { display: flex; justify-content: flex-end; margin-top: 20px; gap: 5px; }
    .pagination-wrapper a, .pagination-wrapper span { display: inline-block; padding: 8px 14px; border: 1px solid #dee2e6; border-radius: 4px; text-decoration: none; color: #007bff; font-size: 14px; background: #fff; transition: 0.2s; }
    .pagination-wrapper a:hover { background-color: #e9ecef; }
    .pagination-wrapper .active { background-color: #007bff; color: white; border-color: #007bff; }
</style>

<div class="content-wrapper">
    <h3 style="margin-top:0; margin-bottom: 20px;">Stock Transfer List</h3>

    <form method="GET" action="<?= base_url('stocks_new/index') ?>" class="filter-container">
        
        <div class="form-group">
            <label>Start Date:</label>
            <input type="date" name="start_date" class="form-control" value="<?= isset($filters['start_date']) ? htmlspecialchars($filters['start_date']) : '' ?>">
        </div>

        <div class="form-group">
            <label>End Date:</label>
            <input type="date" name="end_date" class="form-control" value="<?= isset($filters['end_date']) ? htmlspecialchars($filters['end_date']) : '' ?>">
        </div>

        <div class="form-group">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                <option value="pending" <?= (isset($filters['status']) && $filters['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="completed" <?= (isset($filters['status']) && $filters['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" name="action" value="filter" class="btn btn-primary">Filter Results</button>
        </div>
        <div class="form-group">
            <button type="submit" name="action" value="export" class="btn btn-success">Export to CSV</button>
        </div>
    </form>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Transfer No.</th>
                    <th>Date</th>
                    <th>Medicine</th>
                    <th>From Center</th>
                    <th>Qty Transferred</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['transfer_number']) ?></td>
                            <td><?= htmlspecialchars($row['transfer_date']) ?></td>
                            <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                            <td><?= htmlspecialchars($row['from_center']) ?></td>
                            <td><?= htmlspecialchars($row['quantity_transferred']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; color: #6c757d;">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?= $pagination_links ?>

</div>