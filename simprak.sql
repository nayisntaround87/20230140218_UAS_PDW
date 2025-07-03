CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','asisten') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `laporan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` INT(11) NOT NULL,
  `modul_id` INT(11) NOT NULL,
  `file_laporan` VARCHAR(255) DEFAULT NULL,
  `nilai` INT(11) DEFAULT NULL,
  `komentar` TEXT DEFAULT NULL,
  `tanggal_upload` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_mahasiswa_id` (`mahasiswa_id`),
  KEY `idx_modul_id` (`modul_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `modul` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `praktikum_id` INT(11) NOT NULL,
  `judul_modul` VARCHAR(100) NOT NULL,
  `file_materi` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_praktikum_id` (`praktikum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `peserta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` INT(11) NOT NULL,
  `praktikum_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_mahasiswa_id` (`mahasiswa_id`),
  KEY `idx_praktikum_id` (`praktikum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `praktikum` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_praktikum` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

