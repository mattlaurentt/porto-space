<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service | PORTO SPACE</title>

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
    <img src="assets/bg/fleet-bg.webp" alt="" class="sub-hero-bg" aria-hidden="true">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <!-- NAVIGATION -->
    <?php include 'navbar.php'; ?>

    <!-- HERO -->
    <header class="sub-hero bg-transparent">
        <div class="container px-4 px-md-5">
            <span class="label-text d-block mb-4">REGULATORY BOARD // PROTOCOL</span>
            <h1 class="sub-hero-title">Terms of<br>Space Carriage</h1>
        </div>
    </header>

    <main>
        <section class="section-padding">
            <div class="container px-4 px-md-5">
                <div class="row g-5">
                    <!-- Data Sheet Column -->
                    <div class="col-lg-4">
                        <span class="label-text d-block mb-3">01 // Document Control</span>
                        <h2 class="mb-5">CARRIAGE TERMS</h2>
                        
                        <div class="border-top border-secondary pt-4">
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Document ID</span>
                                <span class="muted-text d-block" style="font-family: monospace;">TOS-LEO-2026-V1</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Effective Date</span>
                                <span class="muted-text d-block">June 08, 2026</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Jurisdiction</span>
                                <span class="muted-text d-block">UN Outer Space Treaty (1967)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Narrative Column -->
                    <div class="col-lg-7 offset-lg-1">
                        <p class="label-text mb-3 text-white-important">01 // PAYLOAD CUSTODY AND INJECTION</p>
                        <p class="muted-text mb-4 text-justified">
                            Porto Space Logistics Corp. assumes liability and physical custody of client payloads only upon successful release from the primary launch vehicle at the designated orbital drop-off point. We are not responsible for primary stage flight anomalies or launch provider scrubs.
                        </p>

                        <div class="border-divider"></div>

                        <p class="label-text mb-3 text-white-important">02 // PROPELLANT PRESERVATION</p>
                        <p class="muted-text mb-4 text-justified">
                            We guarantee that our Orbital Transfer Vehicles (OTVs) will absorb 100% of the maneuvering propellant cost required to transport the client asset from the generic insertion drop-off to its assigned longitudinal coordinate slot. The client's onboard fuel must remain fully intact during transportation.
                        </p>

                        <div class="border-divider"></div>

                        <p class="label-text mb-3 text-white-important">03 // ORBITAL INTERFERENCE LIMITATION</p>
                        <p class="muted-text mb-4 text-justified">
                            Clients must supply active tracking frequencies, transponder encryption keys, and mechanical structural mounts matching universal docking guidelines prior to launch integration. Porto Space reserves the right to scrub or postpone any orbital docking maneuver if local space conditions present telemetry anomalies.
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