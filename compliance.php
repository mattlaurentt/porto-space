<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compliance Registry | PORTO SPACE</title>

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
</head>
<body>
    <img src="assets/bg/fleet-bg.webp" alt="" class="sub-hero-bg" aria-hidden="true" style="filter: brightness(0.2) grayscale(0.5);">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <!-- NAVIGATION -->
    <?php include 'navbar.php'; ?>

    <!-- HERO -->
    <header class="sub-hero bg-transparent">
        <div class="container px-4 px-md-5">
            <span class="label-text d-block mb-4">ORBITAL STEWARDSHIP // GLOBAL REGISTRY</span>
            <h1 class="sub-hero-title">Compliance &<br>Sustainability</h1>
        </div>
    </header>

    <main>
        <section class="section-padding">
            <div class="container px-4 px-md-5">
                <div class="row g-5">
                    <!-- Data Sheet Column -->
                    <div class="col-lg-4">
                        <span class="label-text d-block mb-3">02 // Code of Conduct</span>
                        <h2 class="mb-5">STEWARDSHIP</h2>
                        
                        <div class="border-top border-secondary pt-4">
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Debris Standard</span>
                                <span class="muted-text d-block">ISO 24113 Compliant</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Registry Number</span>
                                <span class="muted-text d-block" style="font-family: monospace;">REG-DEBRIS-550-V4</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Disposal Area</span>
                                <span class="muted-text d-block">South Pacific Uninhabited Area</span>
                            </div>
                        </div>
                    </div>

                    <!-- Narrative Column -->
                    <div class="col-lg-7 offset-lg-1">
                        <p class="label-text mb-3 text-white-important">01 // SPACE DEBRIS MITIGATION (ISO 24113)</p>
                        <p class="muted-text mb-4 text-justified">
                            Every Orbital Transfer Vehicle in the Porto Space fleet operates in strict accordance with the Inter-Agency Space Debris Coordination Committee (IADC) directives. We ensure that no docking, transit, or hardware hot-swap maneuver releases mission-related fragments or debris into the Low Earth Orbit sector [53].
                        </p>

                        <div class="border-divider"></div>

                        <p class="label-text mb-3 text-white-important">02 // CONTROLLED DE-ORBIT AND END OF LIFE</p>
                        <p class="muted-text mb-4 text-justified">
                            For decommissioned assets, we calculate high-thrust atmospheric braking vectors designed to safely guide spent spacecraft or old satellites to complete burn-up over unpopulated ocean sectors [53]. Any residual fragments are strictly targeted to enter the South Pacific Uninhabited Area (Point Nemo).
                        </p>

                        <div class="border-divider"></div>

                        <p class="label-text mb-3 text-white-important">03 // NON-COOPERATIVE ASSET CAPTURE</p>
                        <p class="muted-text mb-4 text-justified">
                            Our recovery fleets are fully certified under international law to capture non-cooperative dead-satellites or space junk for disposal. We maintain direct telemetry pipelines with international space situational awareness (SSA) centers to coordinate safe orbital flight lanes.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>
</body>
</html>