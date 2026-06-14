<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | PORTO SPACE</title>

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
    <img src="assets/bg/about-bg.webp" alt="" class="sub-hero-bg" aria-hidden="true">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <!-- NAVIGATION -->
    <?php include 'navbar.php'; ?>

    <!-- HERO -->
    <header class="sub-hero bg-transparent">
        <div class="container px-4 px-md-5">
            <span class="label-text d-block mb-4">EST. 2021 // ABOUT US</span>
            <h1 class="sub-hero-title">The Couriers of<br>Low Earth Orbit</h1>
        </div>
    </header>

    <main>
        <!-- 1. COMPANY PROFILE -->
        <section class="section-padding">
            <div class="container px-4 px-md-5">
                <div class="row g-5">
                    <!-- Data Sheet Column -->
                    <div class="col-lg-4">
                        <span class="label-text d-block mb-3">01 // Company Profile</span>
                        <h2 class="mb-5">Who We Are</h2>
                        
                        <div class="border-top border-secondary pt-4">
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Legal Entity</span>
                                <span class="muted-text d-block">PORTO SPACE LOGISTICS CORP.</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Established</span>
                                <span class="muted-text d-block">2021 // LEO SECTOR</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Specialization</span>
                                <span class="muted-text d-block">LAST MILE FREIGHT & ESCORT</span>
                            </div>
                            <div class="mb-4">
                                <span class="label-text d-block opacity-50 fs-8rem label-fs-8rem">Primary Fleet</span>
                                <span class="muted-text d-block">12 LTV-2 ATLAS COURIERS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Narrative Column -->
                    <div class="col-lg-7 offset-lg-1">
                        <p class="muted-text mb-3 text-white-important">
                            THE PROBLEM
                        </p>
                        <p class="muted-text mb-4 text-justified">
                            For sixty years, launching a satellite meant accepting a compromise. You were dropped in a general neighbourhood, leaving you to use your own onboard fuel to maneuver to your slot. Every kilometre you burned getting to work was a kilometre subtracted from your operational lifespan.
                        </p>

                        <div class="border-divider"></div>

                        <p class="label-text mb-3 text-white-important">OUR SOLUTION</p>
                        <p class="muted-text mb-4 text-justified">
                            We founded PORTO on a simple conviction: no satellite should ever waste its operational fuel just to get to work. We are the ferry that takes you from the bus stop to your front door.
                        </p>
                        <p class="muted-text text-justified">
                            Our Orbital Transfer Vehicles carry their own propellant. We rendezvous with your asset and deliver it directly to its precise address in orbit. We absorb the maneuvering cost, so your satellite arrives with 100% of its fuel intact.
                        </p>
                    </div>
                </div>
            </div>
        </section>

       <!-- 02 // STRATEGIC MANDATE -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <span class="label-text d-block mb-3">02 // Strategic Mandate</span>
                <div class="glass-panel p-0 overflow-hidden">
                    <div class="row g-0">
                        
                        <!-- VISION -->
                        <div class="col-lg-6 p-4 p-md-5">
                            <span class="label-text d-block mb-4 text-white-important">Vision</span>
                            <ul class="muted-text ps-3 mb-0">
                                <li class="mb-3">To make orbital infrastructure as reliable as terrestrial shipping.</li>
                                <li class="mb-3">View Low Earth Orbit not as a frontier, but as a neighbourhood to be organized.</li>
                                <li>Establish a busy, structured, and perfectly timed orbital highway.</li>
                            </ul>
                        </div>

                        <!-- MISSION -->
                        <div class="col-lg-6 p-4 p-md-5">
                            <span class="label-text d-block mb-4 text-white-important">Mission</span>
                            <ul class="muted-text ps-3 mb-0">
                                <li class="mb-3">Provide zero-waste last-mile logistics for corporate space assets.</li>
                                <li class="mb-3">Execute transfers and move hardware without leaving a trace.</li>
                                <li>Maintain full asset custody to keep space clean while keeping your operations moving.</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        
        <!-- 3. VALUE PROPOSITION -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-5">
                        <span class="label-text d-block mb-3">03 // Why Porto</span>
                        <h2>The Case for<br>Orbital Precision</h2>
                    </div>
                    <div class="col-lg-7">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="metric-card">
                                    <span class="metric-value">0%</span>
                                    <span class="label-text">Collision Rate</span>
                                    <p class="muted-text mt-3 fs-8rem">Zero proximity incidents recorded.</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-card">
                                    <span class="metric-value">50m</span>
                                    <span class="label-text">Accuracy</span>
                                    <p class="muted-text mt-3 fs-8rem">Pinpoint insertion precision.</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-card">
                                    <span class="metric-value">100%</span>
                                    <span class="label-text">Fuel Saved</span>
                                    <p class="muted-text mt-3 fs-8rem">Zero client fuel consumed in transit.</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-card">
                                    <span class="metric-value">3</span>
                                    <span class="label-text">Docking Ports</span>
                                    <p class="muted-text mt-3 fs-8rem">Compatible with all standard mounts.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

       <!-- 4. COMMAND CREW -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
                    <div>
                        <span class="label-text d-block mb-3">04 // Command Crew</span>
                        <h2 class="mb-0">The Team</h2>
                    </div>
                </div>

                <div class="team-grid">
                    <!-- Member 1 -->
                    <div class="team-card">
                        <div class="team-img-wrapper">
                            <!-- ONLY ONE COLOR IMAGE NEEDED -->
                            <img src="assets/teams/zian.webp" alt="ZHIYYAN RAVI" class="team-img" loading="lazy">
                        </div>
                        <div class="team-info-box">
                            <h4 class="mb-1 fs-11rem letter-spacing-2">ZHIYYAN RAVI</h4>
                            <span class="label-text d-block opacity-50 fs-8rem">FOUNDER & CHIEF EXECUTIVE OFFICER</span>
                        </div>
                    </div>

                    <!-- Member 2 -->
                    <div class="team-card">
                        <div class="team-img-wrapper">
                            <img src="assets/teams/kezya.webp" alt="KEZYA ILLONA" class="team-img" loading="lazy">
                        </div>
                        <div class="team-info-box">
                            <h4 class="mb-1 fs-11rem letter-spacing-2">KEZYA ILLONA</h4>
                            <span class="label-text d-block opacity-50 fs-8rem">CHIEF TECHNOLOGY OFFICER</span>
                        </div>
                    </div>

                    <!-- Member 3 -->
                    <div class="team-card">
                        <div class="team-img-wrapper">
                            <img src="assets/teams/aidan.webp" alt="HILYADIL AIDAN" class="team-img" loading="lazy">
                        </div>
                        <div class="team-info-box">
                            <h4 class="mb-1 fs-11rem letter-spacing-2">HILYADIL AIDAN</h4>
                            <span class="label-text d-block opacity-50 fs-8rem">HEAD OF ORBITAL MECHANICS</span>
                        </div>
                    </div>

                    <!-- Member 4 -->
                    <div class="team-card">
                        <div class="team-img-wrapper">
                            <img src="assets/teams/keisya.webp" alt="KEISYA JASMINE" class="team-img" loading="lazy">
                        </div>
                        <div class="team-info-box">
                            <h4 class="mb-1 fs-11rem letter-spacing-2">KEISYA JASMINE</h4>
                            <span class="label-text d-block opacity-50 fs-8rem">HEAD ENGINEER</span>
                        </div>
                    </div>

                    <!-- Member 5 -->
                    <div class="team-card">
                        <div class="team-img-wrapper">
                            <img src="assets/teams/hani.webp" alt="SITI HANIFIAH" class="team-img" loading="lazy">
                        </div>
                        <div class="team-info-box">
                            <h4 class="mb-1 fs-11rem letter-spacing-2">SITI HANIFIAH</h4>
                            <span class="label-text d-block opacity-50 fs-65rem">FLEET OPERATIONS</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
