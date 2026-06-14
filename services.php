<?php
// 1. Establish the database connection at the top of the file
require_once 'koneksidb.php';

// 2. Query the 5 most recent cargo manifests
$query = "SELECT * FROM tb_missions WHERE mission_status = 'ACTIVE' ORDER BY id DESC LIMIT 5";
$result = mysqli_query($koneksidb, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | PORTO SPACE</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght=200;300;400;500&family=Inter:wght=300;400;500&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Custom JS -->
    <script src="main.js" defer></script>
    
    <style>
        /* ============================================================
           HOVER-TRIGGERED SCROLLABLE TABLE CONTAINER (SERVICES)
           ============================================================ */
        .scrollable-table-container {
            max-height: 400px; /* Limits the table height to 400px */
            overflow-y: auto;  /* Enables vertical scrolling */
            border: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 1.5rem;
            transition: border-color 0.3s ease;
        }
        
        /* 1. Define scrollbar width */
        .scrollable-table-container::-webkit-scrollbar { 
            width: 6px; 
        }
        /* 2. Hide scrollbar track by default */
        .scrollable-table-container::-webkit-scrollbar-track { 
            background: transparent; 
        }
        /* 3. Hide scrollbar thumb by default */
        .scrollable-table-container::-webkit-scrollbar-thumb { 
            background: transparent; 
            border-radius: 3px; 
            transition: background 0.3s ease; 
        }
        
        /* 4. ONLY SHOW SCROLLBAR THUMB ON HOVER */
        .scrollable-table-container:hover::-webkit-scrollbar-thumb { 
            background: rgba(255, 255, 255, 0.15); 
        }
        /* 5. Highlight scrollbar thumb when hovered directly */
        .scrollable-table-container:hover::-webkit-scrollbar-thumb:hover { 
            background: rgba(255, 255, 255, 0.3); 
        }

        @media (max-width: 991px) {
            .table-custom {
                min-width: 800px; /* Touch swipe horizontal scroll on mobile */
            }
        }
    </style>
</head>
<body>
    <img src="assets/bg/services-bg.webp" alt="" class="sub-hero-bg brightness-60" aria-hidden="true">
    <div class="scanline-overlay" aria-hidden="true"></div>

    <!-- NAVIGATION -->
    <?php include 'navbar.php'; ?>

    <!-- HERO -->
    <header class="sub-hero bg-transparent">

        <div class="container px-4 px-md-5">
            <span class="label-text d-block mb-4">Services and Solutions</span>
            <h1 class="sub-hero-title">Core<br>Capabilities</h1>
        </div>
    </header>

    <main>
        <!-- 01 // EXACT DELIVERY -->
        <section class="section-padding">
            <div class="container px-4 px-md-5">
                <div class="row g-5 align-items-start">
                    <div class="col-lg-6">
                        <span class="label-text d-block mb-4">01 // Logistics</span>
                        <h2 class="mb-4">Exact Delivery</h2>
                        
                        <p class="muted-text mb-5">
                        Our OTVs rendezvous with your asset and ferry it to its precise longitudinal address. We ensure your satellite arrives at its front door with 100% of its fuel intact, effectively extending its operational life.
                        </p>

                        <div class="spec-border pt-4">
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">Inclination Adjustment</span>
                                <span class="muted-text d-block">UP TO 15° SHIFT</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">Payload Phasing</span>
                                <span class="muted-text d-block">RAAN PHASING READY</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">Max Capacity</span>
                                <span class="muted-text d-block">400KG PER TRANSFER</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <img src="assets/services/services-1.webp" class="service-visual" alt="Exact Delivery">
                    </div>
                </div>
            </div>
        </section>

        <!-- 02 // PART REPLACEMENT -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <div class="row g-5 align-items-start">
                    <div class="col-lg-6">
                        <span class="label-text d-block mb-4">02 // Resilience</span>
                        <h2 class="mb-4">Part Replacement</h2>

                        <p class="muted-text mb-5">
                            We provide 24/7 express replacement for failed hardware. Our vehicles are pre-deployed in key orbital planes, ready to hot-swap modular components to ensure zero network downtime.
                        </p>

                        <div class="spec-border pt-4">
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">Response Time</span>
                                <span class="muted-text d-block">SUB 72-HOUR INTERCEPT</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">Deployment Logic</span>
                                <span class="muted-text d-block">PARKING SHELL STRATEGY</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">Compatibility</span>
                                <span class="muted-text d-block">MODULAR MULTI-STANDARDS MOUNT</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <img src="assets/services/services-2.webp" class="service-visual" alt="Part Replacement">
                    </div>
                </div>
            </div>
        </section>

        <!-- 03 // SAFE REMOVAL -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <div class="row g-5 align-items-start">
                    <div class="col-lg-6">
                        <span class="label-text d-block mb-4">03 // Stewardship</span>
                        <h2 class="mb-4">Safe Removal</h2>
                        
                        <p class="muted-text mb-5">
                            We provide controlled de-orbiting for decommissioned satellites. We capture non-cooperative assets and guide them to a safe burn-up in the atmosphere.
                        </p>

                        <div class="spec-border pt-4">
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">Compliance</span>
                                <span class="muted-text d-block">ISO 24113 COMPLIANT</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">Capture Methodology</span>
                                <span class="muted-text d-block">SYNCHRONOUS NET & HARPON</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem">End of Life</span>
                                <span class="muted-text d-block">CONTROLLED RE-ENTRY BURN-UP</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <img src="assets/services/services-3.webp" class="service-visual" alt="Safe Removal">
                    </div>
                </div>
            </div>
        </section>

        <!-- 04 // ACTIVE MANIFEST (WITH HOVER SCROLLBAR & PROPORTIONAL WIDTHS) -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <span class="label-text d-block mb-4">04 // Data Stream</span>
                <h2>Active Manifest</h2>

                <!-- Wrap inside our responsive scrollable container class -->
                <div class="scrollable-table-container table-responsive">
                    <table class="table-custom w-100 m-0">
                        <thead>
                            <tr>
                                <!-- Defined specific percentage widths to distribute layout evenly -->
                                <th style="width: 15%; padding-left: 24px;">MISSION ID</th>
                                <th style="width: 35%;">CORPORATE ENTITY</th>
                                <th style="width: 30%;">TARGET VECTOR</th>
                                <th style="width: 20%;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- PHP Loop to render dynamic manifest streams -->
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <!-- Aligned padding with headers, and generates structured ORB-00X IDs -->
                                        <td style="padding-left: 24px; font-family: monospace;">ORB-00<?php echo $row['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($row['consignor']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['target_orbit']); ?></td>
                                        <td>
                                            <?php if ($row['assigned_vessel'] == 'UNASSIGNED'): ?>
                                                <span class="status-badge status--pending">● INTEGRATION</span>
                                            <?php else: ?>
                                                <span class="status-badge status--transit">● IN TRANSIT (<?php echo htmlspecialchars($row['assigned_vessel']); ?>)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 muted-text">No active manifest parameters registered on network.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
