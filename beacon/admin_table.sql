-- Admin Table Creation SQL
-- Run this SQL manually in your database to create the admin table
-- Database: beacon_db (as configured in Database.php)

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert the default admin credentials (username: admin, password: admin)
INSERT INTO `admin` (`username`, `password`) VALUES ('admin', 'admin');

