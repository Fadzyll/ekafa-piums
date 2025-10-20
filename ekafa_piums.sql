-- ============================================================================
-- E-KAFA PIUMS Database Schema - PHASE 1 (Simplified for FYP)
-- Version: 2.0 - Optimized 25-Table Core System
-- Date: October 2025
-- Focus: Core functionality with smart features, reduced complexity
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

DROP DATABASE IF EXISTS ekafa_piums;
CREATE DATABASE ekafa_piums CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ekafa_piums;

-- ============================================================================
-- SECTION 1: USER MANAGEMENT LAYER (3 Tables)
-- Merged user_details + user_job into user_profiles for simplicity
-- Removed family_relationships (defer to Phase 2)
-- ============================================================================

-- Table 1: users (Core Authentication)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'Login email',
    password_hash VARCHAR(255) NOT NULL COMMENT 'Bcrypt hashed password',
    auth_key VARCHAR(32) NOT NULL COMMENT 'For "Remember Me" functionality',
    `role` ENUM('Admin', 'Teacher', 'Parent') NOT NULL,
    `status` ENUM('Active', 'Inactive', 'Suspended') NOT NULL DEFAULT 'Active',
    
    -- Security fields
    failed_login_attempts TINYINT NOT NULL DEFAULT 0,
    account_locked_until TIMESTAMP NULL,
    password_reset_token VARCHAR(255) NULL UNIQUE,
    
    -- Timestamps
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    
    INDEX idx_users_email (email),
    INDEX idx_users_role_status (`role`, `status`),
    INDEX idx_users_auth_key (auth_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Core user authentication';

-- Table 2: user_profiles (Personal + Employment Data Merged)
DROP TABLE IF EXISTS user_profiles;
CREATE TABLE user_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    
    -- Personal Information
    full_name VARCHAR(255) NOT NULL,
    ic_number VARCHAR(12) NOT NULL UNIQUE COMMENT 'YYMMDDPBXXXG (no dashes)',
    date_of_birth DATE NOT NULL COMMENT 'Extracted from IC',
    gender ENUM('Male', 'Female') NOT NULL,
    race VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) NOT NULL COMMENT 'Format: +60xxxxxxxxx or 01xxxxxxxx',
    citizenship VARCHAR(100) NOT NULL DEFAULT 'Malaysian',
    marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed') NULL,
    
    -- Address
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL,
    postcode VARCHAR(10) NOT NULL,
    state VARCHAR(100) NOT NULL,
    profile_picture_url VARCHAR(500) NULL,
    
    -- Employment Information (merged from employment_details)
    job_title VARCHAR(100) NULL,
    employer_name VARCHAR(255) NULL,
    employer_phone VARCHAR(20) NULL,
    monthly_gross_salary DECIMAL(10,2) NULL COMMENT 'RM',
    monthly_net_salary DECIMAL(10,2) NULL COMMENT 'RM',
    is_ums_staff BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'For salary deduction eligibility',
    
    -- Partner/Spouse Information (simplified - no separate table)
    spouse_name VARCHAR(255) NULL,
    spouse_ic_number VARCHAR(12) NULL,
    spouse_phone VARCHAR(20) NULL,
    spouse_job_title VARCHAR(100) NULL,
    spouse_employer VARCHAR(255) NULL,
    spouse_monthly_salary DECIMAL(10,2) NULL,
    
    -- Timestamps
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_profile_ic (ic_number),
    INDEX idx_profile_user (user_id),
    INDEX idx_profile_phone (phone_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='User personal and employment data (merged)';

-- Table 3: teacher_profiles (Teacher-Specific Extensions)
DROP TABLE IF EXISTS teacher_profiles;
CREATE TABLE teacher_profiles (
    teacher_id INT PRIMARY KEY COMMENT 'References users.user_id',
    
    -- Employment Info
    date_first_appointment DATE NOT NULL,
    teacher_status ENUM('Active', 'On Leave', 'Resigned') NOT NULL DEFAULT 'Active',
    specialization VARCHAR(100) NULL COMMENT 'Tajwid, Hafazan, etc.',
    
    -- Teaching Preferences
    can_teach_years SET('1','2','3','4','5','6') NOT NULL DEFAULT '1,2,3,4,5,6',
    preferred_session ENUM('Morning', 'Evening', 'Both') NULL,
    max_classes TINYINT NOT NULL DEFAULT 1,
    current_classes_assigned TINYINT NOT NULL DEFAULT 0 COMMENT 'Auto-updated by trigger',
    
    -- Education (simplified - single highest qualification)
    highest_qualification ENUM('SPM', 'STPM', 'Diploma', 'Degree', 'Master', 'PhD') NOT NULL,
    institution_name VARCHAR(255) NOT NULL,
    graduation_year YEAR NOT NULL,
    cgpa_or_result VARCHAR(50) NULL COMMENT 'e.g., "CGPA 3.75" or "5A 3B"',
    
    -- Timestamps
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (teacher_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_teacher_status (teacher_status),
    INDEX idx_teacher_years (can_teach_years)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Teacher-specific information';

-- ============================================================================
-- SECTION 2: ACADEMIC STRUCTURE LAYER (3 Tables)
-- ============================================================================

-- Table 4: academic_sessions
DROP TABLE IF EXISTS academic_sessions;
CREATE TABLE academic_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    session_year VARCHAR(9) NOT NULL COMMENT 'e.g., "2024/2025"',
    semester ENUM('Semester 1', 'Semester 2') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    
    -- Registration period
    registration_open_date DATE NOT NULL,
    registration_close_date DATE NOT NULL,
    
    -- Status
    is_current BOOLEAN NOT NULL DEFAULT FALSE,
    `status` ENUM('Upcoming', 'Active', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Upcoming',
    
    created_by INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    UNIQUE KEY unique_session (session_year, semester),
    INDEX idx_session_current (is_current, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Academic sessions/semesters';

-- Table 5: subjects
DROP TABLE IF EXISTS subjects;
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(20) NOT NULL UNIQUE COMMENT 'e.g., QUR, TAJ, AQD',
    subject_name VARCHAR(100) NOT NULL COMMENT 'Al-Quran, Tajwid, Akidah, etc.',
    year_level TINYINT NULL COMMENT '1-6, or NULL if applies to all years',
    is_core BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Mandatory vs elective',
    
    -- Grading configuration
    has_part_ab BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'TRUE = Part A + Part B scoring',
    part_a_max DECIMAL(5,2) NOT NULL DEFAULT 30.00 COMMENT 'Max score for Part A',
    part_b_max DECIMAL(5,2) NOT NULL DEFAULT 70.00 COMMENT 'Max score for Part B',
    
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_subject_active_year (is_active, year_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='KAFA subjects';

-- Table 6: classes
DROP TABLE IF EXISTS classes;
CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    teacher_id INT NULL COMMENT 'Assigned teacher',
    
    -- Class Info
    class_name VARCHAR(100) NOT NULL COMMENT 'e.g., Al-Burj, Al-Fatih',
    year_level TINYINT NOT NULL COMMENT '1-6',
    session_type ENUM('Morning', 'Evening') NOT NULL,
    
    -- Capacity
    max_capacity TINYINT NOT NULL DEFAULT 30,
    current_enrollment TINYINT NOT NULL DEFAULT 0 COMMENT 'Auto-updated by trigger',
    
    -- Status
    `status` ENUM('Open', 'Full', 'Closed') NOT NULL DEFAULT 'Open',
    
    -- Schedule
    classroom_location VARCHAR(100) NULL,
    schedule_days SET('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NULL,
    start_time TIME NULL,
    end_time TIME NULL,
    
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (session_id) REFERENCES academic_sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teacher_profiles(teacher_id) ON DELETE SET NULL,
    INDEX idx_classes_session_year (session_id, year_level, `status`),
    INDEX idx_classes_teacher (teacher_id),
    INDEX idx_classes_capacity (current_enrollment, max_capacity, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='KAFA classes';

-- ============================================================================
-- SECTION 3: STUDENT MANAGEMENT LAYER (2 Tables)
-- Merged student_personal_details + student_health_details into students
-- Removed student_guardians (parent is guardian, additional guardians Phase 2)
-- ============================================================================

-- Table 7: students
DROP TABLE IF EXISTS students;
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    parent_user_id INT NOT NULL COMMENT 'Parent who registered',
    current_class_id INT NULL COMMENT 'Currently assigned class',
    
    -- Personal Information
    full_name VARCHAR(255) NOT NULL,
    ic_number VARCHAR(12) NULL UNIQUE COMMENT 'IC if student has one',
    birth_certificate_no VARCHAR(50) NOT NULL UNIQUE,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    race VARCHAR(50) NOT NULL,
    religion VARCHAR(50) NOT NULL DEFAULT 'Islam',
    profile_picture_url VARCHAR(500) NULL,
    
    -- Health Information (merged)
    height_cm DECIMAL(5,2) NULL,
    weight_kg DECIMAL(5,2) NULL,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NULL,
    allergies TEXT NULL,
    chronic_conditions TEXT NULL COMMENT 'Asthma, diabetes, etc.',
    has_disability BOOLEAN NOT NULL DEFAULT FALSE,
    disability_details TEXT NULL,
    current_medications TEXT NULL,
    
    -- Emergency Contact
    emergency_contact_name VARCHAR(255) NOT NULL,
    emergency_contact_phone VARCHAR(20) NOT NULL,
    emergency_contact_relationship VARCHAR(50) NOT NULL,
    
    -- Guardian Info (for pickup)
    guardian_name VARCHAR(255) NULL COMMENT 'If different from parent',
    guardian_phone VARCHAR(20) NULL,
    guardian_vehicle_plate VARCHAR(20) NULL,
    
    -- Academic Information
    primary_school_name VARCHAR(255) NOT NULL,
    enrollment_year CHAR(4) NOT NULL COMMENT 'e.g., "2025"',
    current_year TINYINT NOT NULL COMMENT '1-6',
    session_type ENUM('Morning', 'Evening') NOT NULL,
    
    -- Registration Status
    registration_status ENUM('Pending', 'Approved', 'Rejected', 'Active', 'Graduated', 'Withdrawn') NOT NULL DEFAULT 'Pending',
    registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    approved_by INT NULL COMMENT 'Admin who approved',
    rejection_reason TEXT NULL,
    
    -- Graduation/Withdrawal
    graduation_date DATE NULL,
    withdrawal_date DATE NULL,
    withdrawal_reason TEXT NULL,
    
    -- Timestamps
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (parent_user_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (current_class_id) REFERENCES classes(class_id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL,
    
    INDEX idx_students_parent (parent_user_id),
    INDEX idx_students_class (current_class_id),
    INDEX idx_students_status (registration_status),
    INDEX idx_students_year_session (current_year, session_type, registration_status),
    INDEX idx_students_name (full_name),
    
    -- For auto-registration eligibility checking
    INDEX idx_auto_registration (current_year, registration_status, enrollment_year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Student records (personal + health merged)';

-- Table 8: documents
DROP TABLE IF EXISTS documents;
CREATE TABLE documents (
    document_id INT AUTO_INCREMENT PRIMARY KEY,
    uploaded_by INT NOT NULL,
    
    -- What this document is for
    owner_type ENUM('User', 'Student', 'Payment', 'Absence') NOT NULL,
    owner_id INT NOT NULL COMMENT 'ID of the related entity',
    
    -- Document Details
    document_type ENUM('IC Copy', 'Birth Certificate', 'Photo', 'Medical Report', 'Receipt', 'Absence Letter', 'Other') NOT NULL,
    document_name VARCHAR(255) NOT NULL,
    file_url VARCHAR(500) NOT NULL,
    file_size INT NOT NULL COMMENT 'Bytes',
    mime_type VARCHAR(100) NOT NULL,
    
    -- Verification
    is_verified BOOLEAN NOT NULL DEFAULT FALSE,
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (uploaded_by) REFERENCES users(user_id),
    FOREIGN KEY (verified_by) REFERENCES users(user_id),
    INDEX idx_documents_owner (owner_type, owner_id),
    INDEX idx_documents_type (document_type),
    INDEX idx_documents_verification (is_verified)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Centralized document storage';

-- ============================================================================
-- SECTION 4: ATTENDANCE MANAGEMENT LAYER (2 Tables)
-- Removed attendance_summary (calculate on-demand)
-- ============================================================================

-- Table 9: attendance_records
DROP TABLE IF EXISTS attendance_records;
CREATE TABLE attendance_records (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    
    `status` ENUM('Present', 'Absent', 'Excused', 'Late') NOT NULL,
    check_in_time TIME NULL,
    
    marked_by INT NOT NULL COMMENT 'Teacher who marked',
    marked_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    remarks VARCHAR(255) NULL,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE,
    FOREIGN KEY (marked_by) REFERENCES users(user_id),
    
    UNIQUE KEY unique_student_date (student_id, attendance_date),
    INDEX idx_attendance_class_date (class_id, attendance_date),
    INDEX idx_attendance_status (student_id, `status`, attendance_date),
    
    -- For pattern detection (e.g., absent every Monday)
    INDEX idx_attendance_patterns (student_id, attendance_date, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Daily attendance records';

-- Table 10: absence_forms
DROP TABLE IF EXISTS absence_forms;
CREATE TABLE absence_forms (
    form_id INT AUTO_INCREMENT PRIMARY KEY,
    attendance_id INT NOT NULL UNIQUE COMMENT 'Links to absence record',
    student_id INT NOT NULL,
    submitted_by INT NOT NULL COMMENT 'Parent',
    
    form_title VARCHAR(255) NOT NULL,
    absence_reason TEXT NOT NULL,
    absence_date DATE NOT NULL,
    
    -- Supporting document stored in documents table
    
    `status` ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    reviewed_by INT NULL COMMENT 'Teacher',
    reviewed_at TIMESTAMP NULL,
    review_notes TEXT NULL,
    
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (attendance_id) REFERENCES attendance_records(attendance_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (submitted_by) REFERENCES users(user_id),
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id),
    
    INDEX idx_absence_student (student_id, `status`),
    INDEX idx_absence_date (absence_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Absence excuse forms';

-- ============================================================================
-- SECTION 5: LEARNING MANAGEMENT LAYER (2 Tables)
-- Simplified: Just materials + download tracking
-- Removed assessment_types, student_assessments, report_cards (see grading table)
-- ============================================================================

-- Table 11: learning_materials
DROP TABLE IF EXISTS learning_materials;
CREATE TABLE learning_materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    uploaded_by INT NOT NULL COMMENT 'Teacher',
    
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    material_type ENUM('PDF', 'Video', 'Image', 'Link', 'Audio', 'Document') NOT NULL,
    file_url VARCHAR(500) NULL,
    file_size INT NULL COMMENT 'Bytes',
    
    year_level TINYINT NOT NULL COMMENT '1-6',
    
    -- Simple analytics
    download_count INT NOT NULL DEFAULT 0 COMMENT 'Auto-updated by trigger',
    
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES teacher_profiles(teacher_id),
    
    INDEX idx_materials_subject_year (subject_id, year_level, is_published),
    INDEX idx_materials_teacher (uploaded_by),
    INDEX idx_materials_type (material_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Learning materials';

-- Table 12: material_downloads (Simplified tracking)
DROP TABLE IF EXISTS material_downloads;
CREATE TABLE material_downloads (
    download_id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    user_id INT NOT NULL COMMENT 'Parent who downloaded',
    student_id INT NULL COMMENT 'Which child (if parent has multiple)',
    
    downloaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (material_id) REFERENCES learning_materials(material_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    
    INDEX idx_downloads_material (material_id),
    INDEX idx_downloads_user (user_id),
    INDEX idx_downloads_date (downloaded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Material download tracking';

-- ============================================================================
-- SECTION 6: GRADING SYSTEM (1 Table - Simplified)
-- Replaces complex assessment_types + student_assessments + report_cards
-- ============================================================================

-- Table 13: student_grades
DROP TABLE IF EXISTS student_grades;
CREATE TABLE student_grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    session_id INT NOT NULL,
    
    -- Scoring (matches current Excel workflow)
    part_a_score DECIMAL(5,2) NULL COMMENT 'Out of 30',
    part_b_score DECIMAL(5,2) NULL COMMENT 'Out of 70',
    total_score DECIMAL(5,2) AS (COALESCE(part_a_score, 0) + COALESCE(part_b_score, 0)) STORED,
    percentage DECIMAL(5,2) AS ((COALESCE(part_a_score, 0) + COALESCE(part_b_score, 0)) / 100 * 100) STORED,
    
    -- Grade calculation (can be computed or stored)
    grade CHAR(1) NULL COMMENT 'A/B/C/D/E',
    
    -- Remarks
    teacher_remarks TEXT NULL,
    
    recorded_by INT NOT NULL COMMENT 'Teacher',
    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Lock grading after deadline
    is_locked BOOLEAN NOT NULL DEFAULT FALSE,
    locked_at TIMESTAMP NULL,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES academic_sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES teacher_profiles(teacher_id),
    
    UNIQUE KEY unique_student_subject_session (student_id, subject_id, session_id),
    INDEX idx_grades_student_session (student_id, session_id),
    INDEX idx_grades_class_subject (session_id, subject_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Student grades (simplified)';

-- ============================================================================
-- SECTION 7: FINANCIAL MANAGEMENT LAYER (4 Tables)
-- ============================================================================

-- Table 14: payment_categories
DROP TABLE IF EXISTS payment_categories;
CREATE TABLE payment_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    default_amount DECIMAL(10,2) NOT NULL,
    
    -- Billing behavior
    is_recurring BOOLEAN NOT NULL DEFAULT FALSE,
    billing_frequency ENUM('Once', 'Monthly', 'Semester', 'Yearly') NULL,
    is_mandatory BOOLEAN NOT NULL DEFAULT TRUE,
    
    applies_to_year TINYINT NULL COMMENT 'Specific year 1-6, or NULL for all',
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_category_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Payment categories master';

-- Table 15: student_ledgers
DROP TABLE IF EXISTS student_ledgers;
CREATE TABLE student_ledgers (
    ledger_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_id INT NOT NULL,
    
    -- Financial summary (auto-updated by triggers)
    total_payable DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_paid DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    balance DECIMAL(10,2) AS (total_payable - total_paid) STORED,
    
    last_payment_date DATE NULL,
    
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES academic_sessions(session_id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_student_session_ledger (student_id, session_id),
    INDEX idx_ledger_student (student_id),
    
    -- For auto-registration eligibility (no outstanding balance)
    INDEX idx_ledger_balance (student_id, balance)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Student payment ledgers';

-- Table 16: payment_items
DROP TABLE IF EXISTS payment_items;
CREATE TABLE payment_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    ledger_id INT NOT NULL,
    category_id INT NOT NULL,
    
    item_description VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    due_date DATE NULL,
    
    is_paid BOOLEAN NOT NULL DEFAULT FALSE,
    paid_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    
    added_by INT NOT NULL COMMENT 'Teacher/Admin',
    added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (ledger_id) REFERENCES student_ledgers(ledger_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES payment_categories(category_id),
    FOREIGN KEY (added_by) REFERENCES users(user_id),
    
    INDEX idx_items_ledger (ledger_id, is_paid),
    INDEX idx_items_due (due_date, is_paid),
    
    -- For payment prediction queries
    INDEX idx_items_payment_status (is_paid, due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Individual payment items';

-- Table 17: payment_transactions
DROP TABLE IF EXISTS payment_transactions;
CREATE TABLE payment_transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    ledger_id INT NOT NULL,
    paid_by INT NOT NULL COMMENT 'Parent',
    
    payment_method ENUM('e-Payment UMS', 'Cash', 'Bank Transfer', 'Online Banking') NOT NULL,
    transaction_reference VARCHAR(100) NULL COMMENT 'e-Payment reference',
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    
    -- Receipt document stored in documents table
    
    -- Two-stage approval
    verification_status ENUM('Pending', 'Verified', 'Rejected') NOT NULL DEFAULT 'Pending',
    verified_by INT NULL COMMENT 'Teacher',
    verified_at TIMESTAMP NULL,
    verification_notes TEXT NULL,
    
    approval_status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    approved_by INT NULL COMMENT 'Admin',
    approved_at TIMESTAMP NULL,
    approval_notes TEXT NULL,
    
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (ledger_id) REFERENCES student_ledgers(ledger_id) ON DELETE CASCADE,
    FOREIGN KEY (paid_by) REFERENCES users(user_id),
    FOREIGN KEY (verified_by) REFERENCES users(user_id),
    FOREIGN KEY (approved_by) REFERENCES users(user_id),
    
    INDEX idx_transactions_ledger (ledger_id),
    INDEX idx_transactions_status (verification_status, approval_status),
    INDEX idx_transactions_date (payment_date),
    
    -- For payment pattern analysis
    INDEX idx_payment_patterns (paid_by, payment_date, verification_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Payment transactions';

-- ============================================================================
-- SECTION 8: ACTIVITY MANAGEMENT LAYER (1 Table - Simplified)
-- Removed activity_participants and activity_photos (defer to Phase 2)
-- ============================================================================

-- Table 18: activities
DROP TABLE IF EXISTS activities;
CREATE TABLE activities (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    created_by INT NOT NULL COMMENT 'Teacher',
    
    activity_name VARCHAR(255) NOT NULL,
    activity_type ENUM('Field Trip', 'Competition', 'Celebration', 'Workshop', 'Sports Day', 'Exam', 'Other') NOT NULL,
    description TEXT NULL,
    
    activity_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    location VARCHAR(255) NULL,
    
    target_year_levels SET('1','2','3','4','5','6') NOT NULL COMMENT 'Which years can participate',
    
    `status` ENUM('Scheduled', 'Ongoing', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Scheduled',
    cancellation_reason TEXT NULL,
    
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES teacher_profiles(teacher_id),
    
    INDEX idx_activities_date (activity_date, `status`),
    INDEX idx_activities_year (target_year_levels),
    
    -- Prevent double-booking same date/time
    UNIQUE KEY unique_datetime (activity_date, start_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='KAFA activities and events';

-- ============================================================================
-- SECTION 9: INVENTORY MANAGEMENT LAYER (3 Tables)
-- ============================================================================

-- Table 19: inventory_categories
DROP TABLE IF EXISTS inventory_categories;
CREATE TABLE inventory_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    
    is_consumable BOOLEAN NOT NULL DEFAULT FALSE,
    reorder_threshold INT NULL COMMENT 'Min qty before reorder alert',
    
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Inventory categories';

-- Table 20: inventory_items
DROP TABLE IF EXISTS inventory_items;
CREATE TABLE inventory_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    
    item_name VARCHAR(255) NOT NULL,
    item_code VARCHAR(50) NOT NULL UNIQUE COMMENT 'SKU',
    description TEXT NULL,
    
    unit_of_measure VARCHAR(20) NOT NULL COMMENT 'pcs, box, kg',
    current_quantity INT NOT NULL DEFAULT 0 COMMENT 'Auto-updated by trigger',
    minimum_stock_level INT NOT NULL DEFAULT 0,
    
    unit_price DECIMAL(10,2) NULL,
    storage_location VARCHAR(100) NULL,
    
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES inventory_categories(category_id),
    
    INDEX idx_items_category (category_id),
    INDEX idx_items_code (item_code),
    
    -- For low stock alerts
    INDEX idx_items_stock_level (current_quantity, minimum_stock_level, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Inventory items';

-- Table 21: stock_movements
DROP TABLE IF EXISTS stock_movements;
CREATE TABLE stock_movements (
    movement_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    
    movement_type ENUM('Purchase', 'Issue', 'Return', 'Adjustment', 'Write-Off') NOT NULL,
    quantity INT NOT NULL COMMENT 'Positive for IN, negative for OUT',
    
    previous_quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    
    unit_price DECIMAL(10,2) NULL,
    reference_no VARCHAR(100) NULL COMMENT 'PO, Invoice, etc.',
    
    issued_to_student_id INT NULL COMMENT 'If issued to specific student',
    reason TEXT NULL,
    
    recorded_by INT NOT NULL,
    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (item_id) REFERENCES inventory_items(item_id) ON DELETE CASCADE,
    FOREIGN KEY (issued_to_student_id) REFERENCES students(student_id) ON DELETE SET NULL,
    FOREIGN KEY (recorded_by) REFERENCES users(user_id),
    
    INDEX idx_movements_item (item_id, recorded_at),
    INDEX idx_movements_type (movement_type),
    INDEX idx_movements_date (recorded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Stock movement history';

-- ============================================================================
-- SECTION 10: NOTIFICATION LAYER (2 Tables - Simplified)
-- Removed notification_deliveries and user_notification_preferences (Phase 2)
-- ============================================================================

-- Table 22: notification_templates
DROP TABLE IF EXISTS notification_templates;
CREATE TABLE notification_templates (
    template_id INT AUTO_INCREMENT PRIMARY KEY,
    template_name VARCHAR(100) NOT NULL UNIQUE,
    trigger_event VARCHAR(100) NOT NULL COMMENT 'payment_due, absence_alert, etc.',
    
    -- Email template
    email_subject VARCHAR(255) NOT NULL,
    email_body TEXT NOT NULL COMMENT 'Use {{placeholder}} for variables',
    
    -- WhatsApp template (optional)
    whatsapp_body TEXT NULL,
    
    -- Available variables (JSON array)
    variables JSON NULL COMMENT '["student_name", "amount", "date"]',
    
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_templates_event (trigger_event, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Notification templates';

-- Table 23: notifications
DROP TABLE IF EXISTS notifications;
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NULL,
    
    recipient_user_id INT NOT NULL,
    student_id INT NULL COMMENT 'Related student if applicable',
    
    notification_type ENUM('Payment Reminder', 'Absence Alert', 'Activity Reminder', 'Registration', 'Grade Posted', 'Receipt Uploaded', 'Low Stock', 'Custom') NOT NULL,
    priority ENUM('Low', 'Normal', 'High', 'Urgent') NOT NULL DEFAULT 'Normal',
    
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    
    -- Delivery
    send_via ENUM('Email', 'WhatsApp', 'Both') NOT NULL DEFAULT 'Email',
    scheduled_for TIMESTAMP NOT NULL COMMENT 'When to send',
    
    `status` ENUM('Scheduled', 'Sent', 'Failed', 'Cancelled') NOT NULL DEFAULT 'Scheduled',
    sent_at TIMESTAMP NULL,
    error_message TEXT NULL,
    
    created_by INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (template_id) REFERENCES notification_templates(template_id) ON DELETE SET NULL,
    FOREIGN KEY (recipient_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    
    INDEX idx_notifications_recipient (recipient_user_id, `status`),
    INDEX idx_notifications_scheduled (scheduled_for, `status`),
    INDEX idx_notifications_type (notification_type, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Notification queue';

-- ============================================================================
-- SECTION 11: AUDIT & SYSTEM (2 Tables)
-- ============================================================================

-- Table 24: audit_logs
DROP TABLE IF EXISTS audit_logs;
CREATE TABLE audit_logs (
    log_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    
    `action` VARCHAR(100) NOT NULL COMMENT 'CREATE, UPDATE, DELETE, LOGIN, etc.',
    entity_type VARCHAR(100) NOT NULL COMMENT 'Table name',
    entity_id INT NULL COMMENT 'Record ID',
    
    old_values JSON NULL COMMENT 'Before state',
    new_values JSON NULL COMMENT 'After state',
    
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    
    INDEX idx_audit_user (user_id),
    INDEX idx_audit_entity (entity_type, entity_id),
    INDEX idx_audit_date (created_at),
    INDEX idx_audit_action (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Audit trail';

-- Table 25: system_settings
DROP TABLE IF EXISTS system_settings;
CREATE TABLE system_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    setting_type ENUM('String', 'Integer', 'Boolean', 'JSON') NOT NULL DEFAULT 'String',
    
    description TEXT NULL,
    is_public BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Can non-admins access',
    
    updated_by INT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (updated_by) REFERENCES users(user_id) ON DELETE SET NULL,
    
    INDEX idx_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='System configuration';

-- ============================================================================
-- END OF PHASE 1 SCHEMA (25 Tables)
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- SUMMARY OF 25 TABLES
-- ============================================================================
-- Section 1: User Management (3 tables)
--   1. users
--   2. user_profiles
--   3. teacher_profiles
--
-- Section 2: Academic Structure (3 tables)
--   4. academic_sessions
--   5. subjects
--   6. classes
--
-- Section 3: Student Management (2 tables)
--   7. students
--   8. documents
--
-- Section 4: Attendance Management (2 tables)
--   9. attendance_records
--   10. absence_forms
--
-- Section 5: Learning Management (2 tables)
--   11. learning_materials
--   12. material_downloads
--
-- Section 6: Grading System (1 table)
--   13. student_grades
--
-- Section 7: Financial Management (4 tables)
--   14. payment_categories
--   15. student_ledgers
--   16. payment_items
--   17. payment_transactions
--
-- Section 8: Activity Management (1 table)
--   18. activities
--
-- Section 9: Inventory Management (3 tables)
--   19. inventory_categories
--   20. inventory_items
--   21. stock_movements
--
-- Section 10: Notification Layer (2 tables)
--   22. notification_templates
--   23. notifications
--
-- Section 11: Audit & System (2 tables)
--   24. audit_logs
--   25. system_settings
-- ============================================================================

-- ============================================================================
-- E-KAFA PIUMS Database Triggers - AUTO-UPDATE LOGIC
-- Version: 2.0
-- Purpose: Automate data consistency and counter updates
-- ============================================================================

USE ekafa_piums;

DELIMITER $$

-- ============================================================================
-- TRIGGER SET 1: CLASS ENROLLMENT MANAGEMENT
-- Purpose: Auto-update class current_enrollment when students are added/removed
-- ============================================================================

-- Trigger 1: After student registration approved
DROP TRIGGER IF EXISTS after_student_approved$$
CREATE TRIGGER after_student_approved
AFTER UPDATE ON students
FOR EACH ROW
BEGIN
    -- When student is approved and assigned to class
    IF NEW.registration_status = 'Approved' 
       AND OLD.registration_status = 'Pending'
       AND NEW.current_class_id IS NOT NULL THEN
        
        UPDATE classes 
        SET current_enrollment = current_enrollment + 1
        WHERE class_id = NEW.current_class_id;
        
        -- Auto-close class if full
        UPDATE classes 
        SET `status` = 'Full'
        WHERE class_id = NEW.current_class_id
          AND current_enrollment >= max_capacity;
    END IF;
    
    -- When student changes class (transfer)
    IF OLD.current_class_id IS NOT NULL 
       AND NEW.current_class_id IS NOT NULL
       AND OLD.current_class_id != NEW.current_class_id THEN
        
        -- Decrease old class
        UPDATE classes 
        SET current_enrollment = current_enrollment - 1
        WHERE class_id = OLD.current_class_id;
        
        -- Increase new class
        UPDATE classes 
        SET current_enrollment = current_enrollment + 1
        WHERE class_id = NEW.current_class_id;
    END IF;
    
    -- When student withdraws or graduates
    IF OLD.current_class_id IS NOT NULL
       AND (NEW.registration_status IN ('Withdrawn', 'Graduated')) THEN
        
        UPDATE classes 
        SET current_enrollment = current_enrollment - 1
        WHERE class_id = OLD.current_class_id;
        
        -- Reopen class if was full
        UPDATE classes 
        SET `status` = 'Open'
        WHERE class_id = OLD.current_class_id
          AND current_enrollment < max_capacity
          AND `status` = 'Full';
    END IF;
END$$

-- ============================================================================
-- TRIGGER SET 2: TEACHER CLASS ASSIGNMENT
-- Purpose: Track how many classes assigned to each teacher
-- ============================================================================

-- Trigger 2: After class assigned to teacher
DROP TRIGGER IF EXISTS after_class_teacher_assigned$$
CREATE TRIGGER after_class_teacher_assigned
AFTER UPDATE ON classes
FOR EACH ROW
BEGIN
    -- When teacher is newly assigned
    IF NEW.teacher_id IS NOT NULL 
       AND (OLD.teacher_id IS NULL OR OLD.teacher_id != NEW.teacher_id) THEN
        
        UPDATE teacher_profiles 
        SET current_classes_assigned = (
            SELECT COUNT(*) FROM classes 
            WHERE teacher_id = NEW.teacher_id
              AND `status` IN ('Open', 'Full')
        )
        WHERE teacher_id = NEW.teacher_id;
    END IF;
    
    -- When teacher is removed from class
    IF OLD.teacher_id IS NOT NULL 
       AND (NEW.teacher_id IS NULL OR NEW.teacher_id != OLD.teacher_id) THEN
        
        UPDATE teacher_profiles 
        SET current_classes_assigned = (
            SELECT COUNT(*) FROM classes 
            WHERE teacher_id = OLD.teacher_id
              AND `status` IN ('Open', 'Full')
        )
        WHERE teacher_id = OLD.teacher_id;
    END IF;
END$$

-- ============================================================================
-- TRIGGER SET 3: PAYMENT LEDGER AUTO-UPDATE
-- Purpose: Auto-update total_paid when payments are approved
-- ============================================================================

-- Trigger 3: After payment transaction approved
DROP TRIGGER IF EXISTS after_payment_approved$$
CREATE TRIGGER after_payment_approved
AFTER UPDATE ON payment_transactions
FOR EACH ROW
BEGIN
    -- When payment is fully approved (verified + approved)
    IF NEW.approval_status = 'Approved' 
       AND OLD.approval_status != 'Approved' THEN
        
        -- Update ledger totals
        UPDATE student_ledgers 
        SET total_paid = total_paid + NEW.amount_paid,
            last_payment_date = NEW.payment_date
        WHERE ledger_id = NEW.ledger_id;
        
    END IF;
    
    -- When payment is rejected (rollback if was previously approved)
    IF NEW.approval_status = 'Rejected'
       AND OLD.approval_status = 'Approved' THEN
        
        UPDATE student_ledgers 
        SET total_paid = total_paid - NEW.amount_paid
        WHERE ledger_id = NEW.ledger_id;
    END IF;
END$$

-- Trigger 4: After new payment item added
DROP TRIGGER IF EXISTS after_payment_item_added$$
CREATE TRIGGER after_payment_item_added
AFTER INSERT ON payment_items
FOR EACH ROW
BEGIN
    -- Update ledger payable total
    UPDATE student_ledgers 
    SET total_payable = total_payable + NEW.amount
    WHERE ledger_id = NEW.ledger_id;
END$$

-- Trigger 5: When payment item amount is updated
DROP TRIGGER IF EXISTS after_payment_item_updated$$
CREATE TRIGGER after_payment_item_updated
AFTER UPDATE ON payment_items
FOR EACH ROW
BEGIN
    -- Recalculate total_payable
    IF NEW.amount != OLD.amount THEN
        UPDATE student_ledgers 
        SET total_payable = total_payable - OLD.amount + NEW.amount
        WHERE ledger_id = NEW.ledger_id;
    END IF;
END$$

-- Trigger 6: When payment item is deleted
DROP TRIGGER IF EXISTS after_payment_item_deleted$$
CREATE TRIGGER after_payment_item_deleted
AFTER DELETE ON payment_items
FOR EACH ROW
BEGIN
    -- Decrease total_payable
    UPDATE student_ledgers 
    SET total_payable = total_payable - OLD.amount
    WHERE ledger_id = OLD.ledger_id;
END$$

-- ============================================================================
-- TRIGGER SET 4: LEARNING MATERIAL DOWNLOAD TRACKING
-- Purpose: Auto-increment download_count
-- ============================================================================

-- Trigger 7: After material downloaded
DROP TRIGGER IF EXISTS after_material_downloaded$$
CREATE TRIGGER after_material_downloaded
AFTER INSERT ON material_downloads
FOR EACH ROW
BEGIN
    UPDATE learning_materials 
    SET download_count = download_count + 1
    WHERE material_id = NEW.material_id;
END$$

-- ============================================================================
-- TRIGGER SET 5: STOCK MOVEMENT AUTO-UPDATE
-- Purpose: Update current_quantity when stock moves
-- ============================================================================

-- Trigger 8: After stock movement
DROP TRIGGER IF EXISTS after_stock_movement$$
CREATE TRIGGER after_stock_movement
AFTER INSERT ON stock_movements
FOR EACH ROW
BEGIN
    -- Update item quantity
    UPDATE inventory_items 
    SET current_quantity = NEW.new_quantity
    WHERE item_id = NEW.item_id;
END$$

-- ============================================================================
-- TRIGGER SET 6: AUTO-CREATE LEDGER FOR NEW STUDENTS
-- Purpose: When student approved, create their payment ledger
-- ============================================================================

-- Trigger 9: Create ledger after student approval
DROP TRIGGER IF EXISTS after_student_approved_create_ledger$$
CREATE TRIGGER after_student_approved_create_ledger
AFTER UPDATE ON students
FOR EACH ROW
BEGIN
    DECLARE current_session_id INT;
    
    -- When student is approved
    IF NEW.registration_status = 'Approved' 
       AND OLD.registration_status = 'Pending' THEN
        
        -- Get current active session
        SELECT session_id INTO current_session_id
        FROM academic_sessions
        WHERE is_current = TRUE
        LIMIT 1;
        
        -- Create ledger if session exists
        IF current_session_id IS NOT NULL THEN
            INSERT INTO student_ledgers (student_id, session_id, total_payable, total_paid)
            VALUES (NEW.student_id, current_session_id, 0.00, 0.00)
            ON DUPLICATE KEY UPDATE ledger_id = ledger_id; -- Prevent duplicate
        END IF;
    END IF;
END$$

-- ============================================================================
-- TRIGGER SET 7: ABSENCE FORM AUTO-UPDATE ATTENDANCE STATUS
-- Purpose: When absence form is approved, change attendance to 'Excused'
-- ============================================================================

-- Trigger 10: After absence form approved
DROP TRIGGER IF EXISTS after_absence_approved$$
CREATE TRIGGER after_absence_approved
AFTER UPDATE ON absence_forms
FOR EACH ROW
BEGIN
    -- When absence form is approved
    IF NEW.status = 'Approved' AND OLD.status = 'Pending' THEN
        
        UPDATE attendance_records 
        SET `status` = 'Excused'
        WHERE attendance_id = NEW.attendance_id;
    END IF;
    
    -- When absence form is rejected
    IF NEW.status = 'Rejected' AND OLD.status = 'Pending' THEN
        
        -- Keep as 'Absent' (no change needed, just log)
        -- Could add notification here via INSERT into notifications
        NULL;
    END IF;
END$$

-- ============================================================================
-- TRIGGER SET 8: AUTO-POPULATE IC-DERIVED FIELDS
-- Purpose: Extract DOB and gender from Malaysian IC number
-- ============================================================================

-- Trigger 11: Before user profile insert
DROP TRIGGER IF EXISTS before_user_profile_insert$$
CREATE TRIGGER before_user_profile_insert
BEFORE INSERT ON user_profiles
FOR EACH ROW
BEGIN
    DECLARE birth_year INT;
    DECLARE birth_month INT;
    DECLARE birth_day INT;
    DECLARE last_digit INT;
    
    -- If IC number provided, extract data
    IF NEW.ic_number IS NOT NULL AND LENGTH(NEW.ic_number) = 12 THEN
        
        -- Extract date parts (YYMMDDPBXXXG)
        SET birth_year = CAST(SUBSTRING(NEW.ic_number, 1, 2) AS UNSIGNED);
        SET birth_month = CAST(SUBSTRING(NEW.ic_number, 3, 2) AS UNSIGNED);
        SET birth_day = CAST(SUBSTRING(NEW.ic_number, 5, 2) AS UNSIGNED);
        
        -- Determine century (00-25 = 2000s, 26-99 = 1900s)
        IF birth_year <= 25 THEN
            SET birth_year = 2000 + birth_year;
        ELSE
            SET birth_year = 1900 + birth_year;
        END IF;
        
        -- Set date_of_birth
        SET NEW.date_of_birth = STR_TO_DATE(
            CONCAT(birth_year, '-', birth_month, '-', birth_day),
            '%Y-%m-%d'
        );
        
        -- Extract gender from last digit (odd = male, even = female)
        SET last_digit = CAST(SUBSTRING(NEW.ic_number, 12, 1) AS UNSIGNED);
        
        IF MOD(last_digit, 2) = 0 THEN
            SET NEW.gender = 'Female';
        ELSE
            SET NEW.gender = 'Male';
        END IF;
    END IF;
END$$

-- Trigger 12: Before user profile update (in case IC changes)
DROP TRIGGER IF EXISTS before_user_profile_update$$
CREATE TRIGGER before_user_profile_update
BEFORE UPDATE ON user_profiles
FOR EACH ROW
BEGIN
    DECLARE birth_year INT;
    DECLARE birth_month INT;
    DECLARE birth_day INT;
    DECLARE last_digit INT;
    
    -- If IC number changed, recalculate
    IF NEW.ic_number IS NOT NULL 
       AND NEW.ic_number != OLD.ic_number 
       AND LENGTH(NEW.ic_number) = 12 THEN
        
        SET birth_year = CAST(SUBSTRING(NEW.ic_number, 1, 2) AS UNSIGNED);
        SET birth_month = CAST(SUBSTRING(NEW.ic_number, 3, 2) AS UNSIGNED);
        SET birth_day = CAST(SUBSTRING(NEW.ic_number, 5, 2) AS UNSIGNED);
        
        IF birth_year <= 25 THEN
            SET birth_year = 2000 + birth_year;
        ELSE
            SET birth_year = 1900 + birth_year;
        END IF;
        
        SET NEW.date_of_birth = STR_TO_DATE(
            CONCAT(birth_year, '-', birth_month, '-', birth_day),
            '%Y-%m-%d'
        );
        
        SET last_digit = CAST(SUBSTRING(NEW.ic_number, 12, 1) AS UNSIGNED);
        
        IF MOD(last_digit, 2) = 0 THEN
            SET NEW.gender = 'Female';
        ELSE
            SET NEW.gender = 'Male';
        END IF;
    END IF;
END$$

-- ============================================================================
-- TRIGGER SET 9: AUTO-NOTIFICATION TRIGGERS
-- Purpose: Create notification records when events occur
-- ============================================================================

-- Trigger 13: Notify parent when student is absent
DROP TRIGGER IF EXISTS after_absence_marked$$
CREATE TRIGGER after_absence_marked
AFTER INSERT ON attendance_records
FOR EACH ROW
BEGIN
    DECLARE parent_id INT;
    DECLARE student_name VARCHAR(255);
    
    -- Only for absences
    IF NEW.status = 'Absent' THEN
        
        -- Get parent and student name
        SELECT parent_user_id, full_name INTO parent_id, student_name
        FROM students
        WHERE student_id = NEW.student_id;
        
        -- Create notification
        INSERT INTO notifications (
            recipient_user_id,
            student_id,
            notification_type,
            priority,
            subject,
            message,
            send_via,
            scheduled_for,
            created_by,
            status
        ) VALUES (
            parent_id,
            NEW.student_id,
            'Absence Alert',
            'High',
            CONCAT(student_name, ' was absent today'),
            CONCAT('Your child ', student_name, ' was marked absent on ', DATE_FORMAT(NEW.attendance_date, '%d/%m/%Y'), '. Please submit an absence form if applicable.'),
            'Both',
            DATE_ADD(NOW(), INTERVAL 5 MINUTE), -- Send in 5 minutes
            NEW.marked_by,
            'Scheduled'
        );
    END IF;
END$$

-- Trigger 14: Notify admin when new registration submitted
DROP TRIGGER IF EXISTS after_student_registered$$
CREATE TRIGGER after_student_registered
AFTER INSERT ON students
FOR EACH ROW
BEGIN
    DECLARE admin_id INT;
    
    -- Get first admin user
    SELECT user_id INTO admin_id
    FROM users
    WHERE role = 'Admin'
      AND status = 'Active'
    LIMIT 1;
    
    IF admin_id IS NOT NULL THEN
        INSERT INTO notifications (
            recipient_user_id,
            student_id,
            notification_type,
            priority,
            subject,
            message,
            send_via,
            scheduled_for,
            created_by,
            status
        ) VALUES (
            admin_id,
            NEW.student_id,
            'Registration',
            'Normal',
            'New student registration pending',
            CONCAT('New registration for ', NEW.full_name, ' requires your approval.'),
            'Email',
            NOW(),
            NEW.parent_user_id,
            'Scheduled'
        );
    END IF;
END$$

-- Trigger 15: Notify teacher when payment receipt uploaded
DROP TRIGGER IF EXISTS after_receipt_uploaded$$
CREATE TRIGGER after_receipt_uploaded
AFTER INSERT ON payment_transactions
FOR EACH ROW
BEGIN
    DECLARE teacher_id INT;
    DECLARE student_name VARCHAR(255);
    
    -- Get teacher from student's class
    SELECT c.teacher_id, s.full_name INTO teacher_id, student_name
    FROM student_ledgers sl
    JOIN students s ON sl.student_id = s.student_id
    JOIN classes c ON s.current_class_id = c.class_id
    WHERE sl.ledger_id = NEW.ledger_id;
    
    IF teacher_id IS NOT NULL THEN
        INSERT INTO notifications (
            recipient_user_id,
            notification_type,
            priority,
            subject,
            message,
            send_via,
            scheduled_for,
            created_by,
            status
        ) VALUES (
            teacher_id,
            'Receipt Uploaded',
            'Normal',
            'Payment receipt uploaded',
            CONCAT('A payment receipt for ', student_name, ' (RM', NEW.amount_paid, ') requires verification.'),
            'Email',
            NOW(),
            NEW.paid_by,
            'Scheduled'
        );
    END IF;
END$$

-- Trigger 16: Notify parent when grades are posted
DROP TRIGGER IF EXISTS after_grade_posted$$
CREATE TRIGGER after_grade_posted
AFTER INSERT ON student_grades
FOR EACH ROW
BEGIN
    DECLARE parent_id INT;
    DECLARE student_name VARCHAR(255);
    DECLARE subject_name VARCHAR(100);
    
    -- Get parent info
    SELECT s.parent_user_id, s.full_name, subj.subject_name
    INTO parent_id, student_name, subject_name
    FROM students s
    JOIN subjects subj ON subj.subject_id = NEW.subject_id
    WHERE s.student_id = NEW.student_id;
    
    INSERT INTO notifications (
        recipient_user_id,
        student_id,
        notification_type,
        priority,
        subject,
        message,
        send_via,
        scheduled_for,
        created_by,
        status
    ) VALUES (
        parent_id,
        NEW.student_id,
        'Grade Posted',
        'Normal',
        CONCAT('Grades available for ', student_name),
        CONCAT('Grades for ', subject_name, ' have been posted. Total score: ', NEW.total_score, '/100 (Grade: ', NEW.grade, ')'),
        'Email',
        DATE_ADD(NOW(), INTERVAL 10 MINUTE),
        NEW.recorded_by,
        'Scheduled'
    );
END$$

-- ============================================================================
-- TRIGGER SET 10: LOW STOCK ALERTS
-- Purpose: Notify admin when inventory falls below threshold
-- ============================================================================

-- Trigger 17: Alert when stock goes below minimum
DROP TRIGGER IF EXISTS after_stock_low$$
CREATE TRIGGER after_stock_low
AFTER UPDATE ON inventory_items
FOR EACH ROW
BEGIN
    DECLARE admin_id INT;
    
    -- Check if stock just dropped below minimum
    IF NEW.current_quantity < NEW.minimum_stock_level
       AND OLD.current_quantity >= OLD.minimum_stock_level THEN
        
        -- Get admin
        SELECT user_id INTO admin_id
        FROM users
        WHERE role = 'Admin' AND status = 'Active'
        LIMIT 1;
        
        IF admin_id IS NOT NULL THEN
            INSERT INTO notifications (
                recipient_user_id,
                notification_type,
                priority,
                subject,
                message,
                send_via,
                scheduled_for,
                created_by,
                status
            ) VALUES (
                admin_id,
                'Low Stock',
                'High',
                CONCAT('Low stock alert: ', NEW.item_name),
                CONCAT(NEW.item_name, ' stock is low (', NEW.current_quantity, ' ', NEW.unit_of_measure, '). Minimum level is ', NEW.minimum_stock_level, '. Please reorder.'),
                'Email',
                NOW(),
                admin_id,
                'Scheduled'
            );
        END IF;
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- TRIGGER SUMMARY (17 Triggers Created)
-- ============================================================================
-- 1. after_student_approved - Update class enrollment
-- 2. after_class_teacher_assigned - Track teacher class count
-- 3. after_payment_approved - Update ledger when payment approved
-- 4. after_payment_item_added - Update total_payable
-- 5. after_payment_item_updated - Adjust total_payable
-- 6. after_payment_item_deleted - Decrease total_payable
-- 7. after_material_downloaded - Increment download count
-- 8. after_stock_movement - Update inventory quantity
-- 9. after_student_approved_create_ledger - Auto-create payment ledger
-- 10. after_absence_approved - Change attendance to excused
-- 11. before_user_profile_insert - Extract DOB/gender from IC
-- 12. before_user_profile_update - Recalculate IC-derived fields
-- 13. after_absence_marked - Notify parent of absence
-- 14. after_student_registered - Notify admin of new registration
-- 15. after_receipt_uploaded - Notify teacher to verify payment
-- 16. after_grade_posted - Notify parent of new grades
-- 17. after_stock_low - Alert admin of low stock
-- ============================================================================