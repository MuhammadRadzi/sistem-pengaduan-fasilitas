CREATE DATABASE IF NOT EXISTS db_pengaduan_fasilitas;
USE db_pengaduan_fasilitas;

CREATE TABLE IF NOT EXISTS facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id INT NOT NULL,
    reporter_name VARCHAR(100) NOT NULL,
    issue_description TEXT NOT NULL,
    status ENUM('Fasilitas', 'Lapor Masalah', 'Diproses', 'Ditangani', 'Selesai') DEFAULT 'Lapor Masalah',
    action_note TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE
);

INSERT INTO facilities (name, location, description, created_at) VALUES
('AC Ruang Kelas 12A', 'Kelas 12A', 'AC Daikin', NOW()),
('TV', 'Lab Komputer 1', 'Hisense Merah Putih', NOW()),
('Toilet Ikhwan', 'Dekat Tangga Ikhwan', 'Toilet', NOW()),
('Lampu LED', 'Kelas 12A', '', NOW()),
('Kipas', 'Kelas 12A', 'Kipas Panasonic', NOW()),
('Wi-Fi', 'Kelas 12A', '', NOW()),
('Meja dan Kursi', 'Kelas 12A', '', NOW()),
('Wastafel', 'Kantin', '', NOW()),
('Lapangan', 'Tengah Gedung', '', NOW()),
('Parkiran', 'Samping Sekolah', '', NOW()),
('Masdik', 'Lantai 2', '', NOW());