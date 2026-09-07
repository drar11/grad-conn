-- GradConn Laravel database bootstrap
-- Generated from the preserved schema and current Laravel migrations.
-- Fresh database only; run with: mysql --default-character-set=utf8mb4 -u USER -p DATABASE < gradconn.sql
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS `alumni_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `certificate_name` varchar(255) NOT NULL,
  `issuer` varchar(255) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `certificate_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_alumni_certificates_user` (`user_id`),
  CONSTRAINT `fk_alumni_certificates_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `alumni_degrees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `degree_name` varchar(255) NOT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `year_graduated` varchar(20) DEFAULT NULL,
  `diploma_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_alumni_degrees_user` (`user_id`),
  CONSTRAINT `fk_alumni_degrees_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `alumni_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `degree` varchar(255) NOT NULL,
  `field_of_study` varchar(255) DEFAULT NULL,
  `start_year` int(11) DEFAULT NULL,
  `end_year` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_alumni_education_user` (`user_id`),
  CONSTRAINT `fk_alumni_education_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `alumni_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `applicant_fullname` varchar(255) DEFAULT NULL,
  `applicant_email` varchar(255) DEFAULT NULL,
  `applicant_course` varchar(100) DEFAULT NULL,
  `applicant_batch_year` varchar(20) DEFAULT NULL,
  `applicant_birthdate` date DEFAULT NULL,
  `applicant_age` int(11) DEFAULT NULL,
  `applicant_gender` varchar(50) DEFAULT NULL,
  `applicant_civil_status` varchar(50) DEFAULT NULL,
  `applicant_contact_number` varchar(50) DEFAULT NULL,
  `applicant_address` text DEFAULT NULL,
  `applicant_indigenous_tribe` varchar(150) DEFAULT NULL,
  `applicant_special_needs` varchar(150) DEFAULT NULL,
  `applicant_employment_status` varchar(50) DEFAULT NULL,
  `applicant_job_aligned` varchar(10) DEFAULT NULL,
  `applicant_profile_picture` varchar(255) DEFAULT NULL,
  `applicant_career_objective` text DEFAULT NULL,
  `applicant_skills` text DEFAULT NULL,
  `applicant_work_experience` text DEFAULT NULL,
  `applicant_certifications` text DEFAULT NULL,
  `resume_file` varchar(255) DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_apply` (`job_id`,`alumni_id`),
  UNIQUE KEY `unique_application` (`job_id`,`alumni_id`),
  KEY `alumni_id` (`alumni_id`),
  CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`alumni_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `employment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `employment_type` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `job_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_employment_history_user` (`user_id`),
  CONSTRAINT `fk_employment_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `post_start_date` datetime DEFAULT NULL,
  `post_end_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posted_by` (`posted_by`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `interviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `alumni_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `interview_date` date NOT NULL,
  `interview_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'scheduled',
  `email_sent` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `company` varchar(200) NOT NULL,
  `employer_company` varchar(255) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `job_type` varchar(50) DEFAULT 'Full-time',
  `target_course` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `employer_id` int(11) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `posted_by` (`posted_by`),
  KEY `fk_jobs_employer` (`employer_id`),
  CONSTRAINT `fk_jobs_employer` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `post_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_type` varchar(30) NOT NULL DEFAULT 'event',
  `post_id` int(11) DEFAULT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `post_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_user_id` int(11) NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `post_type` varchar(30) NOT NULL,
  `post_id` int(11) NOT NULL,
  `notification_type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_post_notifications_recipient` (`recipient_user_id`),
  KEY `idx_post_notifications_post` (`post_type`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `post_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_type` varchar(30) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reaction_type` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_post_user_reaction` (`post_type`,`post_id`,`user_id`),
  KEY `idx_post_reactions_post` (`post_type`,`post_id`),
  KEY `idx_post_reactions_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `security_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` varchar(255) DEFAULT NULL,
  `ip_address` varchar(64) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `security_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(150) NOT NULL,
  `employer_company` varchar(150) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','alumni','employer','alumni_officer') NOT NULL DEFAULT 'alumni',
  `email` varchar(150) DEFAULT NULL,
  `course` varchar(120) DEFAULT NULL,
  `batch_year` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `indigenous_tribe` varchar(150) DEFAULT NULL,
  `special_needs` varchar(100) DEFAULT NULL,
  `employment_status` varchar(20) DEFAULT NULL,
  `job_aligned` varchar(10) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  `career_objective` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `work_experience` text DEFAULT NULL,
  `certifications` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS employer_activity_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                employer_id INT NOT NULL,
                alumni_id INT NULL,
                offer_id INT NULL,
                action VARCHAR(100) NOT NULL,
                details TEXT NULL,
                course_filter VARCHAR(100) NULL,
                batch_filter VARCHAR(100) NULL,
                skill_search VARCHAR(255) NULL,
                result_count INT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_employer_id (employer_id),
                INDEX idx_alumni_id (alumni_id),
                INDEX idx_offer_id (offer_id)
            );

-- Job Offers Table
CREATE TABLE IF NOT EXISTS job_offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    alumni_id INT NOT NULL,
    offer_token VARCHAR(255) UNIQUE NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message LONGTEXT NOT NULL,
    status ENUM('sent', 'accepted', 'declined', 'expired') DEFAULT 'sent',
    accepted_at TIMESTAMP NULL,
    declined_at TIMESTAMP NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (alumni_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (offer_token),
    INDEX idx_alumni_status (alumni_id, status),
    INDEX idx_employer_status (employer_id, status)
);

-- Current Laravel additions. This file is intended for a fresh MySQL database.
ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL;
ALTER TABLE users ADD COLUMN remember_token VARCHAR(100) NULL;
ALTER TABLE events ADD COLUMN category VARCHAR(30) NOT NULL DEFAULT 'announcement';
ALTER TABLE events ADD COLUMN is_archived TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE events ADD COLUMN archived_at DATETIME NULL;
ALTER TABLE events ADD COLUMN source VARCHAR(30) NOT NULL DEFAULT 'gradconn';
ALTER TABLE events ADD COLUMN source_post_id VARCHAR(191) NULL UNIQUE;
ALTER TABLE events ADD COLUMN source_name VARCHAR(255) NULL;
ALTER TABLE events ADD COLUMN source_url TEXT NULL;
ALTER TABLE events ADD COLUMN external_image_url TEXT NULL;
ALTER TABLE post_comments MODIFY post_id INT NOT NULL, MODIFY user_id INT NOT NULL, MODIFY comment TEXT NOT NULL, ADD INDEX idx_post_comments_parent (parent_comment_id);

CREATE TABLE IF NOT EXISTS `cache` (`key` VARCHAR(255) NOT NULL, `value` MEDIUMTEXT NOT NULL, `expiration` INT NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `cache_locks` (`key` VARCHAR(255) NOT NULL, `owner` VARCHAR(255) NOT NULL, `expiration` INT NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (`email` VARCHAR(255) NOT NULL, `token` VARCHAR(255) NOT NULL, `created_at` TIMESTAMP NULL, PRIMARY KEY (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `sessions` (`id` VARCHAR(255) NOT NULL, `user_id` INT NULL, `ip_address` VARCHAR(45) NULL, `user_agent` TEXT NULL, `payload` LONGTEXT NOT NULL, `last_activity` INT NOT NULL, PRIMARY KEY (`id`), KEY `sessions_user_id_index` (`user_id`), KEY `sessions_last_activity_index` (`last_activity`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `queue_jobs` (`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, `queue` VARCHAR(255) NOT NULL, `payload` LONGTEXT NOT NULL, `attempts` TINYINT UNSIGNED NOT NULL, `reserved_at` INT UNSIGNED NULL, `available_at` INT UNSIGNED NOT NULL, `created_at` INT UNSIGNED NOT NULL, PRIMARY KEY (`id`), KEY `queue_jobs_queue_index` (`queue`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `failed_jobs` (`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, `uuid` VARCHAR(255) NOT NULL UNIQUE, `connection` TEXT NOT NULL, `queue` TEXT NOT NULL, `payload` LONGTEXT NOT NULL, `exception` LONGTEXT NOT NULL, `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `audit_logs` (`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, `user_id` INT NULL, `method` VARCHAR(10) NOT NULL, `path` VARCHAR(255) NOT NULL, `status` SMALLINT UNSIGNED NOT NULL, `ip_address` VARCHAR(45) NULL, `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), KEY `audit_logs_user_id_index` (`user_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `jobs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `applications` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `post_comments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `post_reactions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `post_notifications` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `job_offers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS=1;
