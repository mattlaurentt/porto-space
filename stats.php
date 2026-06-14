<?php
// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Enforce login check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 10-Minute Idle Timeout
$timeout_duration = 600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    header("Location: logout.php");
    exit;
}
$_SESSION['last_activity'] = time();

require_once 'koneksidb.php';

// =====================================================================
// DATA FETCHING: TACTICAL METRICS
// =====================================================================

// 1. Overall System Status
$total_missions = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_missions"))['t'];
$total_fleet = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_fleet"))['t'];
$fleet_transit = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_fleet WHERE status_type = 'transit'"))['t'];
$total_unassigned = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_missions WHERE assigned_vessel = 'UNASSIGNED'"))['t'];

// Calculate Fleet Utilization Percentage
$fleet_utilization = ($total_fleet > 0) ? round(($fleet_transit / $total_fleet) * 100) : 0;

// Mission Status for Doughnut Chart
$active_missions = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_missions WHERE mission_status = 'ACTIVE'"))['t'];
$pending_missions = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_missions WHERE mission_status = 'PENDING'"))['t'];

// 2. Fleet Allocation Matrix (Transit vs Docked per Model)
function getFleetCount($koneksidb, $model, $status) {
    return mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_fleet WHERE vessel_name LIKE '$model%' AND status_type = '$status'"))['t'];
}
$atlas_transit = getFleetCount($koneksidb, 'ATLAS', 'transit');
$atlas_docked = getFleetCount($koneksidb, 'ATLAS', 'docked');
$blue_transit = getFleetCount($koneksidb, 'BLUE', 'transit');
$blue_docked = getFleetCount($koneksidb, 'BLUE', 'docked');
$proxima_transit = getFleetCount($koneksidb, 'PROXIMA', 'transit');
$proxima_docked = getFleetCount($koneksidb, 'PROXIMA', 'docked');

// 3. Orbital Vector Traffic
$leo = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_missions WHERE target_orbit LIKE '%LEO%' OR target_orbit LIKE '%Low%'"))['t'];
$meo = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_missions WHERE target_orbit LIKE '%MEO%' OR target_orbit LIKE '%Medium%'"))['t'];
$geo = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_missions WHERE target_orbit LIKE '%GEO%' OR target_orbit LIKE '%Stationary%'"))['t'];
$luna = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) as t FROM tb_missions WHERE target_orbit LIKE '%Moon%' OR target_orbit LIKE '%Lunar%' OR target_orbit LIKE '%NRHO%'"))['t'];

// 4. Raw Terminal Feed (Latest 8 updates)
$terminal_query = "SELECT m.id, m.consignor, m.target_orbit, m.assigned_vessel, m.mission_status, f.status_text 
                   FROM tb_missions m 
                   LEFT JOIN tb_fleet f ON m.assigned_vessel = f.vessel_name 
                   ORDER BY m.id DESC LIMIT 8";
$terminal_result = mysqli_query($koneksidb, $terminal_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tactical Analytics | PORTO SPACE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- JetBrains Mono included ONLY for the Terminal log -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500&family=Inter:wght@300;400;500&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    
    <style>
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.2; } 100% { opacity: 1; } }
        .status-pulse { color: #5cb85c; animation: pulse 2s infinite; margin-right: 4px; }
        
        .dash-panel { background: rgba(2, 3, 6, 0.75); border: 1px solid rgba(255, 255, 255, 0.1); padding: 2rem; border-radius: 4px; }
        
        /* Monospace Terminal Styling */
        .terminal-box {
            background: #000000;
            border: 1px solid #333;
            padding: 1.5rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            color: #5cb85c;
            height: 350px;
            overflow-y: auto;
        }
        
        /* Terminal Line Animation (Fades in sequentially) */
        .terminal-line { 
            margin-bottom: 0.5rem; border-bottom: 1px dashed rgba(92,184,92,0.2); padding-bottom: 0.5rem;
            opacity: 0;
            animation: fadeInTerminal 0.5s forwards;
        }
        @keyframes fadeInTerminal { to { opacity: 1; } }
        
        .terminal-line:nth-child(1) { animation-delay: 0.2s; }
        .terminal-line:nth-child(2) { animation-delay: 0.6s; }
        .terminal-line:nth-child(3) { animation-delay: 1.0s; }
        .terminal-line:nth-child(4) { animation-delay: 1.4s; }
        .terminal-line:nth-child(5) { animation-delay: 1.8s; }
        .terminal-line:nth-child(6) { animation-delay: 2.2s; }
        .terminal-line:nth-child(7) { animation-delay: 2.6s; }
        .terminal-line:nth-child(8) { animation-delay: 3.0s; }
        .terminal-line.terminal-cursor { animation-delay: 3.4s; border: none; }

        .terminal-time { color: var(--text-muted); font-size: 0.7rem; margin-right: 10px; }

        /* Sidebar Styling */
        .sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; width: 250px; background: rgba(2, 3, 6, 0.95); backdrop-filter: blur(15px); border-right: 1px solid var(--border-thin); padding: 120px 1.5rem 0; transition: 0.3s; }
        .sidebar-link { font-family: 'Jost', sans-serif; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; color: var(--text-muted); text-decoration: none; display: flex; align-items: center; padding: 14px 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: 0.3s; }
        .sidebar-link:hover, .sidebar-link.active-tab { color: #ffffff; background: rgba(255,255,255,0.02); border-left: 2px solid var(--accent-status-green); }
        .main-content { margin-left: 250px; padding-top: 120px; transition: 0.3s; }
        
        @media (max-width: 991px) {
            .sidebar { position: static; width: 100%; padding: 40px 1.5rem 20px; border-right: none; border-bottom: 1px solid var(--border-thin); }
            .main-content { margin-left: 0; padding-top: 40px; }
        }
    </style>
</head>
<body style="background-color: #020306; padding-bottom: 80px; position: relative;">
    
    <img src="assets/bg/fleet-bg.webp" alt="" class="sub-hero-bg" aria-hidden="true" style="filter: brightness(0.2) grayscale(0.5);">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <nav class="custom-navbar d-flex justify-content-between align-items-center" style="padding: 1.5rem 3rem; background: rgba(0,0,0,0.6); border-bottom: 1px solid var(--border-thin); backdrop-filter: blur(10px); z-index: 101;">
        <span class="logo" style="font-size: 1.3rem; letter-spacing: 4px;">ANALYTICS HUD</span>
        <div class="d-flex align-items-center gap-4">
            <span class="label-text" style="font-size: 0.8rem; color: #ffffff; letter-spacing: 1px;">
                <span class="status-pulse">●</span> SYSTEM ONLINE // <?php echo htmlspecialchars(strtoupper($_SESSION['admin_username'])); ?>
            </span>
            <a href="logout.php" class="btn-sleek" style="padding: 6px 20px; font-size: 0.75rem;">DISCONNECT</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            
            <!-- FIXED LEFT SIDEBAR PANEL (CROSS-PAGE LINKS) -->
            <aside class="sidebar col-lg-2">
                <span class="label-text d-block mb-3 text-center" style="font-size: 0.65rem; opacity: 0.5;">WORKSPACE NAVIGATION</span>
                <!-- Points back to the dashboard page sections -->
                <a href="dashboard.php#metrics" class="sidebar-link"><i class="bi bi-speedometer2 me-2"></i> Metrics Overview</a>
                <a href="dashboard.php#cargo" class="sidebar-link"><i class="bi bi-box-seam me-2"></i> Cargo Manifests</a>
                <a href="dashboard.php#fleet" class="sidebar-link"><i class="bi bi-rocket-takeoff me-2"></i> Fleet Telemetry</a>
                
                <a href="stats.php" class="sidebar-link active-tab"><i class="bi bi-radar me-2"></i> Tactical HUD</a>
            </aside>

            <main class="main-content col-lg-10 px-md-5">

                <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-secondary pb-3">
                    <div>
                        <span class="label-text d-block mb-1">DATA STREAM 03</span>
                        <h2 class="mb-0 fs-3">System Analytics & Vector Mapping</h2>
                    </div>
                </div>

                <!-- METRICS ROW (Restored to the elegant dashboard style) -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <span class="metric-value"><?php echo $total_missions; ?></span>
                            <span class="label-text"><i class="bi bi-file-earmark-text me-2"></i>Network Registry</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <span class="metric-value"><?php echo $total_fleet; ?></span>
                            <span class="label-text"><i class="bi bi-rocket me-2"></i>Total OTV Assets</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card" style="border-color: #5cb85c; background: rgba(92, 184, 92, 0.05);">
                            <span class="metric-value" style="color: #5cb85c;"><?php echo $fleet_utilization; ?>%</span>
                            <span class="label-text"><i class="bi bi-speedometer2 me-2"></i>Fleet Utilization</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card" style="border-color: var(--accent-status-amber); background: rgba(196, 154, 108, 0.05);">
                            <span class="metric-value" style="color: var(--accent-status-amber);"><?php echo $total_unassigned; ?></span>
                            <span class="label-text"><i class="bi bi-hourglass-split me-2"></i>Awaiting Launch</span>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <!-- 1. FLEET ASSET ALLOCATION (Stacked Bar Chart) -->
                    <div class="col-lg-6">
                        <div class="dash-panel h-100">
                            <div class="d-flex justify-content-between border-bottom border-secondary pb-2 mb-4">
                                <span class="label-text">ASSET DEPLOYMENT MATRIX</span>
                                <i class="bi bi-rocket text-secondary"></i>
                            </div>
                            <div style="height: 280px; position: relative; width: 100%;">
                                <canvas id="fleetMatrixChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- 2. ORBITAL VECTOR TRAFFIC (Stepped Line Chart) -->
                    <div class="col-lg-6">
                        <div class="dash-panel h-100">
                            <div class="d-flex justify-content-between border-bottom border-secondary pb-2 mb-4">
                                <span class="label-text">ORBITAL TRAFFIC VECTORS</span>
                                <i class="bi bi-geo-alt text-secondary"></i>
                            </div>
                            <div style="height: 280px; position: relative; width: 100%;">
                                <canvas id="orbitVectorChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- 3. LIVE TERMINAL FEED -->
                    <div class="col-lg-8">
                        <div class="dash-panel h-100">
                            <div class="d-flex justify-content-between border-bottom border-secondary pb-2 mb-4">
                                <span class="label-text">LIVE TERMINAL READOUT</span>
                            </div>
                            
                            <div class="terminal-box">
                                <?php if (mysqli_num_rows($terminal_result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($terminal_result)): ?>
                                        <div class="terminal-line">
                                            <span class="terminal-time">[<?php echo date('H:i:s'); ?>]</span> 
                                            <span style="color: #fff;">CMD_RECV:</span> 
                                            Mission <span style="color: #c49a6c;">ORB-00<?php echo $row['id']; ?></span> 
                                            (<?php echo htmlspecialchars($row['consignor']); ?>). 
                                            
                                            <?php if ($row['assigned_vessel'] == 'UNASSIGNED'): ?>
                                                Status: <span style="color: #d9534f;">PENDING INTEGRATION</span>. Awaiting OTV allocation to <?php echo htmlspecialchars($row['target_orbit']); ?>.
                                            <?php else: ?>
                                                OTV Assigned: <span style="color: #5cb85c;"><?php echo htmlspecialchars($row['assigned_vessel']); ?></span>. 
                                                Target: <?php echo htmlspecialchars($row['target_orbit']); ?>. 
                                                Telemetry status: [ <?php echo htmlspecialchars($row['status_text'] ?? 'UPLINK ESTABLISHED'); ?> ].
                                            <?php endif; ?>
                                        </div>
                                    <?php endwhile; ?>
                                    <div class="terminal-line terminal-cursor" style="border: none; color: var(--text-muted);">> Awaiting further system commands_ <span class="status-pulse">█</span></div>
                                <?php else: ?>
                                    <div class="terminal-line">> System ready. No active logs found in database. <span class="status-pulse">█</span></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 4. CONTRACT STATUS (Doughnut Chart - FIXED ALIGNMENT) -->
                    <div class="col-lg-4">
                        <div class="dash-panel h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between border-bottom border-secondary pb-2 mb-4">
                                <span class="label-text">CONTRACT STATUS</span>
                                <i class="bi bi-file-earmark-text text-secondary"></i>
                            </div>
                            
                            <!-- Centered Chart Canvas -->
                            <div style="flex-grow: 1; position: relative; width: 100%; min-height: 180px; display: flex; justify-content: center; align-items: center;">
                                <canvas id="statusChart"></canvas>
                                <!-- High-Tech overlay number in the center of the doughnut hole -->
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 2rem; color: #ffffff; line-height: 1;"><?php echo $total_missions; ?></span>
                                    <span class="label-text d-block opacity-50" style="font-size: 0.6rem;">TOTAL</span>
                                </div>
                            </div>

                            <!-- Custom Info Numbers beneath the chart replacing the legend -->
                            <div class="row text-center mt-3 pt-3 border-top border-secondary">
                                <div class="col-6 border-end border-secondary">
                                    <span class="label-text d-block opacity-50 mb-1" style="font-size: 0.65rem;"><i class="bi bi-square-fill me-1" style="color: #5cb85c;"></i>ACTIVE</span>
                                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 1.5rem; color: #5cb85c;"><?php echo $active_missions; ?></span>
                                </div>
                                <div class="col-6">
                                    <span class="label-text d-block opacity-50 mb-1" style="font-size: 0.65rem;"><i class="bi bi-square-fill me-1" style="color: #c49a6c;"></i>PENDING</span>
                                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 1.5rem; color: var(--accent-status-amber);"><?php echo $pending_missions; ?></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Chart.js Engine CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tactical Chart Configurations -->
    <script>
        const colorGreen = '#5cb85c';
        const colorAmber = '#c49a6c';
        const gridColor = 'rgba(255, 255, 255, 0.05)';
        const textFont = { family: 'Jost, sans-serif', size: 11 };

        Chart.defaults.color = 'rgba(255, 255, 255, 0.6)';

        // 1. FLEET DEPLOYMENT MATRIX
        const ctxFleet = document.getElementById('fleetMatrixChart').getContext('2d');
        new Chart(ctxFleet, {
            type: 'bar',
            data: {
                labels: ['ATLAS', 'BLUE', 'PROXIMA'],
                datasets: [
                    {
                        label: ' IN TRANSIT',
                        data: [<?php echo $atlas_transit; ?>, <?php echo $blue_transit; ?>, <?php echo $proxima_transit; ?>],
                        backgroundColor: 'rgba(92, 184, 92, 0.2)',
                        borderColor: colorGreen,
                        borderWidth: 1
                    },
                    {
                        label: ' DOCKED',
                        data: [<?php echo $atlas_docked; ?>, <?php echo $blue_docked; ?>, <?php echo $proxima_docked; ?>],
                        backgroundColor: 'rgba(196, 154, 108, 0.2)',
                        borderColor: colorAmber,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    x: { stacked: true, grid: { display: false }, ticks: { font: textFont } },
                    y: { stacked: true, grid: { color: gridColor }, ticks: { font: textFont, stepSize: 1 } }
                },
                plugins: { legend: { position: 'top', labels: { font: textFont, boxWidth: 12 } } }
            }
        });

        // 2. ORBITAL VECTOR TRAFFIC
        const ctxOrbit = document.getElementById('orbitVectorChart').getContext('2d');
        new Chart(ctxOrbit, {
            type: 'line',
            data: {
                labels: ['LEO', 'MEO', 'GEO', 'LUNAR'],
                datasets: [{
                    label: 'Payload Count',
                    data: [<?php echo $leo; ?>, <?php echo $meo; ?>, <?php echo $geo; ?>, <?php echo $luna; ?>],
                    borderColor: '#ffffff',
                    backgroundColor: 'rgba(255, 255, 255, 0.05)',
                    borderWidth: 2,
                    stepped: true, 
                    fill: true,
                    pointBackgroundColor: colorGreen,
                    pointBorderColor: '#000',
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    x: { grid: { color: gridColor }, ticks: { font: textFont } },
                    y: { grid: { color: gridColor }, ticks: { font: textFont, stepSize: 1 } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 3. CONTRACT STATUS (Doughnut - Now perfectly centered with custom HTML stats)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['ACTIVE', 'PENDING'],
                datasets: [{
                    data: [<?php echo $active_missions; ?>, <?php echo $pending_missions; ?>],
                    backgroundColor: [colorGreen, colorAmber],
                    borderColor: '#020306',
                    borderWidth: 2
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                cutout: '80%', // Made the doughnut slightly thinner so the center number looks better
                layout: { padding: 10 }, 
                plugins: { 
                    legend: { 
                        display: false // Disabled the default legend causing the misalignment!
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
        
    </script>
    
    <script>
        let idleTimer;
        function resetTimer() {
            clearTimeout(idleTimer);
            idleTimer = setTimeout(() => { window.location.href = 'logout.php'; }, 600000); 
        }
        window.onload = resetTimer;
        window.onmousemove = resetTimer;
        window.onkeypress = resetTimer;  
    </script>
</body>
</html>