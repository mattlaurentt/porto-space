<?php
// Force PHP to show any errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Start the session to store temporary redirect messages
session_start();

// 2. Load the database connection
require_once 'koneksidb.php';

$success_msg = "";
$error_msg = "";

// 3. Check if there is a success message waiting from our redirect
if (isset($_SESSION['temp_success_msg'])) {
    $success_msg = $_SESSION['temp_success_msg'];
    unset($_SESSION['temp_success_msg']); // Clear it immediately so it only shows ONCE
}

// 4. Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Capture user inputs and sanitize them
    $consignor = mysqli_real_escape_string($koneksidb, trim($_POST['consignor']));
    $email = mysqli_real_escape_string($koneksidb, trim($_POST['email']));
    $target_orbit = mysqli_real_escape_string($koneksidb, trim($_POST['target_orbit']));
    $parameters = mysqli_real_escape_string($koneksidb, trim($_POST['parameters']));

    if (!empty($consignor) && !empty($email) && !empty($target_orbit)) {
        
        // Save to database
        $query = "INSERT INTO tb_missions (consignor, email, target_orbit, parameters, mission_status) 
        VALUES ('$consignor', '$email', '$target_orbit', '$parameters', 'PENDING')";
        
        if (mysqli_query($koneksidb, $query)) {
            // SUCCESSFUL POST: Save the message in a session variable and REDIRECT
            $_SESSION['temp_success_msg'] = "Dispatch has queued your payload.";
            header("Location: contact.php");
            exit;
        } else {
            $error_msg = "Database Error: " . mysqli_error($koneksidb);
        }
    } else {
        $error_msg = "Please fill out all required flight parameters.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | PORTO SPACE</title>
    
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
    <img src="assets/bg/contact-bg.webp" class="sub-hero-bg" aria-hidden="true">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <!-- NAVIGATION -->
    <?php include 'navbar.php'; ?>

    <!-- HERO -->
    <header class="sub-hero" style="background: transparent;">
        <div class="container px-4 px-md-5">
            <span class="label-text d-block mb-4">REACH OUT // DISPATCH</span>
            <h1 class="sub-hero-title">Initiate<br>Logistics</h1>
        </div>
    </header>

    <main>
        <!-- 1. CONTACT FORM SECTION -->
        <section class="section-padding">
            <div class="container px-4 px-md-5">
                <div class="row g-5">
                    <div class="col-lg-5 mb-5 mb-lg-0">
                        <span class="label-text d-block mb-4">01 // Requisition</span>
                        <h2 class="mb-4">SUBMIT PARAMETERS</h2>
                        <p class="muted-text pe-lg-5">
                            Submit your mission parameters to our dispatch. An orbital logistics coordinator will draft your preliminary flight manifest and contact you within 24 hours.
                        </p>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        
                        <!-- DYNAMIC STATUS BANNERS (Sharp edges, no icons, clean terminal typography) -->
                        <?php if (!empty($success_msg)): ?>
                            <div style="background: rgba(92, 184, 92, 0.06); border: 1px solid #5cb85c; color: #5cb85c; padding: 15px 20px; margin-bottom: 35px; font-size: 0.8rem; letter-spacing: 1.5px; font-family: 'Jost', sans-serif; text-transform: uppercase; border-radius: 0px; box-shadow: 0 0 10px rgba(92,184,92,0.1);">
                                <strong>[ SUCCESS ]</strong> <?php echo $success_msg; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error_msg)): ?>
                            <div style="background: rgba(217, 83, 79, 0.06); border: 1px solid #d9534f; color: #d9534f; padding: 15px 20px; margin-bottom: 35px; font-size: 0.8rem; letter-spacing: 1.5px; font-family: 'Jost', sans-serif; text-transform: uppercase; border-radius: 0px; box-shadow: 0 0 10px rgba(217,83,79,0.1);">
                                <strong>[ SYSTEM ALERT ]</strong> <?php echo $error_msg; ?>
                            </div>
                        <?php endif; ?>

                        <!-- UPDATED ACTION AND POST METHOD -->
                        <form action="contact.php" method="post">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <!-- Added name="consignor" -->
                                    <input type="text" name="consignor" class="glass-input" placeholder="Consignor (Entity Name)" required autocomplete="off">
                                </div>
                                <div class="col-md-6">
                                    <!-- Added name="email" -->
                                    <input type="email" name="email" class="glass-input" placeholder="Representative Email" required autocomplete="off">
                                </div>
                                <div class="col-12">
                                    <!-- Added name="target_orbit" -->
                                    <input type="text" name="target_orbit" class="glass-input" placeholder="Target Orbit Vector (e.g., LEO 550km)" required autocomplete="off">
                                </div>
                                <div class="col-12">
                                    <!-- Added name="parameters" -->
                                    <textarea name="parameters" class="glass-input" rows="4" placeholder="Additional Mission Parameters..."></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-sleek">Submit Manifest Parameters</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. OFFICES -->
        <section class="section-padding border-top-thin">
            <div class="container px-4 px-md-5">
                <span class="label-text d-block mb-3">02 // Operational Network</span>
                <h2 class="mb-5">DISPATCH // OFFICES</h2>
                
                <div class="row g-5">
                    <!-- HQ -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <h4 class="label-text mb-4" style="color: white !important; font-size: 1.3rem; letter-spacing: 2px;">PORTO HQ (EUROPE)</h4>
                        <p class="muted-text mb-4" style="text-transform: none; font-size: 1.1rem; line-height: 1.6;">
                            Space Innovation Center,<br>
                            Route de Meyrin 14,<br>
                            1202 Geneva, Switzerland
                        </p>
                        <p class="label-text opacity-50" style="font-size: 0.85rem; letter-spacing: 1px;">
                            REG: CH-441-SP-99<br>
                            +41 22 555 0199
                        </p>
                    </div>

                    <!-- AMERICAS -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <h4 class="label-text mb-4" style="color: white !important; font-size: 1.3rem; letter-spacing: 2px;">PORTO AMERICAS</h4>
                        <p class="muted-text mb-4" style="text-transform: none; font-size: 1.1rem; line-height: 1.6;">
                            Launch Integration Facility,<br>
                            Bldg 4A, Spacecom Way <br>
                            Cape Canaveral, USA
                        </p>
                        <p class="label-text opacity-50" style="font-size: 0.85rem; letter-spacing: 1px;">
                            REG: US-FL-88201<br>
                            +1 321 555 0142
                        </p>
                    </div>

                    <!-- ORBITAL NODE 01 -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <h4 class="label-text mb-4" style="color: white !important; font-size: 1.3rem; letter-spacing: 2px;">ORBITAL NODE 01</h4>
                        <p class="muted-text mb-4" style="text-transform: none; font-size: 1.1rem; line-height: 1.6;">
                            LEO Gateway Station,<br>
                            Sector 04 // Inclination 55.0°<br>
                            Altitude: 550 km (Active)
                        </p>
                        <p class="label-text opacity-50" style="font-size: 0.85rem; letter-spacing: 1px;">
                            COMMS: BAND-X / SECURE<br>
                            FREQ: 14.25 GHZ
                        </p>
                    </div>

                    <!-- HUB -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <h4 class="label-text mb-4" style="color: white !important; font-size: 1.3rem; letter-spacing: 2px;">LUNAR FORWARDING</h4>
                        <p class="muted-text mb-4" style="text-transform: none; font-size: 1.1rem; line-height: 1.6;">
                            Gateway Transfer Point,<br>
                            Near-Rectilinear Halo Orbit<br>
                            Luna Sector 01
                        </p>
                        <p class="label-text opacity-50" style="font-size: 0.85rem; letter-spacing: 1px;">
                            STATUS: IN-DOCK ONLY<br>
                            FREQ: DEEP SPACE NET
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
