<?php
// 1. Establish the database connection at the top of the file
require_once 'koneksidb.php';

// 2. Query the live telemetry table
$query = "SELECT * FROM tb_fleet ORDER BY id ASC";
$result = mysqli_query($koneksidb, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Space Fleet | PORTO SPACE</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
           HOVER-TRIGGERED SCROLLABLE TABLE CONTAINER
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
    <img src="assets/bg/fleet-bg.webp" alt="" class="sub-hero-bg brightness-70" aria-hidden="true">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <!-- 1. NAVIGATION -->
    <?php include 'navbar.php'; ?>

    <!-- HEADER -->
    <header class="sub-hero bg-transparent">
        <div class="container px-4 px-md-5">
            <span class="label-text d-block mb-3">Orbital Logistics</span>
            <h1 class="sub-hero-title">Our<br>Space Fleet</h1>
        </div>
    </header>

    <main>
        <!-- 2. MEET OUR SHIPS -->
        <section class="section-padding">
            <div class="container px-4 px-md-5">
                <span class="label-text d-block mb-5">01 // The Vessel Categories</span>
                
                <div class="row g-4">
                    <!-- ATLAS -->
                    <div class="col-lg-4">
                        <div class="glass-panel service-card h-100">
                            <div class="service-image-container">
                                <img src="assets/fleet/atlas.webp" alt="LTV-3 ATLAS">
                            </div>
                            <div class="service-card-content">
                                <h4 class="mb-2">LTV-3 ATLAS</h4>
                                <span class="label-text d-block mb-3 fleet-category">EXACT DELIVERY</span>
                                <p class="muted-text fs-9rem">
                                A high-speed transit unit designed for last-mile delivery of small-form satellites from.
                                </p>
                                <div class="mt-4 pt-4 border-top border-secondary">
                                    <p class="muted-text mb-2 fs-9rem"><strong>MAX PAYLOAD:</strong> 250 KG</p>
                                    <p class="muted-text mb-2 fs-9rem"><strong>ACCURACY:</strong> ± 2.5 METERS</p>
                                    <p class="muted-text mb-0 fs-9rem"><strong>PROPULSION:</strong> ION DRIVE</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLUE -->
                    <div class="col-lg-4">
                        <div class="glass-panel service-card h-100">
                            <div class="service-image-container">
                                <img src="assets/fleet/blue.webp" alt="MTV-2 BLUE">
                            </div>
                            <div class="service-card-content">
                                <h4 class="mb-2">MTV-2 BLUE</h4>
                                <span class="label-text d-block mb-3 fleet-category">PART REPLACEMENT</span>
                                <p class="muted-text fs-9rem">
                                A utility-class vessel designed to intercept active constellations to swap hardware.
                                </p>
                                <div class="mt-4 pt-3 border-top border-secondary">
                                    <p class="muted-text mb-2 fs-9rem"><strong>INTERFACE:</strong> UNIVERSAL</p>
                                    <p class="muted-text mb-2 fs-9rem"><strong>TOOLING:</strong> ROBOTIC ARMS</p>
                                    <p class="muted-text mb-0 fs-9rem"><strong>WINDOW:</strong> 180 DAYS</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PROXIMA -->
                    <div class="col-lg-4">
                        <div class="glass-panel service-card h-100">
                            <div class="service-image-container">
                                <img src="assets/fleet/proxima.webp" alt="HTV-3 PROXIMA">
                            </div>
                            <div class="service-card-content">
                                <h4 class="mb-2">HTV-3 PROXIMA</h4>
                                <span class="label-text d-block mb-3 fleet-category">SAFE REMOVAL</span>
                                <p class="muted-text fs-9rem">
                                a high-thrust recovery vessel built for towing heavy assets.
                                </p>
                                <div class="mt-4 pt-3 border-top border-secondary">
                                    <p class="muted-text mb-2 fs-9rem"><strong>TOW CAPACITY:</strong> 4,000 KG</p>
                                    <p class="muted-text mb-2 fs-9rem"><strong>HULL RATING:</strong> REINFORCED</p>
                                    <p class="muted-text mb-0 fs-9rem"><strong>PROPULSION:</strong> HYPERGOLIC</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. LIVE TRACKING (DYNAMIC DATABASE SYSTEM WITH HOVER SCROLLBAR) -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <span class="label-text d-block mb-4">02 // Real-Time Network</span>
                <h2>Live Tracking</h2>
                
                <!-- Added scrollable-table-container class to the wrapper -->
                <div class="scrollable-table-container table-responsive">
                    <table class="table-custom w-100 m-0">
                        <thead>
                            <tr>
                                <th style="width: 22%; padding-left: 24px;">VESSEL NAME</th>
                                <th style="width: 33%;">CURRENT LOCATION</th>
                                <th style="width: 25%;">ETA (ARRIVAL)</th>
                                <th style="width: 20%;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                        <!-- PHP Loop to render active vehicles dynamically -->
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($vessel = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <!-- Added style="padding-left: 24px;" to align with header -->
                                    <td style="padding-left: 24px;"><strong><?php echo htmlspecialchars($vessel['vessel_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($vessel['location']); ?></td>
                                    <td><?php echo htmlspecialchars($vessel['eta']); ?></td>
                                    <td>
                                        <!-- Dynamically assigns status--transit or status--docked classes -->
                                        <span class="status-badge status--<?php echo htmlspecialchars($vessel['status_type']); ?>">
                                            ● <?php echo htmlspecialchars($vessel['status_text']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: var(--text-muted);">No active telemetry signal detected.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- 3. FOOTER CTA -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5 text-center">
                <h2 class="mb-5">READY TO SEND YOUR CARGO TO SPACE?</h2>
                <div class="d-flex justify-content-center">
                    <a href="contact.php" class="btn-sleek">CONTACT OUR TEAM</a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'footer.php'; ?>
</body>
</html>
