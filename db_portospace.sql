-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 14, 2026 at 01:49 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_portospace`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admins`
--

CREATE TABLE `tb_admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admins`
--

INSERT INTO `tb_admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'aidanganteng', '2026-06-03 07:20:46');

-- --------------------------------------------------------

--
-- Table structure for table `tb_fleet`
--

CREATE TABLE `tb_fleet` (
  `id` int(11) NOT NULL,
  `vessel_name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `eta` varchar(100) NOT NULL,
  `status_text` varchar(255) NOT NULL,
  `status_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_fleet`
--

INSERT INTO `tb_fleet` (`id`, `vessel_name`, `location`, `eta`, `status_text`, `status_type`) VALUES
(1, 'ATLAS-01', 'Low Earth Orbit (LEO)', 'Immediate', 'ON TIME / IN TRANSIT', 'transit'),
(2, 'ATLAS-02', 'Low Earth Orbit (LEO)', '12 Hours', 'ON TIME / IN TRANSIT', 'transit'),
(3, 'ATLAS-03', 'Low Earth Orbit (LEO)', '5 Hours from now', 'ON TIME / IN TRANSIT', 'transit'),
(4, 'ATLAS-04', 'Cape Canaveral Pad 4A', 'T-Minus 24 Hours', 'PRE-FLIGHT / FUELING', 'docked'),
(5, 'BLUE-01', 'Near the Moon', '24 Hours+', 'ON TIME / IN TRANSIT', 'transit'),
(6, 'BLUE-02', 'Low Earth Orbit (LEO)', '1 Hour', 'ON TIME / IN TRANSIT', 'transit'),
(7, 'BLUE-03', 'Low Earth Orbit (LEO)', '22 Minutes', 'DESCENDING', 'transit'),
(8, 'PROXIMA-01', 'GEO-Stationary Belt', '--', 'ON TIME / IN TRANSIT', 'transit'),
(9, 'PROXIMA-02', 'Low Earth Orbit (LEO)', '22 Minutes', 'DESCENDING', 'transit'),
(10, 'PROXIMA-03', 'Low Earth Orbit (LEO)', '1 Hour', 'ON TIME / IN TRANSIT', 'transit');

-- --------------------------------------------------------

--
-- Table structure for table `tb_missions`
--

CREATE TABLE `tb_missions` (
  `id` int(11) NOT NULL,
  `consignor` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `target_orbit` varchar(255) NOT NULL,
  `parameters` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_vessel` varchar(100) DEFAULT 'UNASSIGNED',
  `mission_status` varchar(50) DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_missions`
--

INSERT INTO `tb_missions` (`id`, `consignor`, `email`, `target_orbit`, `parameters`, `created_at`, `assigned_vessel`, `mission_status`) VALUES
(1, 'SpaceX Propulsion', 'cargo@spacex.com', 'Low Earth Orbit (LEO)', 'Deploy 5 Starlink v2 communication satellites. Trajectory requires active RAAN phasing.', '2026-06-08 14:56:30', 'ATLAS-01', 'ACTIVE'),
(2, 'NASA Lunar Gateway', 'logistics@nasa.gov', 'Near the Moon', 'Deliver habitation module life-support replacement parts. Priority escort required.', '2026-06-08 14:56:30', 'BLUE-01', 'ACTIVE'),
(3, 'ESA Science Directorate', 'science@esa.int', 'GEO-Stationary Belt', 'Towing retired climate monitoring satellite to designated South Pacific disposal orbit.', '2026-06-08 14:56:30', 'PROXIMA-01', 'ACTIVE'),
(4, 'JAXA Navigation', 'satellite@jaxa.jp', 'Medium Earth Orbit (MEO)', 'Deploy 1 QZSS positioning satellite. Calibration telemetry check required post-injection.', '2026-06-08 14:56:30', 'UNASSIGNED', 'PENDING'),
(5, 'OneWeb Connectivity', 'network@oneweb.net', 'Low Earth Orbit (LEO)', 'Deploy 10 small-form broadband internet nodes. Standard launch integration complete.', '2026-06-08 14:56:30', 'ATLAS-02', 'ACTIVE'),
(6, 'Planet Labs Imaging', 'imagery@planet.com', 'Low Earth Orbit (LEO)', 'Deploy 4 Dove Earth-imaging cubesats. Clean room deployment verification required.', '2026-06-08 14:56:30', 'UNASSIGNED', 'PENDING'),
(7, 'Capella Space Radar', 'radar@capellaspace.com', 'Low Earth Orbit (LEO)', 'Deploy 1 SAR (Synthetic Aperture Radar) imaging satellite. Telemetry link verification.', '2026-06-08 14:56:30', 'BLUE-02', 'ACTIVE'),
(8, 'Kuiper Systems', 'kuiper@amazon.com', 'Low Earth Orbit (LEO)', 'Deploy 12 prototype broadband satellite nodes. Pre-flight pad integration scheduled.', '2026-06-08 14:56:30', 'UNASSIGNED', 'PENDING'),
(9, 'Astroscale Stewardship', 'debris@astroscale.com', 'Low Earth Orbit (LEO)', 'Intercept and tow active spent rocket upper stage to burn-up orbit. Hazardous maneuvering.', '2026-06-08 14:56:30', 'PROXIMA-02', 'ACTIVE'),
(10, 'Spaceflight Rideshare', 'rideshare@spaceflight.com', 'Low Earth Orbit (LEO)', 'Multi-payload rideshare mission containing 8 university research cubesats.', '2026-06-08 14:56:30', 'UNASSIGNED', 'PENDING'),
(11, 'ISRO Polar Science', 'polar@isro.gov.in', 'Low Earth Orbit (LEO)', 'Deploy 1 polar climate monitor. Precision orbital insertion window required.', '2026-06-08 14:56:30', 'ATLAS-03', 'ACTIVE'),
(12, 'Satellogic Imaging', 'operations@satellogic.com', 'Low Earth Orbit (LEO)', 'Deploy 2 high-resolution imagery satellites. Re-entry timeline verification.', '2026-06-08 14:56:30', 'UNASSIGNED', 'PENDING'),
(13, 'BlackSky Intelligence', 'imaging@blacksky.com', 'Low Earth Orbit (LEO)', 'Deploy 1 Earth observations telemetry platform. Transponder configuration set.', '2026-06-08 14:56:30', 'BLUE-03', 'ACTIVE'),
(14, 'D-Orbit Orbital Cube', 'deploy@dorbit.space', 'Low Earth Orbit (LEO)', 'Deploy rideshare deployer containing 4 experimental cubesat payloads.', '2026-06-08 14:56:30', 'PROXIMA-03', 'ACTIVE'),
(15, 'Spire Global', 'tracking@spire.com', 'Low Earth Orbit (LEO)', 'Deploy 3 maritime tracking constellation modules. Antenna deployment check required.', '2026-06-08 14:56:30', 'UNASSIGNED', 'PENDING'),
(16, 'Northrop Grumman', 'cargo@ngc.com', 'Low Earth Orbit (LEO)', 'Cygnus cargo resupply mission delivering propellant and scientific payloads.', '2026-06-08 17:20:22', 'PROXIMA-03', 'ACTIVE'),
(17, 'Lockheed Martin Space', 'defense@lmco.com', 'GEO-Stationary Belt', 'Deploy GPS III military navigation satellite. Trajectory requires high-precision insertion.', '2026-06-08 17:20:22', 'PROXIMA-01', 'ACTIVE'),
(18, 'Blue Origin Orbital', 'logistics@blueorigin.com', 'LEO — 600km / 45.0°', 'Rideshare deployment of experimental microgravity research modules.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(19, 'Relativity Space', 'terran@relativity.com', 'Low Earth Orbit (LEO)', '3D-printed metal alloy satellite deployment. Telemetry validation requested.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(20, 'Rocket Lab USA', 'rideshare@rocketlab.com', 'SSO — 550km / 97.4°', 'Rideshare mission carrying 6 commercial climate-sensing cubesat payloads.', '2026-06-08 17:20:22', 'ATLAS-02', 'ACTIVE'),
(21, 'Swarm Technologies', 'iot@swarm.space', 'LEO — 500km / 97.4°', 'Deploy 12 SpaceBEE micro-satellites for global IoT communication network.', '2026-06-08 17:20:22', 'ATLAS-03', 'ACTIVE'),
(22, 'Kepler Communications', 'networks@kepler.space', 'LEO — 575km / 97.9°', 'Deploy 2 high-capacity data transfer modules for maritime telemetry lanes.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(23, 'HawkEye 360', 'rf@he360.com', 'Low Earth Orbit (LEO)', 'Deploy 3 radio-frequency mapping satellites. Precision array clustering required.', '2026-06-08 17:20:22', 'BLUE-03', 'ACTIVE'),
(24, 'ICEYE Radar Solutions', 'sar@iceye.fi', 'SSO — 600km / 98.2°', 'Deploy 1 radar imaging (SAR) satellite. High inclination trajectory lanes.', '2026-06-08 17:20:22', 'BLUE-02', 'ACTIVE'),
(25, 'German Aerospace (DLR)', 'mission@dlr.de', 'Low Earth Orbit (LEO)', 'Deploy experimental robotic servicing manipulator arm payload.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(26, 'CNES French Space Agency', 'operations@cnes.fr', 'Low Earth Orbit (LEO)', 'Deploy atmospheric research sounding platform. Altitude verification post-release.', '2026-06-08 17:20:22', 'ATLAS-01', 'ACTIVE'),
(27, 'UK Space Agency', 'payloads@ukspace.gov.uk', 'Low Earth Orbit (LEO)', 'Deploy 1 maritime tracking payload. Standard launcher interface verified.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(28, 'MIT Space Propulsion', 'spl@mit.edu', 'Low Earth Orbit (LEO)', 'University rideshare payload testing next-generation electrospray thruster units.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(29, 'Stanford Space Systems', 'sssl@stanford.edu', 'Low Earth Orbit (LEO)', 'Student research payload testing deployable solar sail arrays in vacuum conditions.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(30, 'TU Berlin Cubesat Group', 'cubesat@tu-berlin.de', 'Low Earth Orbit (LEO)', 'Deploy 2 radio communication cubesats for amateur orbital bands.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(31, 'Synspective Radar', 'imaging@synspective.com', 'SSO — 550km / 97.5°', 'Deploy 1 StriX-series SAR imaging satellite. Active telemetry synchronization required.', '2026-06-08 17:20:22', 'BLUE-01', 'ACTIVE'),
(32, 'Ghara Earth Imaging', 'satellites@ghara.space', 'Low Earth Orbit (LEO)', 'Deploy 1 high-definition thermal imaging sensor module.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(33, 'Axelspace Corporation', 'axel@axelspace.com', 'Low Earth Orbit (LEO)', 'Deploy 1 commercial GRUS optical observation satellite.', '2026-06-08 17:20:22', 'UNASSIGNED', 'PENDING'),
(34, 'Loft Orbital Solutions', 'rideshare@loftorbital.com', 'Low Earth Orbit (LEO)', 'Multi-tenant rideshare satellite integrating 5 separate environmental sensors.', '2026-06-08 17:20:22', 'ATLAS-04', 'ACTIVE'),
(35, 'Australian Space Agency', 'payload@space.gov.au', 'Low Earth Orbit (LEO)', 'Deploy 1 regional bushfire thermal monitoring telemetry module.', '2026-06-08 17:20:22', 'PROXIMA-02', 'ACTIVE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admins`
--
ALTER TABLE `tb_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tb_fleet`
--
ALTER TABLE `tb_fleet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_missions`
--
ALTER TABLE `tb_missions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admins`
--
ALTER TABLE `tb_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_fleet`
--
ALTER TABLE `tb_fleet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_missions`
--
ALTER TABLE `tb_missions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
