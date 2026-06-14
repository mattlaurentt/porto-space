<?php
// Force PHP to show any errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'koneksidb.php';

// 1. Capture search, filter, and page queries
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($koneksidb, trim($_GET['search'])) : "";
$active_filter = isset($_GET['filter']) ? mysqli_real_escape_string($koneksidb, trim($_GET['filter'])) : "all";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 

if ($page < 1) $page = 1;

// 2. DYNAMIC SQL QUERY BUILDER (WHERE CLAUSE)
$where_clause = " WHERE 1=1";

if (!empty($search_query)) {
    $where_clause .= " AND (m.consignor LIKE '%$search_query%' OR m.id = '$search_query')";
}

if ($active_filter == 'atlas') {
    $where_clause .= " AND m.assigned_vessel LIKE 'ATLAS%'";
} elseif ($active_filter == 'blue') {
    $where_clause .= " AND m.assigned_vessel LIKE 'BLUE%'";
} elseif ($active_filter == 'proxima') {
    $where_clause .= " AND m.assigned_vessel LIKE 'PROXIMA%'";
} elseif ($active_filter == 'unassigned') {
    $where_clause .= " AND m.assigned_vessel = 'UNASSIGNED'";
}

// 3. CALCULATE TOTAL RECORDS FOR PAGINATION
$count_sql = "SELECT COUNT(*) as total FROM tb_missions m " . $where_clause;
$count_result = mysqli_query($koneksidb, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];

// Determine total pages
$total_pages = ceil($total_records / $limit);
if ($total_pages < 1) $total_pages = 1;
if ($page > $total_pages) $page = $total_pages;

$offset = ($page - 1) * $limit;

// 4. FETCH THE ACTUAL PAGINATED DATA
$sql = "SELECT m.*, f.location, f.eta, f.status_text, f.status_type 
        FROM tb_missions m 
        LEFT JOIN tb_fleet f ON m.assigned_vessel = f.vessel_name 
        $where_clause 
        ORDER BY m.id DESC 
        LIMIT $limit OFFSET $offset";

$search_results = mysqli_query($koneksidb, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telemetry Tracking | PORTO SPACE</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <style>
        .glass-select-track {
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--border-thin);
            color: #ffffff;
            font-family: 'Jost', sans-serif;
            letter-spacing: 1.5px;
            font-size: 0.85rem;
            padding: 15px 10px;
            cursor: pointer;
            transition: 0.3s;
        }
        .glass-select-track:focus { outline: none; border-bottom-color: #ffffff; }
        .glass-select-track option { background: #020306; color: #ffffff; }

        /* Bootstrap Modal Overrides for Porto Space Theme */
        .modal-content {
            background-color: rgba(2, 3, 6, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-thin);
            border-radius: 0px; 
            color: #ffffff;
            box-shadow: 0 0 40px rgba(0,0,0,0.8);
        }
        .modal-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            border-radius: 0px;
            padding: 1.5rem 2rem;
        }
        .modal-body { padding: 2rem; }
        .close-modal-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            transition: 0.3s;
        }
        .close-modal-btn:hover { color: #ffffff; }
        
        .readout-box {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            padding: 1.5rem;
            height: 100%;
        }

        /* Fix for Modal Z-index over Scanlines */
        .modal { z-index: 10500 !important; }
        .modal-backdrop { z-index: 10400 !important; }

        @media (max-width: 991px) {
            .table-custom { min-width: 800px; }
        }
    </style>
</head>

<body>
    <img src="assets/bg/track-bg.webp" class="sub-hero-bg" aria-hidden="true">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <!-- NAVIGATION -->
    <?php include 'navbar.php'; ?>

    <!-- HERO -->
    <header class="sub-hero bg-transparent">
        <div class="container px-4 px-md-5">
            <span class="label-text d-block mb-4">ORBITAL INTERFACE // LOGISTICS</span>
            <h1 class="sub-hero-title">Trajectory<br>Telemetry Tracker</h1>
        </div>
    </header>

    <main style="min-height: 70vh; position: relative; z-index: 2;">
        <section class="section-padding pt-4">
            <div class="container px-4 px-md-5">
                
                <!-- SEARCH & FILTER CONSOLE -->
                <div class="row mb-5 justify-content-center">
                    <div class="col-lg-10">
                        <div class="glass-panel p-4" style="border-radius: 0px; border-color: rgba(255,255,255,0.15); background: rgba(2,3,6,0.5);">
                            
                            <form action="track.php" method="get" class="row g-3 align-items-end">
                                <!-- Reset page back to 1 when a new search is performed -->
                                <input type="hidden" name="page" value="1">
                                
                                <div class="col-md-5">
                                    <span class="label-text d-block mb-2" style="font-size: 0.7rem; opacity: 0.6;">CONSIGNOR / ID</span>
                                    <input type="text" name="search" class="glass-input m-0 w-100" placeholder="e.g. AetherLink or 1" value="<?php echo htmlspecialchars($search_query); ?>" autocomplete="off" style="border-radius: 0px; height: 45px; padding: 0 10px;">
                                </div>
                                
                                <div class="col-md-4">
                                    <span class="label-text d-block mb-2" style="font-size: 0.7rem; opacity: 0.6;">FLEET ASSET FILTER</span>
                                    <!-- Sleek Dropdown Filter -->
                                    <select name="filter" class="glass-select-track w-100 m-0" style="height: 45px; padding: 0 10px;">
                                        <option value="all" <?php echo ($active_filter == 'all') ? 'selected' : ''; ?>>ALL MISSIONS</option>
                                        <option value="atlas" <?php echo ($active_filter == 'atlas') ? 'selected' : ''; ?>>ATLAS COURIERS</option>
                                        <option value="blue" <?php echo ($active_filter == 'blue') ? 'selected' : ''; ?>>BLUE COURIERS</option>
                                        <option value="proxima" <?php echo ($active_filter == 'proxima') ? 'selected' : ''; ?>>PROXIMA COURIERS</option>
                                        <option value="unassigned" <?php echo ($active_filter == 'unassigned') ? 'selected' : ''; ?>>AWAITING LAUNCH</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="btn-sleek w-100" style="height: 45px; font-size: 0.8rem; border-radius: 0px;">INITIATE SCAN</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- STREAMLINED REGISTRY TABLE -->
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
                            <span class="label-text" style="font-size: 0.75rem; opacity: 0.5;">Registry Results (<?php echo $total_records; ?> found)</span>
                            <?php if (!empty($search_query) || $active_filter !== 'all'): ?>
                                <a href="track.php" class="muted-text text-decoration-none" style="font-size: 0.75rem; letter-spacing: 1px;"><i class="bi bi-x-circle me-1"></i>Clear Scan</a>
                            <?php endif; ?>
                        </div>

                        <?php if ($total_records > 0): ?>
                            <div class="table-responsive">
                                <table class="table-custom w-100">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">MISSION ID</th>
                                            <th style="width: 30%;">CONSIGNOR</th>
                                            <th style="width: 25%;">TARGET VECTOR</th>
                                            <th style="width: 15%;">STATUS</th>
                                            <th style="width: 15%; text-align: right;">DATA LINK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($mission = mysqli_fetch_assoc($search_results)): ?>
                                            <tr>
                                                <td class="text-secondary" style="font-family: monospace;">ORB-00<?php echo $mission['id']; ?></td>
                                                <td><strong class="text-white"><?php echo htmlspecialchars($mission['consignor']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($mission['target_orbit']); ?></td>
                                                <td>
                                                    <?php if ($mission['assigned_vessel'] == 'UNASSIGNED'): ?>
                                                        <span class="status-badge status--pending">● STANDBY</span>
                                                    <?php else: ?>
                                                        <span class="status-badge status--<?php echo htmlspecialchars($mission['status_type']); ?>">
                                                            ● <?php echo htmlspecialchars($mission['status_text']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <!-- Trigger Button for the Pop-up Modal -->
                                                    <button type="button" class="btn-sleek" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $mission['id']; ?>" style="padding: 4px 15px; font-size: 0.7rem;">
                                                        READOUT
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- PAGINATION BUTTONS (< >) -->
                            <?php if ($total_pages > 1): ?>
                                <div class="d-flex justify-content-end align-items-center mt-4 pt-3 border-top border-secondary gap-3">
                                    <span class="label-text" style="font-size: 0.7rem; opacity: 0.5;">PAGE <?php echo $page; ?> OF <?php echo $total_pages; ?></span>
                                    
                                    <div class="d-flex gap-2">
                                        <!-- Previous Button (<) -->
                                        <a href="track.php?search=<?php echo urlencode($search_query); ?>&filter=<?php echo urlencode($active_filter); ?>&page=<?php echo $page - 1; ?>" 
                                           class="btn-sleek" 
                                           style="padding: 4px 12px; font-size: 0.8rem; <?php echo ($page <= 1) ? 'opacity: 0.2; pointer-events: none;' : ''; ?>">
                                            &lt;
                                        </a>
                                        
                                        <!-- Next Button (>) -->
                                        <a href="track.php?search=<?php echo urlencode($search_query); ?>&filter=<?php echo urlencode($active_filter); ?>&page=<?php echo $page + 1; ?>" 
                                           class="btn-sleek" 
                                           style="padding: 4px 12px; font-size: 0.8rem; <?php echo ($page >= $total_pages) ? 'opacity: 0.2; pointer-events: none;' : ''; ?>">
                                            &gt;
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <div class="glass-panel text-center py-5" style="border-radius: 0px; border-color: rgba(255,255,255,0.1);">
                                <i class="bi bi-wifi-off fs-1 d-block mb-3 text-secondary"></i>
                                <span class="label-text d-block" style="font-size: 0.85rem;">No active telemetry signals matched your query.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>

    <!-- ==========================================================
    MODALS RENDERED HERE (At the absolute bottom of the body)
    This prevents the dark background from trapping the popup!
    ========================================================== -->
    <?php 
    if ($total_records > 0) {
        // Reset the database pointer to loop through the results one more time to generate modals
        mysqli_data_seek($search_results, 0);
        while($mission = mysqli_fetch_assoc($search_results)): 
    ?>
        <div class="modal fade" id="modal-<?php echo $mission['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="label-text d-block" style="font-size: 0.7rem; color: var(--accent-status-amber);">[ SECURE TACTICAL READOUT ]</span>
                            <h4 class="mb-0 mt-1" style="letter-spacing: 2px;">MISSION ORB-00<?php echo $mission['id']; ?></h4>
                        </div>
                        <button type="button" class="close-modal-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-4">
                            
                            <!-- Client Details -->
                            <div class="col-lg-6">
                                <div class="readout-box">
                                    <span class="label-text d-block mb-3 border-bottom border-secondary pb-2">01 // CLIENT REGISTRY</span>
                                    <p class="muted-text mb-1 fs-8rem opacity-50">Consignor Entity</p>
                                    <p class="text-white fs-5 mb-3" style="font-family: 'Jost', sans-serif;"><?php echo htmlspecialchars($mission['consignor']); ?></p>
                                    
                                    <p class="muted-text mb-1 fs-8rem opacity-50">Officer Contact</p>
                                    <p class="text-white mb-0" style="font-family: monospace; font-size: 0.9rem;"><i class="bi bi-envelope me-2 text-secondary"></i><?php echo htmlspecialchars($mission['email']); ?></p>
                                </div>
                            </div>

                            <!-- Flight & Cargo Details -->
                            <div class="col-lg-6">
                                <div class="readout-box">
                                    <span class="label-text d-block mb-3 border-bottom border-secondary pb-2">02 // ORBITAL PARAMETERS</span>
                                    <p class="muted-text mb-1 fs-8rem opacity-50">Target Orbit Vector</p>
                                    <p class="text-white fs-5 mb-3" style="font-family: 'Jost', sans-serif;"><i class="bi bi-geo-alt me-2 text-secondary"></i><?php echo htmlspecialchars($mission['target_orbit']); ?></p>
                                    
                                    <p class="muted-text mb-1 fs-8rem opacity-50">Payload Specifications</p>
                                    <p class="text-white mb-0" style="font-size: 0.85rem; line-height: 1.6;"><?php echo htmlspecialchars($mission['parameters']); ?></p>
                                </div>
                            </div>

                            <!-- Live Telemetry Details -->
                            <div class="col-12">
                                <div class="readout-box" style="background: rgba(0,0,0,0.5);">
                                    <span class="label-text d-block mb-3 border-bottom border-secondary pb-2">03 // LIVE FLEET TELEMETRY</span>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <p class="muted-text mb-1 fs-8rem opacity-50">OTV Asset</p>
                                            <?php if ($mission['assigned_vessel'] == 'UNASSIGNED'): ?>
                                                <span class="text-warning fs-5" style="font-family: 'Jost', sans-serif;"><i class="bi bi-hourglass-split me-2"></i>AWAITING</span>
                                            <?php else: ?>
                                                <span class="text-success fs-5" style="font-family: 'Jost', sans-serif;"><i class="bi bi-rocket-takeoff me-2"></i><?php echo htmlspecialchars($mission['assigned_vessel']); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-4">
                                            <p class="muted-text mb-1 fs-8rem opacity-50">Current Coordinates</p>
                                            <span class="text-white" style="font-size: 0.95rem;">
                                                <?php echo ($mission['assigned_vessel'] == 'UNASSIGNED') ? 'LAUNCHPAD INTEGRATION' : htmlspecialchars($mission['location']); ?>
                                            </span>
                                        </div>

                                        <div class="col-md-2">
                                            <p class="muted-text mb-1 fs-8rem opacity-50">ETA</p>
                                            <span class="text-white" style="font-size: 0.95rem;">
                                                <?php echo ($mission['assigned_vessel'] == 'UNASSIGNED') ? '--' : htmlspecialchars($mission['eta']); ?>
                                            </span>
                                        </div>

                                        <div class="col-md-3">
                                            <p class="muted-text mb-1 fs-8rem opacity-50">Status Code</p>
                                            <?php if ($mission['assigned_vessel'] == 'UNASSIGNED'): ?>
                                                <span class="status-badge status--pending">● STANDBY</span>
                                            <?php else: ?>
                                                <span class="status-badge status--<?php echo htmlspecialchars($mission['status_type']); ?>">
                                                    ● <?php echo htmlspecialchars($mission['status_text']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php 
        endwhile; 
    } 
    ?>
    <!-- END OF MODALS -->

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bulletproof Inline Navbar Scroll-Blur Script -->
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.custom-navbar');
            if (navbar) {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        });
    </script>
</body>
</html>