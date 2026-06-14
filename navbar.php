<?php
// Detect the name of the file currently being loaded (e.g., "about.php")
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="custom-navbar">
    <a href="index.php" class="logo">PORTO SPACE</a>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon" aria-label="Toggle navigation">
        <span></span><span></span><span></span>
    </label>
    <div class="nav-links">
        <!-- PHP checks the current page name. If it matches the link, it prints 'nav-active' -->
        <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'nav-active' : ''; ?>">Home</a>
        <a href="about.php" class="<?php echo ($current_page == 'about.php') ? 'nav-active' : ''; ?>">About Us</a>
        <a href="services.php" class="<?php echo ($current_page == 'services.php') ? 'nav-active' : ''; ?>">Services</a>
        <a href="fleet.php" class="<?php echo ($current_page == 'fleet.php') ? 'nav-active' : ''; ?>">Fleet</a>
        <a href="track.php" class="<?php echo ($current_page == 'track.php') ? 'nav-active' : ''; ?>">Tracker</a>
        <a href="contact.php" class="<?php echo ($current_page == 'contact.php') ? 'nav-active' : ''; ?>">Contact</a>
    </div>
</nav>