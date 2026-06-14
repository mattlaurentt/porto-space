<?php
// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 1. Enforce login check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 2. SECURITY ENHANCEMENT: 10-Minute Idle Timeout
$timeout_duration = 600;
if (isset($_SESSION['last_activity'])) {
    $elapsed_time = time() - $_SESSION['last_activity'];
    if ($elapsed_time > $timeout_duration) {
        header("Location: logout.php");
        exit;
    }
}
$_SESSION['last_activity'] = time();

// 3. Database Connection
require_once 'koneksidb.php';
$message = "";

// 4. CHECK FOR TEMPORARY REDIRECT MESSAGES
if (isset($_SESSION['temp_dashboard_msg'])) {
    $message = $_SESSION['temp_dashboard_msg'];
    unset($_SESSION['temp_dashboard_msg']);
}

// 5. PROCESS ADMINISTRATIVE ACTIONS (CRUD)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ACTION A: Assign ship WITH COMBINED MODEL & NUMBER VERIFICATION
    if (isset($_POST['action']) && $_POST['action'] == 'assign_vessel') {
        $mission_id = (int)$_POST['mission_id'];
        $vessel_model = mysqli_real_escape_string($koneksidb, $_POST['vessel_model']);
        $vessel_num = mysqli_real_escape_string($koneksidb, trim($_POST['vessel_num']));
        
        if ($vessel_model == 'UNASSIGNED' || empty($vessel_num)) {
            $vessel_name = 'UNASSIGNED';
        } else {
            $vessel_num_padded = str_pad($vessel_num, 2, '0', STR_PAD_LEFT);
            $vessel_name = $vessel_model . "-" . $vessel_num_padded;
        }
        
        $vessel_exists = false;
        if ($vessel_name !== 'UNASSIGNED') {
            $check_query = "SELECT id FROM tb_fleet WHERE vessel_name = '$vessel_name' LIMIT 1";
            $check_result = mysqli_query($koneksidb, $check_query);
            if (mysqli_num_rows($check_result) == 1) {
                $vessel_exists = true;
            }
        }
        
        if ($vessel_name == 'UNASSIGNED' || $vessel_exists) {
            $status_flag = ($vessel_name == 'UNASSIGNED') ? 'PENDING' : 'ACTIVE';
            $update_query = "UPDATE tb_missions SET assigned_vessel = '$vessel_name', mission_status = '$status_flag' WHERE id = $mission_id";
            
            if (mysqli_query($koneksidb, $update_query)) {
                $msg = "Vessel " . htmlspecialchars($vessel_name) . " successfully assigned to Mission #" . $mission_id;
                
                if ($vessel_name !== 'UNASSIGNED') {
                    $get_mission = mysqli_query($koneksidb, "SELECT target_orbit FROM tb_missions WHERE id = $mission_id LIMIT 1");
                    if ($get_mission && mysqli_num_rows($get_mission) > 0) {
                        $mission_data = mysqli_fetch_assoc($get_mission);
                        $target_orbit = mysqli_real_escape_string($koneksidb, $mission_data['target_orbit']);
                        
                        $auto_launch_query = "UPDATE tb_fleet SET 
                                              location = 'Cape Canaveral Pad 4A', 
                                              eta = 'T-Minus 24 Hours', 
                                              status_text = 'PRE-FLIGHT / FUELING', 
                                              status_type = 'docked' 
                                              WHERE vessel_name = '$vessel_name'";
                        mysqli_query($koneksidb, $auto_launch_query);
                        $msg .= " // OTV moved to Launchpad 4A for pre-flight payload integration.";
                    }
                }
                $_SESSION['temp_dashboard_msg'] = $msg;
            }
        } else {
            $_SESSION['temp_dashboard_msg'] = "CRITICAL_ERROR: Spacecraft Unit " . htmlspecialchars($vessel_name) . " is not registered in active telemetry. Operation aborted.";
        }
        
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
    
    // ACTION B: Update ship telemetry
    if (isset($_POST['action']) && $_POST['action'] == 'update_telemetry') {
        $vessel_id = (int)$_POST['vessel_id'];
        $location = mysqli_real_escape_string($koneksidb, $_POST['location']);
        $eta = mysqli_real_escape_string($koneksidb, $_POST['eta']);
        $status_text = mysqli_real_escape_string($koneksidb, $_POST['status_text']);
        $status_type = mysqli_real_escape_string($koneksidb, $_POST['status_type']);
        
        $update_query = "UPDATE tb_fleet SET location = '$location', eta = '$eta', status_text = '$status_text', status_type = '$status_type' WHERE id = $vessel_id";
        if (mysqli_query($koneksidb, $update_query)) {
            $_SESSION['temp_dashboard_msg'] = "Telemetry broadcast successful for Vessel ID #" . $vessel_id;
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    // ACTION C: Create/Register New Cargo Mission
    if (isset($_POST['action']) && $_POST['action'] == 'add_mission') {
        $consignor = mysqli_real_escape_string($koneksidb, trim($_POST['consignor']));
        $email = mysqli_real_escape_string($koneksidb, trim($_POST['email']));
        $target_orbit = mysqli_real_escape_string($koneksidb, trim($_POST['target_orbit']));
        $parameters = mysqli_real_escape_string($koneksidb, trim($_POST['parameters']));
        
        $add_query = "INSERT INTO tb_missions (consignor, email, target_orbit, parameters, mission_status) 
                      VALUES ('$consignor', '$email', '$target_orbit', '$parameters', 'PENDING')";
        if (mysqli_query($koneksidb, $add_query)) {
            $_SESSION['temp_dashboard_msg'] = "New cargo mission registry successfully created for " . htmlspecialchars($consignor);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    // ACTION D: Delete/Purge Cargo Mission
    if (isset($_POST['action']) && $_POST['action'] == 'delete_mission') {
        $mission_id = (int)$_POST['mission_id'];
        $delete_query = "DELETE FROM tb_missions WHERE id = $mission_id";
        if (mysqli_query($koneksidb, $delete_query)) {
            $_SESSION['temp_dashboard_msg'] = "Mission parameters for ID #" . $mission_id . " successfully purged from system.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    // ACTION E: Create/Register New OTV Spacecraft Unit
    if (isset($_POST['action']) && $_POST['action'] == 'add_vessel') {
        $vessel_name = mysqli_real_escape_string($koneksidb, trim(strtoupper($_POST['vessel_name'])));
        $location = mysqli_real_escape_string($koneksidb, trim($_POST['location']));
        $eta = mysqli_real_escape_string($koneksidb, trim($_POST['eta']));
        $status_text = mysqli_real_escape_string($koneksidb, trim($_POST['status_text']));
        $status_type = mysqli_real_escape_string($koneksidb, $_POST['status_type']);
        
        $add_query = "INSERT INTO tb_fleet (vessel_name, location, eta, status_text, status_type) 
                      VALUES ('$vessel_name', '$location', '$eta', '$status_text', '$status_type')";
        if (mysqli_query($koneksidb, $add_query)) {
            $_SESSION['temp_dashboard_msg'] = "New spacecraft asset registered: " . htmlspecialchars($vessel_name) . " linked to active telemetry.";
            header("Location: dashboard.php#fleet");
            exit;
        }
    }

    // ACTION F: Delete/Retire Spacecraft Unit
    if (isset($_POST['action']) && $_POST['action'] == 'delete_vessel') {
        $vessel_id = (int)$_POST['vessel_id'];
        $delete_query = "DELETE FROM tb_fleet WHERE id = $vessel_id";
        if (mysqli_query($koneksidb, $delete_query)) {
            $_SESSION['temp_dashboard_msg'] = "Spacecraft Asset ID #" . $vessel_id . " successfully retired and de-registered.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }
}

// ------------------------------------------------------------
// 6A. MISSIONS PAGINATION & FILTER LOGIC
// ------------------------------------------------------------
$filter_status = isset($_GET['filter_status']) ? mysqli_real_escape_string($koneksidb, $_GET['filter_status']) : 'all';
$filter_model = isset($_GET['filter_model']) ? mysqli_real_escape_string($koneksidb, $_GET['filter_model']) : 'all';
$sort_order = isset($_GET['sort_order']) ? mysqli_real_escape_string($koneksidb, $_GET['sort_order']) : 'newest';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$where_clause = " WHERE 1=1";
if ($filter_status == 'assigned') {
    $where_clause .= " AND assigned_vessel != 'UNASSIGNED'";
} elseif ($filter_status == 'unassigned') {
    $where_clause .= " AND assigned_vessel = 'UNASSIGNED'";
}

if ($filter_model == 'atlas') {
    $where_clause .= " AND assigned_vessel LIKE 'ATLAS%'";
} elseif ($filter_model == 'blue') {
    $where_clause .= " AND assigned_vessel LIKE 'BLUE%'";
} elseif ($filter_model == 'proxima') {
    $where_clause .= " AND assigned_vessel LIKE 'PROXIMA%'";
}

$total_sql = "SELECT COUNT(*) as total FROM tb_missions" . $where_clause;
$total_result = mysqli_query($koneksidb, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);
if ($total_pages < 1) $total_pages = 1;
if ($page > $total_pages) $page = $total_pages;
$offset = ($page - 1) * $limit;

$missions_sql = "SELECT * FROM tb_missions" . $where_clause;
if ($sort_order == 'oldest') {
    $missions_sql .= " ORDER BY id ASC";
} else {
    $missions_sql .= " ORDER BY id DESC";
}
$missions_sql .= " LIMIT $limit OFFSET $offset";
$missions_result = mysqli_query($koneksidb, $missions_sql);
$total_missions_displayed = mysqli_num_rows($missions_result);

// ------------------------------------------------------------
// 6B. FLEET PAGINATION LOGIC & DROPDOWN LIST FETCH
// ------------------------------------------------------------
// Fetch ALL vessels purely for the Assignment dropdowns (so we don't lose options)
$fleet_all_query = "SELECT * FROM tb_fleet ORDER BY vessel_name ASC";
$fleet_all_result = mysqli_query($koneksidb, $fleet_all_query);
$total_fleet = mysqli_num_rows($fleet_all_result);

// Fetch the total number of cargo requests currently unassigned
$unassigned_count_query = mysqli_query($koneksidb, "SELECT COUNT(*) as total FROM tb_missions WHERE assigned_vessel = 'UNASSIGNED'");
$unassigned_row = mysqli_fetch_assoc($unassigned_count_query);
$total_unassigned = $unassigned_row['total'];

$vessels_list = [];
if ($total_fleet > 0) {
    while($vessel = mysqli_fetch_assoc($fleet_all_result)) {
        $vessels_list[] = $vessel;
    }
}

// Fleet Table Pagination
$fleet_limit = 10; // Limit fleet table to 10 rows
$fleet_page = isset($_GET['fleet_page']) ? (int)$_GET['fleet_page'] : 1;
if ($fleet_page < 1) $fleet_page = 1;

$fleet_total_pages = ceil($total_fleet / $fleet_limit);
if ($fleet_total_pages < 1) $fleet_total_pages = 1;
if ($fleet_page > $fleet_total_pages) $fleet_page = $fleet_total_pages;
$fleet_offset = ($fleet_page - 1) * $fleet_limit;

// Fetch PAGINATED fleet data for the telemetry table
$fleet_paginated_query = "SELECT * FROM tb_fleet ORDER BY id ASC LIMIT $fleet_limit OFFSET $fleet_offset";
$fleet_result = mysqli_query($koneksidb, $fleet_paginated_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Center | PORTO SPACE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    
    <style>
        html { scroll-padding-top: 130px; }
        
        .glass-select, .glass-input-sm {
            background: rgba(0,0,0,0.4);
            border: 1px solid var(--border-thin);
            color: #ffffff;
            font-size: 0.8rem;
            padding: 6px 10px;
            font-family: 'Jost', sans-serif;
            width: 100%;
            transition: 0.3s;
        }
        .glass-select:focus, .glass-input-sm:focus { outline: none; border-color: var(--accent-status-white); box-shadow: 0 0 10px rgba(92,184,92,0.3); }
        .glass-select option { background: #020306; color: #ffffff; }
        
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.2; } 100% { opacity: 1; } }
        .status-pulse { color: #5cb85c; animation: pulse 2s infinite; margin-right: 4px; }
        
        .dash-panel { background: rgba(2, 3, 6, 0.75); border: 1px solid rgba(255, 255, 255, 0.1); padding: 2rem; border-radius: 4px; }
        
        /* Sticky Left Sidebar */
        .sidebar {
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 100;
            width: 250px;
            background: rgba(2, 3, 6, 0.95);
            backdrop-filter: blur(15px);
            border-right: 1px solid var(--border-thin);
            padding: 120px 1.5rem 0;
            transition: 0.3s;
        }
        .sidebar-link {
            font-family: 'Jost', sans-serif;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 2px;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 14px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: 0.3s;
        }
        .sidebar-link:hover {
            color: #ffffff;
            padding-left: 15px;
            background: rgba(255,255,255,0.02);
            border-left: 2px solid var(--accent-status-green);
        }
        .main-content {
            margin-left: 250px;
            padding-top: 120px;
            transition: 0.3s;
        }

        .scrollable-table-container {
            max-height: 520px; 
            overflow-y: auto;  
            border: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 1.5rem;
        }
        .scrollable-table-container::-webkit-scrollbar { width: 6px; }
        .scrollable-table-container::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        .scrollable-table-container::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }
        .scrollable-table-container::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
        
        @media (max-width: 991px) {
            .sidebar {
                position: static;
                width: 100%;
                padding: 40px 1.5rem 20px;
                border-right: none;
                border-bottom: 1px solid var(--border-thin);
            }
            .main-content {
                margin-left: 0;
                padding-top: 40px;
            }
            .table-custom {
                min-width: 900px;
            }
        }
    </style>
</head>
<body style="background-color: #020306; padding-bottom: 80px; position: relative;">
    
    <img src="assets/bg/fleet-bg.webp" alt="" class="sub-hero-bg" aria-hidden="true" style="filter: brightness(0.2) grayscale(0.5);">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <nav class="custom-navbar d-flex justify-content-between align-items-center" style="padding: 1.5rem 3rem; background: rgba(0,0,0,0.6); border-bottom: 1px solid var(--border-thin); backdrop-filter: blur(10px); z-index: 101;">
        <span class="logo" style="font-size: 1.3rem; letter-spacing: 4px;">COMMAND CENTER</span>
        
        <div class="d-flex align-items-center gap-4">
            <span class="label-text" style="font-size: 0.8rem; color: #ffffff; letter-spacing: 1px;">
                <span class="status-pulse">●</span> SYSTEM ONLINE // <?php echo htmlspecialchars(strtoupper($_SESSION['admin_username'])); ?>
            </span>
            <a href="logout.php" class="btn-sleek" style="padding: 6px 20px; font-size: 0.75rem;">DISCONNECT</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            
            <aside class="sidebar col-lg-2">
                <span class="label-text d-block mb-3 text-center" style="font-size: 0.65rem; opacity: 0.5;">WORKSPACE NAVIGATION</span>
                <a href="#metrics" class="sidebar-link"><i class="bi bi-speedometer2 me-2 "></i> Metrics Overview</a>
                <a href="#cargo" class="sidebar-link"><i class="bi bi-box-seam me-2"></i> Cargo Manifests</a>
                <a href="#fleet" class="sidebar-link"><i class="bi bi-rocket-takeoff me-2"></i> Fleet Telemetry</a>
                <a href="stats.php" class="sidebar-link"><i class="bi bi-radar me-2"></i> Tactical HUD</a>
            </aside>

            <main class="main-content col-lg-10 px-md-5">
                
                <?php if (!empty($message)): ?>
                    <?php if (strpos($message, 'ERROR') !== false || strpos($message, 'CRITICAL') !== false): ?>
                        <div class="alert text-start mb-5" style="background: rgba(196, 154, 108, 0.15); border: 1px solid var(--accent-status-amber); color: var(--accent-status-amber); padding: 15px; font-size: 0.85rem; letter-spacing: 1px; box-shadow: 0 0 15px rgba(196,154,108,0.2);">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>SEC_ALERT:</strong> <?php echo $message; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert text-start mb-5" style="background: rgba(92, 184, 92, 0.15); border: 1px solid #5cb85c; color: #5cb85c; padding: 15px; font-size: 0.85rem; letter-spacing: 1px; box-shadow: 0 0 15px rgba(92,184,92,0.2);">
                            <i class="bi bi-broadcast me-2"></i><strong>TELEMETRY_UPLINK:</strong> <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- SECTION: METRICS OVERVIEW -->
                <div id="metrics" class="scroll-offset"></div>
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="metric-card">
                            <span class="metric-value"><?php echo $total_records; ?></span>
                            <span class="label-text"><i class="bi bi-file-earmark-text me-2"></i>Active Manifests</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="metric-card">
                            <span class="metric-value"><?php echo $total_fleet; ?></span>
                            <span class="label-text"><i class="bi bi-rocket me-2"></i>Fleet OTVs</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="metric-card" style="border-color: <?php echo ($total_unassigned > 0) ? 'var(--accent-status-amber)' : '#5cb85c'; ?>; background: <?php echo ($total_unassigned > 0) ? 'rgba(196, 154, 108, 0.05)' : 'rgba(92, 184, 92, 0.05)'; ?>;">
                            <span class="metric-value" style="color: <?php echo ($total_unassigned > 0) ? 'var(--accent-status-amber)' : '#5cb85c'; ?>;">
                                <?php echo $total_unassigned; ?>
                            </span>
                            <span class="label-text"><i class="bi bi-hourglass-split me-2"></i>Awaiting Launch</span>
                        </div>
                    </div>

                <!-- SECTION 1: INBOUND CARGO REGISTRY (SCROLLABLE & MULTI-FILTER) -->
                <div id="cargo" class="scroll-offset"></div>
                <div class="dash-panel mb-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 border-bottom border-secondary pb-3 gap-3">
                        <div>
                            <span class="label-text d-block mb-1">DATA STREAM 01</span>
                            <h2 class="mb-0 fs-3">Cargo Manifests</h2>
                        </div>
                        <button class="btn-sleek" type="button" data-bs-toggle="collapse" data-bs-target="#addMissionCollapse" aria-expanded="false" style="padding: 6px 15px; font-size: 0.75rem;">
                            <i class="bi bi-plus-circle me-1"></i> Register Cargo
                        </button>
                    </div>

                    <!-- MULTI-FILTER FORM -->
                    <div class="row mb-4 g-2">
                        <div class="col-12">
                            <form action="dashboard.php" method="get" class="row g-2 align-items-end" style="background: rgba(0,0,0,0.2); padding: 15px; border: 1px solid rgba(255,255,255,0.05);">
                                <input type="hidden" name="page" value="1">
                                <!-- Maintain fleet page when filtering missions -->
                                <input type="hidden" name="fleet_page" value="<?php echo $fleet_page; ?>"> 
                                
                                <div class="col-md-3">
                                    <span class="label-text d-block mb-1" style="font-size: 0.65rem; opacity: 0.8;">STATUS FILTER</span>
                                    <select name="filter_status" class="glass-select">
                                        <option value="all" <?php echo ($filter_status == 'all') ? 'selected' : ''; ?>>SHOW ALL CARGO</option>
                                        <option value="assigned" <?php echo ($filter_status == 'assigned') ? 'selected' : ''; ?>>ASSIGNED ONLY</option>
                                        <option value="unassigned" <?php echo ($filter_status == 'unassigned') ? 'selected' : ''; ?>>AWAITING LAUNCH</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <span class="label-text d-block mb-1" style="font-size: 0.65rem; opacity: 0.8;">OTV MODEL FILTER</span>
                                    <select name="filter_model" class="glass-select">
                                        <option value="all" <?php echo ($filter_model == 'all') ? 'selected' : ''; ?>>ALL OTV MODELS</option>
                                        <option value="atlas" <?php echo ($filter_model == 'atlas') ? 'selected' : ''; ?>>ATLAS COURIERS</option>
                                        <option value="blue" <?php echo ($filter_model == 'blue') ? 'selected' : ''; ?>>BLUE COURIERS</option>
                                        <option value="proxima" <?php echo ($filter_model == 'proxima') ? 'selected' : ''; ?>>PROXIMA COURIERS</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <span class="label-text d-block mb-1" style="font-size: 0.65rem; opacity: 0.8;">SORT BY TIME</span>
                                    <select name="sort_order" class="glass-select">
                                        <option value="newest" <?php echo ($sort_order == 'newest') ? 'selected' : ''; ?>>NEWEST FIRST</option>
                                        <option value="oldest" <?php echo ($sort_order == 'oldest') ? 'selected' : ''; ?>>OLDEST FIRST</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <span class="label-text d-block mb-1" style="font-size: 0.65rem; opacity: 0.8;">ITEMS PER PAGE</span>
                                    <select name="limit" class="glass-select">
                                        <option value="10" <?php echo ($limit == 10) ? 'selected' : ''; ?>>10 ITEMS</option>
                                        <option value="25" <?php echo ($limit == 25) ? 'selected' : ''; ?>>25 ITEMS</option>
                                        <option value="50" <?php echo ($limit == 50) ? 'selected' : ''; ?>>50 ITEMS</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn-sleek w-100" style="padding: 7px 0; font-size: 0.75rem;">
                                        APPLY FILTERS
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- COLLAPSIBLE FORM: ADD NEW MISSION -->
                    <div class="collapse mb-4" id="addMissionCollapse">
                        <div class="glass-panel p-4" style="background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.15);">
                            <span class="label-text d-block mb-3" style="font-size: 0.8rem; color: var(--accent-status-amber);">[ REGISTER NEW INBOUND CONTRACT ]</span>
                            <form action="dashboard.php" method="post">
                                <input type="hidden" name="action" value="add_mission">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" name="consignor" class="glass-input-sm" placeholder="Consignor Name (e.g. Orbis)" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="email" name="email" class="glass-input-sm" placeholder="Officer Email" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="target_orbit" class="glass-input-sm" placeholder="Target Orbit (e.g. LEO 550km)" required>
                                    </div>
                                    <div class="col-12">
                                        <textarea name="parameters" class="glass-input-sm" rows="2" placeholder="Mission specifications & payload requirements..."></textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn-sleek" style="padding: 6px 20px; font-size: 0.75rem;">Submit Registry</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- VERTICAL SCROLL CONTAINER FOR CARGO REGISTRY -->
                    <div class="scrollable-table-container">
                        <table class="table-custom w-100 m-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 20%;">Consignor (Entity)</th>
                                    <th style="width: 30%;">Target Orbit / Parameters</th>
                                    <th style="width: 15%;">Assigned Vessel</th>
                                    <th style="width: 22%; text-align: right;">Dispatch Allocation</th>
                                    <th style="width: 8%; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($total_missions_displayed > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($missions_result)): ?>
                                        <tr>
                                            <td class="text-secondary">#<?php echo $row['id']; ?></td>
                                            <td>
                                                <strong class="text-white"><?php echo htmlspecialchars($row['consignor']); ?></strong><br>
                                                <span class="muted-text" style="font-size: 0.75rem; text-transform: lowercase;"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($row['email']); ?></span>
                                            </td>
                                            <td>
                                                <span class="status-badge status--transit">● <?php echo htmlspecialchars($row['target_orbit']); ?></span><br>
                                                <span class="muted-text" style="font-size: 0.8rem; display: block; margin-top: 5px;"><?php echo htmlspecialchars($row['parameters']); ?></span>
                                            </td>
                                            <td style="font-family: 'Jost', sans-serif; letter-spacing: 1px;">
                                                <?php if ($row['assigned_vessel'] == 'UNASSIGNED'): ?>
                                                    <span style="color: var(--accent-status-amber);"><i class="bi bi-hourglass-split me-1"></i>AWAITING</span>
                                                <?php else: ?>
                                                    <span style="color: #ffffff; letter-spacing: 1px;"><i class="bi bi-rocket me-2"></i><?php echo htmlspecialchars($row['assigned_vessel']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <!-- Action form automatically retains filters in the query string -->
                                                <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post" class="d-flex gap-2 justify-content-end">
                                                    <input type="hidden" name="action" value="assign_vessel">
                                                    <input type="hidden" name="mission_id" value="<?php echo $row['id']; ?>">
                                                    
                                                    <select name="vessel_model" class="glass-select" style="width: 110px;">
                                                        <option value="UNASSIGNED">Select Model</option>
                                                        <option value="ATLAS">ATLAS</option>
                                                        <option value="BLUE">BLUE</option>
                                                        <option value="PROXIMA">PROXIMA</option>
                                                    </select>
                                                    
                                                    <input type="text" name="vessel_num" class="glass-input-sm text-center" placeholder="01" style="width: 50px;" autocomplete="off">
                                                    
                                                    <button type="submit" class="btn-sleek" style="padding: 4px 15px; font-size: 0.75rem;">Assign</button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post" onsubmit="return confirm('Secure Warning: Purge Mission registry ID #<?php echo $row['id']; ?>? This action is irreversible.');">
                                                    <input type="hidden" name="action" value="delete_mission">
                                                    <input type="hidden" name="mission_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn-sleek" style="padding: 6px 12px; font-size: 0.75rem; border-color: #d9534f; color: #d9534f;"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 muted-text"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No inbound manifests found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- MINIMALIST PAGINATION CONTROLS FOR CARGO -->
                    <?php if ($total_pages > 1): ?>
                        <div class="d-flex justify-content-end align-items-center mt-4 pt-3 border-top border-secondary gap-3">
                            <span class="label-text" style="font-size: 0.7rem; opacity: 0.5;">PAGE <?php echo $page; ?> OF <?php echo $total_pages; ?></span>
                            
                            <div class="d-flex gap-2">
                                <a href="dashboard.php?page=<?php echo $page - 1; ?>&filter_status=<?php echo $filter_status; ?>&filter_model=<?php echo $filter_model; ?>&sort_order=<?php echo $sort_order; ?>&limit=<?php echo $limit; ?>&fleet_page=<?php echo $fleet_page; ?>#cargo" 
                                   class="btn-sleek" 
                                   style="padding: 4px 12px; font-size: 0.8rem; <?php echo ($page <= 1) ? 'opacity: 0.2; pointer-events: none;' : ''; ?>">
                                    &lt;
                                </a>
                                
                                <a href="dashboard.php?page=<?php echo $page + 1; ?>&filter_status=<?php echo $filter_status; ?>&filter_model=<?php echo $filter_model; ?>&sort_order=<?php echo $sort_order; ?>&limit=<?php echo $limit; ?>&fleet_page=<?php echo $fleet_page; ?>#cargo" 
                                   class="btn-sleek" 
                                   style="padding: 4px 12px; font-size: 0.8rem; <?php echo ($page >= $total_pages) ? 'opacity: 0.2; pointer-events: none;' : ''; ?>">
                                    &gt;
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SECTION 2: ACTIVE FLEET TELEMETRY CONTROL -->
                <div id="fleet" class="scroll-offset"></div>
                <div class="dash-panel">
                    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-secondary pb-3">
                        <div>
                            <span class="label-text d-block mb-1">DATA STREAM 02</span>
                            <h2 class="mb-0 fs-3">Fleet Telemetry Control</h2>
                        </div>
                        <button class="btn-sleek" type="button" data-bs-toggle="collapse" data-bs-target="#addVesselCollapse" aria-expanded="false" style="padding: 6px 15px; font-size: 0.75rem;">
                            <i class="bi bi-plus-circle me-1"></i> Register OTV
                        </button>
                    </div>

                    <!-- COLLAPSIBLE FORM: REGISTER NEW VESSEL -->
                    <div class="collapse mb-4" id="addVesselCollapse">
                        <div class="glass-panel p-4" style="background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.15);">
                            <span class="label-text d-block mb-3" style="font-size: 0.8rem; color: var(--accent-status-amber);">[ REGISTER NEW OTV PHYSICAL UNIT ]</span>
                            <form action="dashboard.php" method="post">
                                <input type="hidden" name="action" value="add_vessel">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <input type="text" name="vessel_name" class="glass-input-sm" placeholder="Unit Name (e.g. ATLAS-09)" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="location" class="glass-input-sm" value="Earth Base" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="eta" class="glass-input-sm" value="--" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="status_text" class="glass-input-sm" value="IDLE / IN DOCK" required>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="status_type" class="glass-select">
                                            <option value="docked">DOCKED</option>
                                            <option value="transit">TRANSIT</option>
                                        </select>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn-sleek" style="padding: 6px 20px; font-size: 0.75rem;">Integrate Unit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- VERTICAL SCROLL CONTAINER FOR FLEET TELEMETRY -->
                    <div class="scrollable-table-container">
                        <table class="table-custom w-100 m-0">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">OTV Unit</th>
                                    <th style="width: 25%;">Current Location</th>
                                    <th style="width: 15%;">ETA</th>
                                    <th style="width: 15%;">Status Code</th>
                                    <th style="width: 12%;">Marker</th>
                                    <th style="width: 10%;">Broadcast</th>
                                    <th style="width: 8%; text-align: center;">Retire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($fleet_result) > 0): ?>
                                    <?php while($vessel = mysqli_fetch_assoc($fleet_result)): ?>
                                        <tr>
                                            <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
                                                <input type="hidden" name="action" value="update_telemetry">
                                                <input type="hidden" name="vessel_id" value="<?php echo $vessel['id']; ?>">
                                                
                                                <td><strong style="color: #ffffff; letter-spacing: 1px;"><i class="bi bi-rocket me-2"></i><?php echo htmlspecialchars($vessel['vessel_name']); ?></strong></td>
                                                
                                                <td>
                                                    <input type="text" name="location" list="location-presets-<?php echo $vessel['id']; ?>" class="glass-input-sm" value="<?php echo htmlspecialchars($vessel['location']); ?>" required autocomplete="off">
                                                    <datalist id="location-presets-<?php echo $vessel['id']; ?>">
                                                        <option value="Earth Base">
                                                        <option value="Low Earth Orbit (LEO)">
                                                        <option value="Medium Earth Orbit (MEO)">
                                                        <option value="GEO-Stationary Belt">
                                                        <option value="Near the Moon">
                                                        <option value="Atmosphere Entry">
                                                        <option value="Deep Space">
                                                    </datalist>
                                                </td>
                                                
                                                <td>
                                                    <input type="text" name="eta" list="eta-presets-<?php echo $vessel['id']; ?>" class="glass-input-sm" value="<?php echo htmlspecialchars($vessel['eta']); ?>" required autocomplete="off">
                                                    <datalist id="eta-presets-<?php echo $vessel['id']; ?>">
                                                        <option value="--">
                                                        <option value="Immediate">
                                                        <option value="TBD (Ascending)">
                                                        <option value="Under 1 Hour">
                                                        <option value="24 Hours+">
                                                    </datalist>
                                                </td>
                                                
                                                <td>
                                                    <input type="text" name="status_text" list="status-presets-<?php echo $vessel['id']; ?>" class="glass-input-sm" value="<?php echo htmlspecialchars($vessel['status_text']); ?>" required autocomplete="off">
                                                    <datalist id="status-presets-<?php echo $vessel['id']; ?>">
                                                        <option value="ON TIME / IN TRANSIT">
                                                        <option value="DESCENDING">
                                                        <option value="MAINTENANCE MODE">
                                                        <option value="IDLE / IN DOCK">
                                                        <option value="LAUNCH READY">
                                                    </datalist>
                                                </td>
                                                
                                                <td>
                                                    <select name="status_type" class="glass-select">
                                                        <option value="transit" <?php echo ($vessel['status_type'] == 'transit') ? 'selected' : ''; ?>>TRANSIT</option>
                                                        <option value="docked" <?php echo ($vessel['status_type'] == 'docked') ? 'selected' : ''; ?>>DOCKED</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn-sleek w-100" style="padding: 6px 0; font-size: 0.75rem;">Update</button>
                                                </td>
                                            </form>
                                            <td class="text-center">
                                                <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post" onsubmit="return confirm('Retire Warning: De-register OTV Asset <?php echo htmlspecialchars($vessel['vessel_name']); ?>? Physical unit telemetry will be permanently lost.');" style="display:inline;">
                                                    <input type="hidden" name="action" value="delete_vessel">
                                                    <input type="hidden" name="vessel_id" value="<?php echo $vessel['id']; ?>">
                                                    <button type="submit" class="btn-sleek" style="padding: 6px 12px; font-size: 0.75rem; border-color: #d9534f; color: #d9534f; width: 100%;"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 muted-text"><i class="bi bi-exclamation-circle fs-3 d-block mb-2"></i>No active vehicles registered.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- MINIMALIST PAGINATION CONTROLS FOR FLEET -->
                    <?php if ($fleet_total_pages > 1): ?>
                        <div class="d-flex justify-content-end align-items-center mt-4 pt-3 border-top border-secondary gap-3">
                            <span class="label-text" style="font-size: 0.7rem; opacity: 0.5;">PAGE <?php echo $fleet_page; ?> OF <?php echo $fleet_total_pages; ?></span>
                            
                            <div class="d-flex gap-2">
                                <a href="dashboard.php?fleet_page=<?php echo $fleet_page - 1; ?>&page=<?php echo $page; ?>&filter_status=<?php echo $filter_status; ?>&filter_model=<?php echo $filter_model; ?>&sort_order=<?php echo $sort_order; ?>&limit=<?php echo $limit; ?>#fleet" 
                                   class="btn-sleek" 
                                   style="padding: 4px 12px; font-size: 0.8rem; <?php echo ($fleet_page <= 1) ? 'opacity: 0.2; pointer-events: none;' : ''; ?>">
                                    &lt;
                                </a>
                                
                                <a href="dashboard.php?fleet_page=<?php echo $fleet_page + 1; ?>&page=<?php echo $page; ?>&filter_status=<?php echo $filter_status; ?>&filter_model=<?php echo $filter_model; ?>&sort_order=<?php echo $sort_order; ?>&limit=<?php echo $limit; ?>#fleet" 
                                   class="btn-sleek" 
                                   style="padding: 4px 12px; font-size: 0.8rem; <?php echo ($fleet_page >= $fleet_total_pages) ? 'opacity: 0.2; pointer-events: none;' : ''; ?>">
                                    &gt;
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Idle Javascript Timeout script -->
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


