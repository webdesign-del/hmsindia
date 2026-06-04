<div class="call-analytics-container">
    <h3><i class="fa fa-phone-square"></i> Call Recording & Analytics Log</h3>
    
    <div class="table-responsive">
        <table class="call-log-table">
            <thead>
                <tr>
                    <th>Call ID</th>
                    <th>Agent Name</th>
                    <th>Direction</th>
                    <th>Status</th>
                    <th>Lead Status</th>
                    <th>Audio Recording</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>177721739093381</td>
                    <td><strong>AGENT 1</strong></td>
                    <td><span class="badge outbound">Outbound</span></td>
                    <td><span class="status-connected">Connected</span></td>
                    <td><span class="lead-pending">Payment Pending</span></td>
                    <td>
                        <audio controls class="custom-audio-player">
                            <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </td>
                </tr>
                <tr>
                    <td>17772173909343381</td>
                    <td><strong>AGENT 2</strong></td>
                    <td><span class="badge outbound">Outbound</span></td>
                    <td><span class="status-connected">Connected</span></td>
                    <td><span class="lead-pending">Payment Pending</span></td>
                    <td>
                        <audio controls class="custom-audio-player">
                            <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
.call-analytics-container {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin: 20px 0;
    font-family: Arial, sans-serif;
}
.call-analytics-container h3 {
    margin-bottom: 20px;
    color: #333;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
}
.call-log-table {
    width: 100%;
    border-collapse: collapse;
}
.call-log-table th {
    background-color: #f8f9fa;
    color: #555;
    font-weight: 600;
    padding: 12px;
    border-bottom: 2px solid #dee2e6;
    text-align: left;
}
.call-log-table td {
    padding: 12px;
    border-bottom: 1px solid #dee2e6;
    color: #444;
    vertical-align: middle;
}
/* बैज और स्टेटस स्टाइल्स */
.badge.outbound {
    background-color: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}
.status-connected {
    color: #28a745;
    font-weight: bold;
}
.lead-pending {
    background-color: #fff3cd;
    color: #856404;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    border: 1px solid #ffeeba;
}
/* ऑडियो प्लेयर को सुव्यवस्थित करना */
.custom-audio-player {
    height: 32px;
    width: 240px;
    outline: none;
}
</style>