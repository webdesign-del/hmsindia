<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision Feedback | HMS Billing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --approve-color: #28a745;
            --disapprove-color: #dc3545;
            --bg-color: #f0f2f5;
            --text-main: #2d3436;
        }

        body { 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            background-color: var(--bg-color); 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: var(--text-main);
        }

        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 450px;
            text-align: center;
            border-top: 8px solid <?= ($action == 'approve' ? 'var(--approve-color)' : 'var(--disapprove-color)') ?>;
        }

        .icon-box {
            font-size: 50px;
            margin-bottom: 20px;
            color: <?= ($action == 'approve' ? 'var(--approve-color)' : 'var(--disapprove-color)') ?>;
        }

        h2 { 
            margin: 0 0 10px 0;
            font-size: 24px;
            color: #2d3436;
        }

        .subtitle {
            color: #636e72;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            display: block;
            margin-bottom: 8px;
        }

        textarea { 
            width: 100%; 
            height: 120px; 
            border-radius: 8px; 
            border: 1px solid #dfe6e9; 
            padding: 12px; 
            font-family: inherit;
            resize: none;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        textarea:focus {
            outline: none;
            border-color: <?= ($action == 'approve' ? 'var(--approve-color)' : 'var(--disapprove-color)') ?>;
        }

        .btn-submit { 
            width: 100%;
            padding: 14px; 
            border: none; 
            border-radius: 8px; 
            color: white; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: 600;
            background-color: <?= ($action == 'approve' ? 'var(--approve-color)' : 'var(--disapprove-color)') ?>;
            transition: opacity 0.3s, transform 0.2s;
        }

        .btn-submit:hover {
            opacity: 0.9;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="icon-box">
        <i class="fa-solid <?= ($action == 'approve' ? 'fa-circle-check' : 'fa-circle-xmark') ?>"></i>
    </div>

    <h2>Confirm <?= ucfirst($action) ?></h2>
    <p class="subtitle">Processing Package ID: <strong>#<?= $ID ?></strong> </p>

    <form action="<?= base_url('billings/submit_decision') ?>" method="post">
        <input type="hidden" name="ID" value="<?= $ID ?>">
        <input type="hidden" name="action" value="<?= $action ?>">
        <input type="hidden" name="role" value="<?= $role ?>"> 
        
        <div class="form-group">
            <label for="reason">Internal Remarks / Reason:</label>
            <textarea name="reason" id="reason" placeholder="Explain the reason for this decision..." required></textarea>
        </div>
        
        <button type="submit" class="btn-submit">
            Confirm and Notify Team
        </button>
    </form>
</div>

</body>
</html>