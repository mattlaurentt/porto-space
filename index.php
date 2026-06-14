<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PORTO SPACE | Space Logistics & Orbital Transportation</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Custom JS -->
    <script src="main.js" defer></script>
</head>
<body>

    <!-- Overlays -->
    <div class="scanline-overlay" aria-hidden="true"></div>

    <!-- 1. NAVIGATION -->
    <?php include 'navbar.php'; ?>

    <!-- 2. HERO -->
    <header class="hero">
        <video autoplay loop muted playsinline preload="metadata" poster="assets/bg/index-bg-poster.webp" class="hero-video">
            <source src="assets/bg/index-bg.webm" type="video/webm">
            Your browser does not support HTML5 video.
        </video>
        <div class="hero-overlay"></div>
        <div class="container-fluid px-4 px-md-5">
            <div class="row">
                <div class="col-xl-10">
                    <h1 class="hero-title">SPACE LOGISTICS AND ORBITAL TRANSPORTATION</h1>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- 3. PARTNERS MARQUEE -->
        <section class="marquee-section">
            <div class="container px-4 px-md-5">
                <p class="label-text marquee-label">Partners &amp; collaborators</p>
                <div class="marquee-track">
                    <div class="marquee-inner">
                        <!-- SET A -->
                        <div class="marquee-item"><img src="assets/logo/logo-ipsum-1.svg" alt="Partner"></div>
                        <div class="marquee-item"><img src="assets/logo/logo-ipsum-2.svg" alt="Partner"></div>
                        <div class="marquee-item"><img src="assets/logo/logo-ipsum-3.svg" alt="Partner"></div>
                        <div class="marquee-item"><img src="assets/logo/logo-ipsum-4.svg" alt="Partner"></div>
                        <div class="marquee-item"><img src="assets/logo/logo-ipsum-5.svg" alt="Partner"></div> 
                        <!-- SET B (Duplicate for seamless loop) -->
                        <div class="marquee-item" aria-hidden="true"><img src="assets/logo/logo-ipsum-1.svg" alt="Partner"></div>
                        <div class="marquee-item" aria-hidden="true"><img src="assets/logo/logo-ipsum-2.svg" alt="Partner"></div>
                        <div class="marquee-item" aria-hidden="true"><img src="assets/logo/logo-ipsum-3.svg" alt="Partner"></div>
                        <div class="marquee-item" aria-hidden="true"><img src="assets/logo/logo-ipsum-4.svg" alt="Partner"></div>
                        <div class="marquee-item" aria-hidden="true"><img src="assets/logo/logo-ipsum-5.svg" alt="Partner"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. IMPACT DASHBOARD -->
        <section id="impact" class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <div class="row align-items-center g-5">
                    <div class="col-lg-5">
                        <span class="label-text d-block mb-4">01 // The Impact</span>
                        <h2 class="mb-4">Driving the <br>Orbital Economy</h2>
                    </div>
                    <div class="col-lg-7">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="metric-card">
                                    <span class="metric-value counter" data-target="22">0</span>
                                    <span class="label-text">Successful Missions</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-card">
                                    <span class="metric-value counter" data-target="79">0</span>
                                    <span class="label-text">Hosted Payloads</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-card">
                                    <span class="metric-value counter" data-target="141">0</span>
                                    <span class="label-text">Satellites Deployed</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-card">
                                    <span class="metric-value">06/2026</span>
                                    <span class="label-text">Next Launch</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. ABOUT SUMMARY -->
        <section id="about" class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <div class="row">
                    <div class="col-lg-7">
                        <span class="label-text d-block mb-4">02 // The Architecture</span>
                        <h2 class="mb-4">THE LAST MILE OF YOUR MISSION</h2>
                        <p class="muted-text mb-5">
                            PORTO SPACE operates as the final link in the orbital supply chain. Our transfer vehicles picks up payloads at generic drop-off points and delivers them to your precise addresses.
                        </p>
                        <a href="about.php" class="btn-sleek">Learn More About Us</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. SERVICES SUMMARY -->
        <section id="services" class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
                    <div>
                        <span class="label-text d-block mb-3">03 // Core Capabilities</span>
                        <h2 class="mb-0">Our Services</h2>
                    </div>
                    <a href="services.php" class="btn-sleek mt-4 mt-md-0">View All Services</a>
                </div>

                <div class="row g-4">
                    <!-- Card 1 -->
                    <div class="col-12 col-lg-4">
                        <div class="glass-panel service-card">
                            <div class="service-image-container">
                                <img src="assets/services/services-1.webp" alt="EXACT DELIVERY" loading="lazy">
                            </div>
                            <!-- New Wrapper for Padding -->
                            <div class="service-card-content">
                                <h4 class="mb-3">EXACT DELIVERY</h4>
                                <p class="muted-text">Our fleet of orbital vehicles move your satellite from a generic drop-off point to its specific working location in orbit.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-12 col-lg-4">
                        <div class="glass-panel service-card">
                            <div class="service-image-container">
                                <img src="assets/services/services-2.webp" alt="PART REPLACEMENT" loading="lazy">
                            </div>
                            <!-- New Wrapper for Padding -->
                            <div class="service-card-content">
                                <h4 class="mb-3">PART REPLACEMENT</h4>
                                <p class="muted-text">Quickly swap out old or broken hardware in your satellite network to keep your system running without lag.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-12 col-lg-4">
                        <div class="glass-panel service-card">
                            <div class="service-image-container">
                                <img src="assets/services/services-3.webp" alt="SAFE REMOVAL" loading="lazy">
                            </div>
                            <!-- New Wrapper for Padding -->
                            <div class="service-card-content">
                                <h4 class="mb-3">SAFE REMOVAL</h4>
                                <p class="muted-text">Clear away old equipment at the end of its life, keeping space clean and following all safety rules.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. TESTIMONIALS (SEAMLESS STREAM) -->
        <section id="testimonials" class="section-padding border-top-thin">
            <div class="container px-4 px-md-5 mb-5">
                <span class="label-text d-block mb-3">04 // Partner Endorsements</span>
                <h2>FEEDBACK</h2>
            </div>
            <div class="testimonial-track">
                <div class="testimonial-inner">

                    <!-- ── SET A (5 Unique Items) ── -->
                    <div class="testimonial-card">
                        <p class="testimonial-text">Precision insertion saved our constellation 18 months of maneuvering fuel.</p>
                        <h6 class="testimonial-author">Dr. Elias Thorne</h6>
                        <span class="testimonial-company">AetherLink</span>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">Reliable, exact, and highly communicative. PORTO is our first call for node refreshes.</p>
                        <h6 class="testimonial-author">Sarah Vance</h6>
                        <span class="testimonial-company">GlobalTel</span>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">Unmatched de-orbiting compliance. Zero regulatory friction on asset disposal.</p>
                        <h6 class="testimonial-author">Marcus Jin</h6>
                        <span class="testimonial-company">Orbis Systems</span>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">The most reliable last-mile orbital courier we've used in a decade.</p>
                        <h6 class="testimonial-author">Elena Rostova</h6>
                        <span class="testimonial-company">Apex Defense</span>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">The telemetry data provided during transit was flawlessly accurate.</p>
                        <h6 class="testimonial-author">James Wu</h6>
                        <span class="testimonial-company">NimbusSat</span>
                    </div>

                    <!-- ── SET B (Exact Duplicate of Set A) ── -->
                    <div class="testimonial-card">
                        <p class="testimonial-text">Precision insertion saved our constellation 18 months of maneuvering fuel.</p>
                        <h6 class="testimonial-author">Dr. Elias Thorne</h6>
                        <span class="testimonial-company">AetherLink</span>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">Reliable, exact, and highly communicative. PORTO is our first call for node refreshes.</p>
                        <h6 class="testimonial-author">Sarah Vance</h6>
                        <span class="testimonial-company">GlobalTel</span>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">Unmatched de-orbiting compliance. Zero regulatory friction on asset disposal.</p>
                        <h6 class="testimonial-author">Marcus Jin</h6>
                        <span class="testimonial-company">Orbis Systems</span>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">The most reliable last-mile orbital courier we've used in a decade.</p>
                        <h6 class="testimonial-author">Elena Rostova</h6>
                        <span class="testimonial-company">Apex Defense</span>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">The telemetry data provided during transit was flawlessly accurate.</p>
                        <h6 class="testimonial-author">James Wu</h6>
                        <span class="testimonial-company">NimbusSat</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
