-- ============================================================================
-- E-KAFA PIUMS Database Schema - COMPLETE FIXED VERSION
-- Version: 1.1 - ALL 43 TABLES INCLUDED
-- Date: October 2025
-- Key Fixes: CHECK constraints removed, GENERATED columns made compatible
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
-- SECTION 1: USER MANAGEMENT LAYER (Tables 1-4)
-- ============================================================================

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'User email address for login',
    password_hash VARCHAR(255) NOT NULL COMMENT 'Bcrypt hashed password',
    auth_key VARCHAR(32) NOT NULL COMMENT 'Authentication key for cookies',
    `role` ENUM('Admin', 'Teacher', 'Parent') NOT NULL COMMENT 'User role',
    `status` ENUM('Active', 'Inactive', 'Suspended') NOT NULL DEFAULT 'Active' COMMENT 'Account status',
    failed_login_attempts TINYINT NOT NULL DEFAULT 0 COMMENT 'Failed login counter',
    account_locked_until TIMESTAMP NULL COMMENT 'Account lock expiration time',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL COMMENT 'Last successful login timestamp',
    
    INDEX idx_users_email (email),
    INDEX idx_users_role_status (`role`, `status`),
    INDEX idx_users_auth_key (auth_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Core user authentication table';

DROP TABLE IF EXISTS user_profiles;
CREATE TABLE user_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE COMMENT 'References users table (1:1)',
    full_name VARCHAR(255) NOT NULL COMMENT 'User full name as per IC',
    ic_number VARCHAR(20) NOT NULL UNIQUE COMMENT 'Malaysian IC number (YYMMDD-PB-###G)',
    date_of_birth DATE NOT NULL COMMENT 'Extracted from IC number',
    gender ENUM('Male', 'Female') NOT NULL COMMENT 'Extracted from IC last digit',
    race VARCHAR(50) NOT NULL COMMENT 'Malay, Chinese, Indian, Others',
    phone_number VARCHAR(20) NOT NULL COMMENT 'Primary contact (+60xxxxxxxxx)',
    citizenship VARCHAR(100) NOT NULL DEFAULT 'Malaysian',
    marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed') NULL,
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL,
    postcode VARCHAR(10) NOT NULL,
    state VARCHAR(100) NOT NULL,
    profile_picture_url VARCHAR(500) NULL COMMENT 'Path to uploaded photo',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_profile_ic (ic_number),
    INDEX idx_profile_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User personal information';

DROP TABLE IF EXISTS employment_details;
CREATE TABLE employment_details (
    employment_id INT AUTO_INCREMENT PRIMARY KEY,
    profile_id INT NOT NULL COMMENT 'References user_profiles (1:1)',
    job_title VARCHAR(100) NOT NULL,
    employer_name VARCHAR(255) NOT NULL,
    employer_address TEXT NULL,
    employer_phone VARCHAR(20) NULL,
    monthly_gross_salary DECIMAL(10,2) NOT NULL COMMENT 'Salary before deductions (RM)',
    monthly_net_salary DECIMAL(10,2) NOT NULL COMMENT 'Salary after deductions (RM)',
    employment_type ENUM('Full-Time', 'Part-Time', 'Contract', 'Self-Employed') NOT NULL DEFAULT 'Full-Time',
    start_date DATE NULL,
    is_ums_staff BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'TRUE if employed by UMS',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (profile_id) REFERENCES user_profiles(profile_id) ON DELETE CASCADE,
    INDEX idx_employment_profile (profile_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Employment information';

DROP TABLE IF EXISTS family_relationships;
CREATE TABLE family_relationships (
    relationship_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Primary user (e.g., father)',
    related_user_id INT NOT NULL COMMENT 'Related user (e.g., mother)',
    relationship_type ENUM('Spouse', 'Guardian', 'Emergency Contact', 'Ex-Spouse') NOT NULL,
    is_primary_contact BOOLEAN NOT NULL DEFAULT FALSE,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (related_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_relationship (user_id, related_user_id, relationship_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Family relationships (self-referencing)';

-- ============================================================================
-- SECTION 2: STUDENT MANAGEMENT LAYER (Tables 5-10)
-- ============================================================================

DROP TABLE IF EXISTS students;
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    parent_user_id INT NOT NULL COMMENT 'Parent who registered student',
    current_class_id INT NULL COMMENT 'Currently assigned class',
    full_name VARCHAR(255) NOT NULL,
    ic_number VARCHAR(20) NULL UNIQUE COMMENT 'IC number (if student has IC)',
    birth_certificate_no VARCHAR(50) NOT NULL UNIQUE,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    race VARCHAR(50) NOT NULL,
    religion VARCHAR(50) NOT NULL DEFAULT 'Islam',
    profile_picture_url VARCHAR(500) NULL,
    primary_school_name VARCHAR(255) NOT NULL COMMENT 'Primary school student attends',
    registration_status ENUM('Pending', 'Approved', 'Rejected', 'Active', 'Graduated', 'Withdrawn') NOT NULL DEFAULT 'Pending',
    enrollment_year YEAR NOT NULL COMMENT 'Year of first enrollment (e.g., 2025)',
    current_year TINYINT NOT NULL COMMENT 'Current KAFA year level (1-6)',
    session_type ENUM('Morning', 'Evening') NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    approved_by INT NULL COMMENT 'Admin who approved',
    graduation_date DATE NULL,
    withdrawal_date DATE NULL,
    withdrawal_reason TEXT NULL,
    
    FOREIGN KEY (parent_user_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_students_parent (parent_user_id),
    INDEX idx_students_class (current_class_id),
    INDEX idx_students_status (registration_status),
    INDEX idx_students_year_session (current_year, session_type, registration_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Core student records';

DROP TABLE IF EXISTS student_health_info;
CREATE TABLE student_health_info (
    health_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL UNIQUE COMMENT 'References students (1:1)',
    height_cm DECIMAL(5,2) NULL COMMENT 'Height in centimeters',
    weight_kg DECIMAL(5,2) NULL COMMENT 'Weight in kilograms',
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NULL,
    allergies TEXT NULL COMMENT 'Known allergies (food, medicine, environmental)',
    chronic_conditions TEXT NULL COMMENT 'Asthma, diabetes, epilepsy, etc.',
    has_disability BOOLEAN NOT NULL DEFAULT FALSE,
    disability_details TEXT NULL,
    medications TEXT NULL COMMENT 'Regular medications',
    emergency_contact_name VARCHAR(255) NOT NULL,
    emergency_contact_phone VARCHAR(20) NOT NULL,
    emergency_contact_relationship VARCHAR(50) NOT NULL,
    doctor_name VARCHAR(255) NULL,
    doctor_phone VARCHAR(20) NULL,
    medical_conditions_notes TEXT NULL COMMENT 'Additional notes for teachers',
    last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    INDEX idx_health_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Student health information';

DROP TABLE IF EXISTS student_guardians;
CREATE TABLE student_guardians (
    guardian_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL COMMENT 'References students',
    guardian_name VARCHAR(255) NOT NULL,
    relationship VARCHAR(50) NOT NULL COMMENT 'Father, Mother, Grandparent, Uncle, etc.',
    phone_number VARCHAR(20) NOT NULL,
    ic_number VARCHAR(20) NULL,
    vehicle_plate_no VARCHAR(20) NULL COMMENT 'For pickup identification',
    is_authorized_pickup BOOLEAN NOT NULL DEFAULT TRUE,
    is_primary_guardian BOOLEAN NOT NULL DEFAULT FALSE,
    can_authorize_medical BOOLEAN NOT NULL DEFAULT FALSE,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    INDEX idx_guardians_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Multiple guardians per student';

DROP TABLE IF EXISTS class_enrollments;
CREATE TABLE class_enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    `status` ENUM('Active', 'Transferred', 'Completed', 'Withdrawn') NOT NULL DEFAULT 'Active',
    completion_date DATE NULL,
    final_grade VARCHAR(5) NULL COMMENT 'Overall grade (A/B/C/D/E)',
    final_percentage DECIMAL(5,2) NULL,
    attendance_rate DECIMAL(5,2) NULL,
    class_rank INT NULL COMMENT 'Student rank in class',
    remarks TEXT NULL,
    is_current BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_class (student_id, class_id),
    INDEX idx_enrollments_student (student_id, is_current),
    INDEX idx_enrollments_class (class_id, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Student class enrollment history';

DROP TABLE IF EXISTS teacher_profiles;
CREATE TABLE teacher_profiles (
    teacher_id INT PRIMARY KEY COMMENT 'References users.user_id (both PK and FK)',
    date_first_appointment DATE NOT NULL,
    teacher_status ENUM('Active', 'On Leave', 'Resigned', 'Terminated') NOT NULL DEFAULT 'Active',
    specialization VARCHAR(100) NULL COMMENT 'Teaching specialty (Tajwid, Hafazan, etc.)',
    can_teach_years VARCHAR(20) NOT NULL DEFAULT '1,2,3,4,5,6' COMMENT 'Comma-separated years',
    preferred_session ENUM('Morning', 'Evening', 'Both') NULL,
    max_classes TINYINT NOT NULL DEFAULT 1,
    total_classes_assigned TINYINT NOT NULL DEFAULT 0,
    performance_rating DECIMAL(3,2) NULL COMMENT 'Average rating (1.00-5.00)',
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (teacher_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_teacher_status (teacher_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Teacher-specific information';

DROP TABLE IF EXISTS teacher_qualifications;
CREATE TABLE teacher_qualifications (
    qualification_id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    qualification_type ENUM('SPM', 'STPM', 'Diploma', 'Degree', 'Master', 'PhD', 'Certificate') NOT NULL,
    institution_name VARCHAR(255) NOT NULL,
    course_name VARCHAR(255) NULL,
    field_of_study VARCHAR(100) NULL,
    result VARCHAR(50) NULL COMMENT 'Grade/CGPA (e.g., "CGPA 3.75", "5A 3B")',
    start_date DATE NULL,
    graduation_date DATE NOT NULL,
    certificate_url VARCHAR(500) NULL COMMENT 'Path to certificate',
    is_verified BOOLEAN NOT NULL DEFAULT FALSE,
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (teacher_id) REFERENCES teacher_profiles(teacher_id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_qualifications_teacher (teacher_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Teacher educational qualifications';

-- ============================================================================
-- CONTINUE TO PART 2 FOR REMAINING TABLES...
-- Note: Due to character limit, see next artifact for Tables 11-43
-- ============================================================================

-- ============================================================================
-- E-KAFA PIUMS Database Schema - PART 2/2 (Tables 11-43 + Triggers + Data)
-- CONTINUE FROM PART 1
-- ============================================================================

-- ============================================================================
-- SECTION 3: ACADEMIC STRUCTURE LAYER (Tables 11-13)
-- ============================================================================

DROP TABLE IF EXISTS academic_sessions;
CREATE TABLE academic_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    session_year VARCHAR(20) NOT NULL UNIQUE COMMENT 'e.g., "2024/2025"',
    session_type ENUM('Mid Semester', 'Final Semester') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_current BOOLEAN NOT NULL DEFAULT FALSE,
    registration_open_date DATE NOT NULL,
    registration_close_date DATE NOT NULL,
    `status` ENUM('Upcoming', 'Active', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Upcoming',
    created_by INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    UNIQUE KEY unique_session (session_year, session_type),
    INDEX idx_session_current (is_current),
    INDEX idx_session_status (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Academic sessions/semesters';

DROP TABLE IF EXISTS subjects;
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(20) NOT NULL UNIQUE COMMENT 'Short code (e.g., QUR, TAJ, AQD)',
    subject_name VARCHAR(100) NOT NULL COMMENT 'Full name (e.g., Al-Quran, Tajwid)',
    year_level TINYINT NULL COMMENT 'Specific year (1-6) or NULL if all years',
    description TEXT NULL,
    is_core BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'TRUE if mandatory',
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_subject_active (is_active),
    INDEX idx_subject_year (year_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='KAFA subjects';

DROP TABLE IF EXISTS classes;
CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    teacher_id INT NULL COMMENT 'Assigned teacher',
    class_name VARCHAR(100) NOT NULL COMMENT 'e.g., Al-Burj, Al-Fatih',
    year_level TINYINT NOT NULL COMMENT 'KAFA year (1-6)',
    session_type ENUM('Morning', 'Evening') NOT NULL,
    max_capacity TINYINT NOT NULL DEFAULT 30,
    current_enrollment TINYINT NOT NULL DEFAULT 0 COMMENT 'Auto-updated by trigger',
    `status` ENUM('Open', 'Full', 'Closed', 'Cancelled') NOT NULL DEFAULT 'Open',
    classroom_location VARCHAR(100) NULL,
    schedule_days VARCHAR(50) NULL COMMENT 'e.g., "Mon,Wed,Fri"',
    start_time TIME NULL,
    end_time TIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (session_id) REFERENCES academic_sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teacher_profiles(teacher_id) ON DELETE SET NULL,
    INDEX idx_classes_session_year (session_id, year_level, `status`),
    INDEX idx_classes_teacher (teacher_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='KAFA classes';

-- Add foreign keys for circular dependencies
ALTER TABLE students ADD FOREIGN KEY (current_class_id) REFERENCES classes(class_id) ON DELETE SET NULL;
ALTER TABLE class_enrollments ADD FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE;

-- ============================================================================
-- SECTION 4: ACADEMIC OPERATIONS LAYER (Tables 14-21)
-- ============================================================================

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
    UNIQUE KEY unique_student_attendance (student_id, attendance_date),
    INDEX idx_attendance_student_date (student_id, attendance_date),
    INDEX idx_attendance_class_date (class_id, attendance_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Daily attendance records';

DROP TABLE IF EXISTS absence_forms;
CREATE TABLE absence_forms (
    form_id INT AUTO_INCREMENT PRIMARY KEY,
    attendance_id INT NOT NULL UNIQUE COMMENT 'Links to absence record (1:1)',
    student_id INT NOT NULL,
    submitted_by INT NOT NULL COMMENT 'Parent who submitted',
    form_title VARCHAR(255) NOT NULL,
    absence_reason TEXT NOT NULL,
    absence_date DATE NOT NULL,
    supporting_document_url VARCHAR(500) NULL,
    `status` ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    reviewed_by INT NULL,
    reviewed_at TIMESTAMP NULL,
    review_notes TEXT NULL,
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (attendance_id) REFERENCES attendance_records(attendance_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (submitted_by) REFERENCES users(user_id),
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id),
    INDEX idx_absence_student (student_id),
    INDEX idx_absence_status (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Absence excuse forms';

DROP TABLE IF EXISTS attendance_summary;
CREATE TABLE attendance_summary (
    summary_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    `year_month` VARCHAR(7) NOT NULL COMMENT 'Format: YYYY-MM',
    total_school_days TINYINT NOT NULL,
    present_count TINYINT NOT NULL DEFAULT 0,
    absent_count TINYINT NOT NULL DEFAULT 0,
    excused_count TINYINT NOT NULL DEFAULT 0,
    late_count TINYINT NOT NULL DEFAULT 0,
    attendance_rate DECIMAL(5,2) NULL,
    computed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_month (student_id, `year_month`),
    INDEX idx_summary_month (`year_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pre-computed monthly attendance stats';

DROP TABLE IF EXISTS learning_materials;
CREATE TABLE learning_materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    uploaded_by INT NOT NULL COMMENT 'Teacher who uploaded',
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    material_type ENUM('PDF', 'Video', 'Image', 'Link', 'Audio') NOT NULL,
    file_url VARCHAR(500) NULL,
    file_size INT NULL COMMENT 'File size in bytes',
    year_level TINYINT NOT NULL COMMENT 'Target year (1-6)',
    tags JSON NULL COMMENT 'Array of tags for categorization',
    view_count INT NOT NULL DEFAULT 0,
    download_count INT NOT NULL DEFAULT 0,
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    published_at TIMESTAMP NULL,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES teacher_profiles(teacher_id),
    INDEX idx_materials_subject_year (subject_id, year_level, is_published),
    INDEX idx_materials_teacher (uploaded_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Learning materials uploaded by teachers';

DROP TABLE IF EXISTS material_access_log;
CREATE TABLE material_access_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    user_id INT NOT NULL COMMENT 'Parent who accessed',
    student_id INT NULL COMMENT 'Specific student (if parent has multiple)',
    access_type ENUM('View', 'Download', 'Print') NOT NULL,
    accessed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    duration_seconds INT NULL,
    device_type VARCHAR(50) NULL COMMENT 'Desktop, Mobile, Tablet',
    
    FOREIGN KEY (material_id) REFERENCES learning_materials(material_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    INDEX idx_access_material (material_id),
    INDEX idx_access_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Material access analytics';

DROP TABLE IF EXISTS assessment_types;
CREATE TABLE assessment_types (
    assessment_type_id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(100) NOT NULL UNIQUE COMMENT 'e.g., Mid Semester Exam',
    weightage_percent DECIMAL(5,2) NOT NULL,
    max_score DECIMAL(5,2) NOT NULL,
    has_parts BOOLEAN NOT NULL DEFAULT FALSE,
    part_a_max DECIMAL(5,2) NULL,
    part_b_max DECIMAL(5,2) NULL,
    description TEXT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Types of assessments';

DROP TABLE IF EXISTS student_assessments;
CREATE TABLE student_assessments (
    assessment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    session_id INT NOT NULL,
    assessment_type_id INT NOT NULL,
    score_part_a DECIMAL(5,2) NULL,
    score_part_b DECIMAL(5,2) NULL,
    total_score DECIMAL(5,2) NULL,
    percentage DECIMAL(5,2) NULL,
    grade VARCHAR(5) NULL COMMENT 'A/B/C/D/E',
    remarks TEXT NULL,
    recorded_by INT NOT NULL,
    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_absent BOOLEAN NOT NULL DEFAULT FALSE,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (session_id) REFERENCES academic_sessions(session_id),
    FOREIGN KEY (assessment_type_id) REFERENCES assessment_types(assessment_type_id),
    FOREIGN KEY (recorded_by) REFERENCES teacher_profiles(teacher_id),
    UNIQUE KEY unique_assessment (student_id, subject_id, session_id, assessment_type_id),
    INDEX idx_assessments_student_session (student_id, session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Student assessment scores';

DROP TABLE IF EXISTS student_report_cards;
CREATE TABLE student_report_cards (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_id INT NOT NULL,
    class_id INT NOT NULL,
    total_subjects TINYINT NOT NULL,
    total_marks DECIMAL(6,2) NOT NULL,
    average_score DECIMAL(5,2) NOT NULL,
    overall_grade VARCHAR(5) NULL,
    class_rank INT NULL,
    total_students_in_class TINYINT NULL,
    attendance_rate DECIMAL(5,2) NULL,
    teacher_remarks TEXT NULL,
    principal_remarks TEXT NULL,
    generated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    pdf_url VARCHAR(500) NULL,
    is_finalized BOOLEAN NOT NULL DEFAULT FALSE,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES academic_sessions(session_id),
    FOREIGN KEY (class_id) REFERENCES classes(class_id),
    UNIQUE KEY unique_report (student_id, session_id),
    INDEX idx_report_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pre-computed report cards';

-- ============================================================================
-- SECTION 5: FINANCIAL LAYER (Tables 22-26)
-- ============================================================================

DROP TABLE IF EXISTS payment_categories;
CREATE TABLE payment_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    default_amount DECIMAL(10,2) NOT NULL,
    is_recurring BOOLEAN NOT NULL DEFAULT FALSE,
    billing_frequency ENUM('Once', 'Monthly', 'Semester', 'Yearly') NULL,
    is_mandatory BOOLEAN NOT NULL DEFAULT TRUE,
    applies_to_year TINYINT NULL COMMENT 'Specific year (1-6) or NULL if all',
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payment category master data';

DROP TABLE IF EXISTS student_ledgers;
CREATE TABLE student_ledgers (
    ledger_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_id INT NOT NULL,
    total_payable DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_paid DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    balance DECIMAL(10,2) NULL,
    last_payment_date DATE NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES academic_sessions(session_id),
    UNIQUE KEY unique_ledger (student_id, session_id),
    INDEX idx_ledger_student (student_id),
    INDEX idx_ledger_balance (balance)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Student payment ledgers per session';

DROP TABLE IF EXISTS payment_items;
CREATE TABLE payment_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    ledger_id INT NOT NULL,
    category_id INT NOT NULL,
    item_description VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    due_date DATE NULL,
    is_paid BOOLEAN NOT NULL DEFAULT FALSE,
    added_by INT NOT NULL COMMENT 'Teacher/Admin who added item',
    added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (ledger_id) REFERENCES student_ledgers(ledger_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES payment_categories(category_id),
    FOREIGN KEY (added_by) REFERENCES users(user_id),
    INDEX idx_payment_items_ledger (ledger_id),
    INDEX idx_payment_items_due (due_date, is_paid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Individual payment items in ledger';

DROP TABLE IF EXISTS payment_transactions;
CREATE TABLE payment_transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    ledger_id INT NOT NULL,
    paid_by INT NOT NULL COMMENT 'Parent who made payment',
    payment_method ENUM('e-Payment UMS', 'Cash', 'Bank Transfer', 'Cheque', 'Online Banking') NOT NULL,
    transaction_reference VARCHAR(100) NULL COMMENT 'e-Payment reference number',
    amount_paid DECIMAL(10,2) NOT NULL,
    receipt_url VARCHAR(500) NULL COMMENT 'Uploaded receipt image',
    payment_date DATE NOT NULL,
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    verification_status ENUM('Pending', 'Verified', 'Rejected') NOT NULL DEFAULT 'Pending',
    verified_by INT NULL COMMENT 'Teacher who verified',
    verified_at TIMESTAMP NULL,
    verification_notes TEXT NULL,
    
    approval_status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    approved_by INT NULL COMMENT 'Admin who approved',
    approved_at TIMESTAMP NULL,
    approval_notes TEXT NULL,
    
    FOREIGN KEY (ledger_id) REFERENCES student_ledgers(ledger_id) ON DELETE CASCADE,
    FOREIGN KEY (paid_by) REFERENCES users(user_id),
    FOREIGN KEY (verified_by) REFERENCES users(user_id),
    FOREIGN KEY (approved_by) REFERENCES users(user_id),
    INDEX idx_transactions_ledger (ledger_id),
    INDEX idx_transactions_status (verification_status, approval_status),
    INDEX idx_transactions_date (payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payment transactions with approval workflow';

DROP TABLE IF EXISTS payment_allocations;
CREATE TABLE payment_allocations (
    allocation_id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    item_id INT NOT NULL,
    allocated_amount DECIMAL(10,2) NOT NULL,
    allocated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (transaction_id) REFERENCES payment_transactions(transaction_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES payment_items(item_id) ON DELETE CASCADE,
    INDEX idx_allocations_transaction (transaction_id),
    INDEX idx_allocations_item (item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Links transactions to specific payment items';

-- ============================================================================
-- SECTION 6: ACTIVITY MANAGEMENT LAYER (Tables 27-29)
-- ============================================================================

DROP TABLE IF EXISTS activities;
CREATE TABLE activities (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    created_by INT NOT NULL COMMENT 'Teacher who created activity',
    activity_name VARCHAR(255) NOT NULL,
    activity_type ENUM('Field Trip', 'Competition', 'Celebration', 'Workshop', 'Sports Day', 'Exam', 'Other') NOT NULL,
    description TEXT NULL,
    activity_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    location VARCHAR(255) NULL,
    max_participants INT NULL,
    current_participants INT NOT NULL DEFAULT 0,
    target_year_levels VARCHAR(20) NULL COMMENT 'Comma-separated years (e.g., "1,2,3")',
    registration_open_date DATE NULL,
    registration_close_date DATE NULL,
    requires_payment BOOLEAN NOT NULL DEFAULT FALSE,
    payment_amount DECIMAL(10,2) NULL,
    `status` ENUM('Draft', 'Open for Registration', 'Registration Closed', 'Ongoing', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Draft',
    cancellation_reason TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES teacher_profiles(teacher_id),
    INDEX idx_activities_date (activity_date),
    INDEX idx_activities_status (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='KAFA activities and events';

DROP TABLE IF EXISTS activity_participants;
CREATE TABLE activity_participants (
    participant_id INT AUTO_INCREMENT PRIMARY KEY,
    activity_id INT NOT NULL,
    student_id INT NOT NULL,
    registered_by INT NOT NULL COMMENT 'Parent who registered',
    registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    attendance_status ENUM('Registered', 'Attended', 'Absent', 'Cancelled') NOT NULL DEFAULT 'Registered',
    attendance_marked_by INT NULL,
    attendance_marked_at TIMESTAMP NULL,
    payment_status ENUM('Not Required', 'Pending', 'Paid') NOT NULL DEFAULT 'Not Required',
    payment_transaction_id INT NULL,
    remarks TEXT NULL,
    
    FOREIGN KEY (activity_id) REFERENCES activities(activity_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (registered_by) REFERENCES users(user_id),
    FOREIGN KEY (attendance_marked_by) REFERENCES teacher_profiles(teacher_id),
    FOREIGN KEY (payment_transaction_id) REFERENCES payment_transactions(transaction_id),
    UNIQUE KEY unique_activity_student (activity_id, student_id),
    INDEX idx_participants_activity (activity_id),
    INDEX idx_participants_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Activity participation records';

DROP TABLE IF EXISTS activity_photos;
CREATE TABLE activity_photos (
    photo_id INT AUTO_INCREMENT PRIMARY KEY,
    activity_id INT NOT NULL,
    uploaded_by INT NOT NULL COMMENT 'Teacher who uploaded',
    photo_url VARCHAR(500) NOT NULL,
    caption TEXT NULL,
    photo_order INT NOT NULL DEFAULT 0,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (activity_id) REFERENCES activities(activity_id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES teacher_profiles(teacher_id),
    INDEX idx_photos_activity (activity_id, photo_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Activity photo gallery';

-- ============================================================================
-- SECTION 7: INVENTORY MANAGEMENT LAYER (Tables 30-32)
-- ============================================================================

DROP TABLE IF EXISTS inventory_categories;
CREATE TABLE inventory_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    requires_tracking BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Track individual items vs bulk',
    is_consumable BOOLEAN NOT NULL DEFAULT FALSE,
    reorder_threshold INT NULL COMMENT 'Min quantity before reorder',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Inventory categories';

DROP TABLE IF EXISTS inventory_items;
CREATE TABLE inventory_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    item_code VARCHAR(50) NOT NULL UNIQUE COMMENT 'SKU or barcode',
    description TEXT NULL,
    unit_of_measure VARCHAR(20) NOT NULL COMMENT 'pcs, box, kg, etc.',
    current_quantity INT NOT NULL DEFAULT 0,
    minimum_stock_level INT NOT NULL DEFAULT 0,
    maximum_stock_level INT NULL,
    unit_price DECIMAL(10,2) NULL,
    storage_location VARCHAR(100) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES inventory_categories(category_id),
    INDEX idx_items_category (category_id),
    INDEX idx_items_code (item_code),
    INDEX idx_items_stock_level (current_quantity, minimum_stock_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Inventory items master';

DROP TABLE IF EXISTS stock_movements;
CREATE TABLE stock_movements (
    movement_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    movement_type ENUM('Purchase', 'Issue', 'Return', 'Adjustment', 'Write-Off', 'Transfer') NOT NULL,
    quantity INT NOT NULL COMMENT 'Positive for IN, negative for OUT',
    previous_quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NULL,
    total_value DECIMAL(10,2) NULL,
    reference_no VARCHAR(100) NULL COMMENT 'PO number, invoice, etc.',
    issued_to_student_id INT NULL COMMENT 'If issued to specific student',
    reason TEXT NULL,
    recorded_by INT NOT NULL COMMENT 'User who recorded movement',
    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    
    FOREIGN KEY (item_id) REFERENCES inventory_items(item_id) ON DELETE CASCADE,
    FOREIGN KEY (issued_to_student_id) REFERENCES students(student_id),
    FOREIGN KEY (recorded_by) REFERENCES users(user_id),
    FOREIGN KEY (approved_by) REFERENCES users(user_id),
    INDEX idx_movements_item (item_id),
    INDEX idx_movements_date (recorded_at),
    INDEX idx_movements_type (movement_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='All stock movements history';

-- ============================================================================
-- SECTION 8: NOTIFICATION LAYER (Tables 33-36)
-- ============================================================================

DROP TABLE IF EXISTS notification_templates;
CREATE TABLE notification_templates (
    template_id INT AUTO_INCREMENT PRIMARY KEY,
    template_name VARCHAR(100) NOT NULL UNIQUE,
    trigger_event VARCHAR(100) NOT NULL COMMENT 'payment_due, absence_alert, etc.',
    subject_template TEXT NOT NULL COMMENT 'Email subject with placeholders',
    body_template TEXT NOT NULL COMMENT 'Body with {{placeholder}} variables',
    whatsapp_template TEXT NULL,
    variables JSON NULL COMMENT 'Available placeholders: ["student_name", "amount"]',
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_templates_event (trigger_event)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notification templates';

DROP TABLE IF EXISTS notifications;
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NULL COMMENT 'NULL if custom notification',
    recipient_user_id INT NOT NULL,
    student_id INT NULL COMMENT 'Related student if applicable',
    notification_type ENUM('Payment Reminder', 'Absence Alert', 'Activity Reminder', 'Registration', 'Announcement', 'Grade Posted', 'Custom') NOT NULL,
    priority ENUM('Low', 'Normal', 'High', 'Urgent') NOT NULL DEFAULT 'Normal',
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    scheduled_for TIMESTAMP NOT NULL COMMENT 'When to send',
    `status` ENUM('Scheduled', 'Sending', 'Sent', 'Failed', 'Cancelled') NOT NULL DEFAULT 'Scheduled',
    created_by INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (template_id) REFERENCES notification_templates(template_id),
    FOREIGN KEY (recipient_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    INDEX idx_notifications_recipient (recipient_user_id, `status`),
    INDEX idx_notifications_scheduled (scheduled_for, `status`),
    INDEX idx_notifications_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notification queue';

DROP TABLE IF EXISTS notification_deliveries;
CREATE TABLE notification_deliveries (
    delivery_id INT AUTO_INCREMENT PRIMARY KEY,
    notification_id INT NOT NULL,
    channel ENUM('Email', 'WhatsApp', 'In-App') NOT NULL,
    recipient_address VARCHAR(255) NOT NULL COMMENT 'Email or phone number',
    delivery_status ENUM('Pending', 'Delivered', 'Failed', 'Bounced') NOT NULL DEFAULT 'Pending',
    sent_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    opened_at TIMESTAMP NULL,
    clicked_at TIMESTAMP NULL,
    error_message TEXT NULL,
    retry_count TINYINT NOT NULL DEFAULT 0,
    
    FOREIGN KEY (notification_id) REFERENCES notifications(notification_id) ON DELETE CASCADE,
    INDEX idx_deliveries_notification (notification_id),
    INDEX idx_deliveries_status (delivery_status),
    INDEX idx_deliveries_channel (channel, delivery_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Multi-channel delivery tracking';

DROP TABLE IF EXISTS user_notification_preferences;
CREATE TABLE user_notification_preferences (
    preference_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    notification_type VARCHAR(100) NOT NULL,
    email_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    whatsapp_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    in_app_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    frequency ENUM('Immediate', 'Daily Digest', 'Weekly Digest') NOT NULL DEFAULT 'Immediate',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_type (user_id, notification_type),
    INDEX idx_preferences_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User notification preferences';

-- ============================================================================
-- SECTION 9: DOCUMENT MANAGEMENT LAYER (Table 37)
-- ============================================================================

DROP TABLE IF EXISTS documents;
CREATE TABLE documents (
    document_id INT AUTO_INCREMENT PRIMARY KEY,
    uploaded_by INT NOT NULL,
    related_entity_type ENUM('User', 'Student', 'Teacher', 'Payment', 'Activity', 'Attendance', 'Other') NOT NULL,
    related_entity_id INT NOT NULL COMMENT 'ID of related entity',
    document_type ENUM('IC Copy', 'Birth Certificate', 'Photo', 'Medical Report', 'Receipt', 'Certificate', 'Form', 'Other') NOT NULL,
    document_name VARCHAR(255) NOT NULL,
    file_url VARCHAR(500) NOT NULL,
    file_size INT NOT NULL COMMENT 'Size in bytes',
    file_extension VARCHAR(10) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    description TEXT NULL,
    is_verified BOOLEAN NOT NULL DEFAULT FALSE,
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (uploaded_by) REFERENCES users(user_id),
    FOREIGN KEY (verified_by) REFERENCES users(user_id),
    INDEX idx_documents_entity (related_entity_type, related_entity_id),
    INDEX idx_documents_uploader (uploaded_by),
    INDEX idx_documents_type (document_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Centralized document storage';

-- ============================================================================
-- SECTION 10: RBAC LAYER (Tables 38-41)
-- ============================================================================

DROP TABLE IF EXISTS auth_item;
CREATE TABLE auth_item (
    name VARCHAR(64) NOT NULL PRIMARY KEY,
    `type` TINYINT NOT NULL COMMENT '1=Role, 2=Permission',
    description TEXT,
    rule_name VARCHAR(64),
    `data` BLOB,
    created_at INT(11),
    updated_at INT(11),
    
    INDEX idx_type (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RBAC items (roles and permissions)';

DROP TABLE IF EXISTS auth_item_child;
CREATE TABLE auth_item_child (
    parent VARCHAR(64) NOT NULL,
    child VARCHAR(64) NOT NULL,
    PRIMARY KEY (parent, child),
    
    FOREIGN KEY (parent) REFERENCES auth_item(name) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (child) REFERENCES auth_item(name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RBAC hierarchy';

DROP TABLE IF EXISTS auth_assignment;
CREATE TABLE auth_assignment (
    item_name VARCHAR(64) NOT NULL,
    user_id INT NOT NULL,
    created_at INT(11),
    PRIMARY KEY (item_name, user_id),
    
    FOREIGN KEY (item_name) REFERENCES auth_item(name) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_auth_assignment_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User role assignments';

DROP TABLE IF EXISTS auth_rule;
CREATE TABLE auth_rule (
    name VARCHAR(64) NOT NULL PRIMARY KEY,
    `data` BLOB,
    created_at INT(11),
    updated_at INT(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RBAC rules';

-- ============================================================================
-- SECTION 11: AUDIT LOG (Table 42)
-- ============================================================================

-- Table 42: audit_logs
DROP TABLE IF EXISTS audit_logs;
CREATE TABLE audit_logs (
    log_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL COMMENT 'User who performed action',
    action VARCHAR(100) NOT NULL COMMENT 'CREATE, UPDATE, DELETE, LOGIN, etc.',
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
    INDEX idx_audit_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit trail for all actions';

-- ============================================================================
-- SECTION 12: SYSTEM SETTINGS (Table 43)
-- ============================================================================

DROP TABLE IF EXISTS system_settings;
CREATE TABLE system_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    setting_type ENUM('String', 'Integer', 'Boolean', 'JSON') NOT NULL DEFAULT 'String',
    description TEXT NULL,
    is_public BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Can be accessed by non-admins',
    updated_by INT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (updated_by) REFERENCES users(user_id),
    INDEX idx_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='System configuration';

-- ============================================================================
-- ALL 43 TABLES CREATED! Now adding TRIGGERS...
-- ============================================================================

-- ============================================================================
-- E-KAFA PIUMS Database Schema - PART 3/3
-- TRIGGERS, STORED PROCEDURES, VIEWS, AND SEED DATA
-- Run this AFTER Part 1 and Part 2
-- ============================================================================

-- ============================================================================
-- SECTION 13: TRIGGERS FOR AUTOMATION
-- ============================================================================

DELIMITER $$

DROP TRIGGER IF EXISTS after_enrollment_insert$$
CREATE TRIGGER after_enrollment_insert
AFTER INSERT ON class_enrollments
FOR EACH ROW
BEGIN
    IF NEW.status = 'Active' THEN
        UPDATE classes 
        SET current_enrollment = current_enrollment + 1
        WHERE class_id = NEW.class_id;
        
        UPDATE classes
        SET `status` = 'Full'
        WHERE class_id = NEW.class_id 
        AND current_enrollment >= max_capacity
        AND `status` = 'Open';
    END IF;
END$$

DROP TRIGGER IF EXISTS after_enrollment_update$$
CREATE TRIGGER after_enrollment_update
AFTER UPDATE ON class_enrollments
FOR EACH ROW
BEGIN
    IF OLD.status = 'Active' AND NEW.status IN ('Withdrawn', 'Transferred', 'Completed') THEN
        UPDATE classes 
        SET current_enrollment = current_enrollment - 1
        WHERE class_id = OLD.class_id;
        
        UPDATE classes
        SET `status` = 'Open'
        WHERE class_id = OLD.class_id 
        AND current_enrollment < max_capacity
        AND `status` = 'Full';
    END IF;
END$$

DROP TRIGGER IF EXISTS after_payment_approved$$
CREATE TRIGGER after_payment_approved
AFTER UPDATE ON payment_transactions
FOR EACH ROW
BEGIN
    IF OLD.approval_status != 'Approved' AND NEW.approval_status = 'Approved' THEN
        UPDATE student_ledgers
        SET total_paid = total_paid + NEW.amount_paid,
            last_payment_date = NEW.payment_date,
            balance = total_payable - (total_paid + NEW.amount_paid)
        WHERE ledger_id = NEW.ledger_id;
    END IF;
END$$

DROP TRIGGER IF EXISTS after_activity_registration$$
CREATE TRIGGER after_activity_registration
AFTER INSERT ON activity_participants
FOR EACH ROW
BEGIN
    UPDATE activities
    SET current_participants = current_participants + 1
    WHERE activity_id = NEW.activity_id;
    
    UPDATE activities
    SET `status` = 'Registration Closed'
    WHERE activity_id = NEW.activity_id
    AND current_participants >= max_participants
    AND `status` = 'Open for Registration';
END$$

DROP TRIGGER IF EXISTS after_stock_movement$$
CREATE TRIGGER after_stock_movement
AFTER INSERT ON stock_movements
FOR EACH ROW
BEGIN
    UPDATE inventory_items
    SET current_quantity = NEW.new_quantity
    WHERE item_id = NEW.item_id;
END$$

DROP TRIGGER IF EXISTS before_student_assessment_insert$$
CREATE TRIGGER before_student_assessment_insert
BEFORE INSERT ON student_assessments
FOR EACH ROW
BEGIN
    IF NEW.score_part_a IS NOT NULL OR NEW.score_part_b IS NOT NULL THEN
        SET NEW.total_score = COALESCE(NEW.score_part_a, 0) + COALESCE(NEW.score_part_b, 0);
    END IF;
END$$

DROP TRIGGER IF EXISTS before_student_assessment_update$$
CREATE TRIGGER before_student_assessment_update
BEFORE UPDATE ON student_assessments
FOR EACH ROW
BEGIN
    IF NEW.score_part_a IS NOT NULL OR NEW.score_part_b IS NOT NULL THEN
        SET NEW.total_score = COALESCE(NEW.score_part_a, 0) + COALESCE(NEW.score_part_b, 0);
    END IF;
END$$

DROP TRIGGER IF EXISTS before_student_ledger_update$$
CREATE TRIGGER before_student_ledger_update
BEFORE UPDATE ON student_ledgers
FOR EACH ROW
BEGIN
    SET NEW.balance = NEW.total_payable - NEW.total_paid;
END$$

DROP TRIGGER IF EXISTS after_attendance_summary_insert$$
CREATE TRIGGER after_attendance_summary_insert
BEFORE INSERT ON attendance_summary
FOR EACH ROW
BEGIN
    IF NEW.total_school_days > 0 THEN
        SET NEW.attendance_rate = (NEW.present_count / NEW.total_school_days) * 100;
    END IF;
END$$

DROP TRIGGER IF EXISTS after_attendance_summary_update$$
CREATE TRIGGER after_attendance_summary_update
BEFORE UPDATE ON attendance_summary
FOR EACH ROW
BEGIN
    IF NEW.total_school_days > 0 THEN
        SET NEW.attendance_rate = (NEW.present_count / NEW.total_school_days) * 100;
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- SECTION 14: STORED PROCEDURES
-- ============================================================================

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_auto_register_students$$
CREATE PROCEDURE sp_auto_register_students(IN p_session_id INT)
BEGIN
    DECLARE v_affected_rows INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error: Auto-registration failed!' AS status, 0 AS students_registered;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    INSERT INTO students (
        parent_user_id, full_name, ic_number, birth_certificate_no, date_of_birth,
        gender, race, religion, primary_school_name, registration_status,
        enrollment_year, current_year, session_type, approved_at, approved_by
    )
    SELECT 
        s.parent_user_id,
        s.full_name,
        s.ic_number,
        s.birth_certificate_no,
        s.date_of_birth,
        s.gender,
        s.race,
        s.religion,
        s.primary_school_name,
        'Approved',
        s.enrollment_year,
        s.current_year + 1,
        s.session_type,
        NOW(),
        1
    FROM students s
    WHERE s.registration_status = 'Active'
    AND s.current_year < 6
    AND NOT EXISTS (
        SELECT 1 FROM students s2
        WHERE s2.parent_user_id = s.parent_user_id
        AND s2.birth_certificate_no = s.birth_certificate_no
        AND s2.current_year = s.current_year + 1
        AND s2.registration_status IN ('Pending', 'Approved', 'Active')
    );
    
    SET v_affected_rows = ROW_COUNT();
    
    COMMIT;
    
    SELECT 
        'Success' AS status,
        v_affected_rows AS students_registered,
        CONCAT(v_affected_rows, ' students auto-registered for new session') AS message;
END$$

DROP PROCEDURE IF EXISTS sp_calculate_report_card$$
CREATE PROCEDURE sp_calculate_report_card(IN p_student_id INT, IN p_session_id INT)
BEGIN
    DECLARE v_total_marks DECIMAL(6,2);
    DECLARE v_total_subjects INT;
    DECLARE v_average DECIMAL(5,2);
    DECLARE v_overall_grade VARCHAR(5);
    DECLARE v_class_id INT;
    DECLARE v_attendance_rate DECIMAL(5,2);
    DECLARE v_class_rank INT;
    DECLARE v_total_students INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error' AS status, 'Report card calculation failed!' AS message;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    SELECT current_class_id INTO v_class_id 
    FROM students 
    WHERE student_id = p_student_id;
    
    IF v_class_id IS NULL THEN
        SELECT 'Error' AS status, 'Student not assigned to any class' AS message;
        ROLLBACK;
    ELSE
        SELECT 
            COUNT(DISTINCT subject_id),
            SUM(percentage),
            AVG(percentage)
        INTO v_total_subjects, v_total_marks, v_average
        FROM student_assessments
        WHERE student_id = p_student_id
        AND session_id = p_session_id
        AND is_absent = FALSE;
        
        SELECT COALESCE(AVG(attendance_rate), 0)
        INTO v_attendance_rate
        FROM attendance_summary
        WHERE student_id = p_student_id;
        
        SET v_overall_grade = CASE 
            WHEN v_average >= 80 THEN 'A'
            WHEN v_average >= 60 THEN 'B'
            WHEN v_average >= 50 THEN 'C'
            WHEN v_average >= 40 THEN 'D'
            ELSE 'E'
        END;
        
        SET v_class_rank = (
            SELECT COUNT(*) + 1
            FROM student_report_cards src
            JOIN students s ON src.student_id = s.student_id
            WHERE s.current_class_id = v_class_id
            AND src.session_id = p_session_id
            AND src.average_score > v_average
        );
        
        SELECT COUNT(*)
        INTO v_total_students
        FROM students
        WHERE current_class_id = v_class_id
        AND registration_status = 'Active';
        
        INSERT INTO student_report_cards (
            student_id, session_id, class_id, total_subjects,
            total_marks, average_score, overall_grade, class_rank,
            total_students_in_class, attendance_rate, is_finalized
        ) VALUES (
            p_student_id, p_session_id, v_class_id, v_total_subjects,
            v_total_marks, v_average, v_overall_grade, v_class_rank,
            v_total_students, v_attendance_rate, FALSE
        )
        ON DUPLICATE KEY UPDATE
            total_subjects = v_total_subjects,
            total_marks = v_total_marks,
            average_score = v_average,
            overall_grade = v_overall_grade,
            class_rank = v_class_rank,
            total_students_in_class = v_total_students,
            attendance_rate = v_attendance_rate,
            generated_at = NOW();
        
        COMMIT;
        
        SELECT 
            'Success' AS `status`,
            p_student_id AS student_id,
            v_overall_grade AS grade,
            v_average AS percentage,
            v_class_rank AS `rank`,
            CONCAT('Report card generated - Grade: ', v_overall_grade, 
                   ' (', ROUND(v_average, 2), '%), Rank: ', v_class_rank, 
                   '/', v_total_students) AS message;
    END IF;
END$$

DROP PROCEDURE IF EXISTS sp_send_payment_reminders$$
CREATE PROCEDURE sp_send_payment_reminders()
BEGIN
    DECLARE v_reminder_count INT DEFAULT 0;
    DECLARE v_reminder_days INT;
    DECLARE v_template_id INT;
    
    SELECT CAST(setting_value AS SIGNED) INTO v_reminder_days
    FROM system_settings 
    WHERE setting_key = 'payment_reminder_days';
    
    SET v_reminder_days = COALESCE(v_reminder_days, 7);
    
    SELECT template_id INTO v_template_id
    FROM notification_templates 
    WHERE template_name = 'Payment Reminder' 
    LIMIT 1;
    
    INSERT INTO notifications (
        template_id, recipient_user_id, student_id, notification_type,
        priority, subject, message, scheduled_for, created_by
    )
    SELECT 
        v_template_id,
        s.parent_user_id,
        s.student_id,
        'Payment Reminder',
        CASE 
            WHEN pi.due_date < CURDATE() THEN 'High'
            WHEN pi.due_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) THEN 'Normal'
            ELSE 'Low'
        END,
        CONCAT('Payment Reminder for ', s.full_name),
        CONCAT('Dear Parent,\n\nThis is a reminder for payment of RM ', 
               FORMAT(pi.amount, 2), ' for ', pi.item_description, 
               '.\n\nDue date: ', DATE_FORMAT(pi.due_date, '%d/%m/%Y'),
               '\nOutstanding balance: RM ', FORMAT(sl.balance, 2),
               '\n\nPlease make payment via UMS e-Payment portal.',
               '\n\nThank you.\nPusat Islam UMS'),
        NOW(),
        1
    FROM students s
    JOIN student_ledgers sl ON s.student_id = sl.student_id
    JOIN payment_items pi ON sl.ledger_id = pi.ledger_id
    WHERE sl.balance > 0
    AND s.registration_status = 'Active'
    AND pi.is_paid = FALSE
    AND pi.due_date <= DATE_ADD(CURDATE(), INTERVAL v_reminder_days DAY)
    AND NOT EXISTS (
        SELECT 1 FROM notifications n
        WHERE n.recipient_user_id = s.parent_user_id
        AND n.student_id = s.student_id
        AND n.notification_type = 'Payment Reminder'
        AND DATE(n.created_at) = CURDATE()
    )
    GROUP BY s.student_id, pi.item_id;
    
    SET v_reminder_count = ROW_COUNT();
    
    SELECT 
        'Success' AS status,
        v_reminder_count AS reminders_created,
        CONCAT(v_reminder_count, ' payment reminder(s) created') AS message;
END$$

DELIMITER ;

-- ============================================================================
-- SECTION 15: VIEWS FOR REPORTING
-- ============================================================================

DROP VIEW IF EXISTS view_student_dashboard;
CREATE VIEW view_student_dashboard AS
SELECT 
    s.student_id,
    s.full_name,
    s.current_year,
    s.session_type,
    s.registration_status,
    c.class_id,
    c.class_name,
    CONCAT(up.full_name) AS teacher_name,
    sl.balance AS outstanding_balance,
    sl.total_payable,
    sl.total_paid,
    COALESCE(att_sum.attendance_rate, 0) AS current_attendance_rate,
    COALESCE(att_sum.present_count, 0) AS days_present,
    COALESCE(att_sum.absent_count, 0) AS days_absent,
    COUNT(DISTINCT atp.activity_id) AS registered_activities,
    p_user.email AS parent_email,
    p_profile.phone_number AS parent_phone
FROM students s
LEFT JOIN classes c ON s.current_class_id = c.class_id
LEFT JOIN teacher_profiles tp ON c.teacher_id = tp.teacher_id
LEFT JOIN user_profiles up ON tp.teacher_id = up.user_id
LEFT JOIN student_ledgers sl ON s.student_id = sl.student_id
LEFT JOIN attendance_summary att_sum ON s.student_id = att_sum.student_id 
    AND att_sum.year_month = DATE_FORMAT(CURDATE(), '%Y-%m')
LEFT JOIN activity_participants atp ON s.student_id = atp.student_id 
    AND atp.attendance_status = 'Registered'
LEFT JOIN users p_user ON s.parent_user_id = p_user.user_id
LEFT JOIN user_profiles p_profile ON s.parent_user_id = p_profile.user_id
WHERE s.registration_status = 'Active'
GROUP BY s.student_id;

DROP VIEW IF EXISTS view_teacher_class_summary;
CREATE VIEW view_teacher_class_summary AS
SELECT 
    t.teacher_id,
    up.full_name AS teacher_name,
    up.phone_number AS teacher_phone,
    c.class_id,
    c.class_name,
    c.year_level,
    c.session_type,
    c.current_enrollment,
    c.max_capacity,
    ROUND((c.current_enrollment / c.max_capacity) * 100, 2) AS occupancy_rate,
    c.`status` AS class_status,
    COUNT(DISTINCT att.attendance_date) AS days_taught,
    COUNT(DISTINCT CASE WHEN att.`status` = 'Present' THEN att.student_id END) AS total_present,
    COUNT(DISTINCT CASE WHEN att.`status` = 'Absent' THEN att.student_id END) AS total_absent,
    ROUND(AVG(CASE WHEN att.`status` = 'Present' THEN 100 ELSE 0 END), 2) AS avg_attendance_rate,
    asess.session_year
FROM teacher_profiles t
JOIN user_profiles up ON t.teacher_id = up.user_id
LEFT JOIN classes c ON t.teacher_id = c.teacher_id
LEFT JOIN academic_sessions asess ON c.session_id = asess.session_id
LEFT JOIN attendance_records att ON c.class_id = att.class_id
WHERE t.teacher_status = 'Active'
AND (c.`status` = 'Open' OR c.`status` = 'Full')
GROUP BY t.teacher_id, c.class_id;

DROP VIEW IF EXISTS view_payment_summary;
CREATE VIEW view_payment_summary AS
SELECT 
    asess.session_id,
    asess.session_year,
    asess.session_type,
    COUNT(DISTINCT sl.student_id) AS total_students,
    SUM(sl.total_payable) AS total_payable,
    SUM(sl.total_paid) AS total_collected,
    SUM(sl.balance) AS total_outstanding,
    ROUND((SUM(sl.total_paid) / NULLIF(SUM(sl.total_payable), 0)) * 100, 2) AS collection_rate,
    COUNT(DISTINCT CASE WHEN sl.balance = 0 THEN sl.student_id END) AS fully_paid_students,
    COUNT(DISTINCT CASE WHEN sl.balance > 0 THEN sl.student_id END) AS students_with_balance,
    AVG(sl.balance) AS avg_outstanding_per_student
FROM student_ledgers sl
JOIN academic_sessions asess ON sl.session_id = asess.session_id
GROUP BY asess.session_id;

DROP VIEW IF EXISTS view_stock_alerts;
CREATE VIEW view_stock_alerts AS
SELECT 
    i.item_id,
    i.item_name,
    i.item_code,
    ic.category_name,
    i.current_quantity,
    i.minimum_stock_level,
    i.maximum_stock_level,
    (i.minimum_stock_level - i.current_quantity) AS shortage,
    CASE 
        WHEN i.current_quantity = 0 THEN 'OUT OF STOCK'
        WHEN i.current_quantity < (i.minimum_stock_level * 0.5) THEN 'CRITICAL'
        WHEN i.current_quantity <= i.minimum_stock_level THEN 'LOW'
        ELSE 'OK'
    END AS stock_status,
    i.unit_price,
    (i.minimum_stock_level - i.current_quantity) * i.unit_price AS estimated_reorder_cost,
    i.storage_location,
    i.unit_of_measure
FROM inventory_items i
JOIN inventory_categories ic ON i.category_id = ic.category_id
WHERE i.current_quantity <= i.minimum_stock_level
AND i.is_active = TRUE
ORDER BY 
    CASE 
        WHEN i.current_quantity = 0 THEN 1
        WHEN i.current_quantity < (i.minimum_stock_level * 0.5) THEN 2
        ELSE 3
    END,
    (i.minimum_stock_level - i.current_quantity) DESC;

DROP VIEW IF EXISTS view_attendance_problems;
CREATE VIEW view_attendance_problems AS
SELECT 
    s.student_id,
    s.full_name AS student_name,
    s.current_year,
    c.class_name,
    up.full_name AS parent_name,
    u.email AS parent_email,
    up.phone_number AS parent_phone,
    att_sum.year_month,
    att_sum.attendance_rate,
    att_sum.total_school_days,
    att_sum.present_count,
    att_sum.absent_count,
    att_sum.excused_count,
    att_sum.late_count,
    CASE 
        WHEN att_sum.attendance_rate < 60 THEN 'CRITICAL'
        WHEN att_sum.attendance_rate < 75 THEN 'WARNING'
        WHEN att_sum.attendance_rate < 80 THEN 'NEEDS ATTENTION'
        ELSE 'OK'
    END AS attendance_status,
    (SELECT COUNT(*) 
     FROM absence_forms af 
     WHERE af.student_id = s.student_id 
     AND DATE_FORMAT(af.absence_date, '%Y-%m') = att_sum.year_month
     AND af.`status` = 'Pending') AS pending_absence_forms
FROM attendance_summary att_sum
JOIN students s ON att_sum.student_id = s.student_id
JOIN classes c ON s.current_class_id = c.class_id
JOIN users u ON s.parent_user_id = u.user_id
JOIN user_profiles up ON u.user_id = up.user_id
WHERE att_sum.attendance_rate < 80
AND att_sum.year_month = DATE_FORMAT(CURDATE(), '%Y-%m')
AND s.registration_status = 'Active'
ORDER BY att_sum.attendance_rate ASC, att_sum.absent_count DESC;

-- ============================================================================
-- SECTION 16: SEED DATA (CRITICAL - MUST HAVE!)
-- ============================================================================

-- Default admin user
INSERT INTO users (email, password_hash, auth_key, `role`, `status`) VALUES
('admin@piums.ums.edu.my', '$2y$13$rQdQ3eYzKZvKXMp.N7hIveLhQ9gJfW5P4nZ3XZw9kQeY5TxHqN/im', 
 MD5(CONCAT('admin', NOW())), 'Admin', 'Active');

SET @admin_user_id = LAST_INSERT_ID();

INSERT INTO user_profiles (user_id, full_name, ic_number, date_of_birth, gender, race, phone_number, 
    address_line_1, city, postcode, state) VALUES
(@admin_user_id, 'System Administrator', '900101-12-1234', '1990-01-01', 'Male', 'Malay', 
 '+60123456789', 'Pusat Islam UMS', 'Kota Kinabalu', '88400', 'Sabah');

-- Default teacher user
INSERT INTO users (email, password_hash, auth_key, `role`, `status`) VALUES
('teacher@piums.ums.edu.my', '$2y$13$rQdQ3eYzKZvKXMp.N7hIveLhQ9gJfW5P4nZ3XZw9kQeY5TxHqN/im', 
 MD5(CONCAT('teacher', NOW())), 'Teacher', 'Active');

SET @teacher_user_id = LAST_INSERT_ID();

INSERT INTO user_profiles (user_id, full_name, ic_number, date_of_birth, gender, race, phone_number,
    address_line_1, city, postcode, state) VALUES
(@teacher_user_id, 'Ustaz Ahmad Bin Abdullah', '850505-12-5678', '1985-05-05', 'Male', 'Malay',
 '+60129876543', 'Jalan Kolej', 'Kota Kinabalu', '88400', 'Sabah');

INSERT INTO teacher_profiles (teacher_id, date_first_appointment, teacher_status, specialization) VALUES
(@teacher_user_id, '2020-01-01', 'Active', 'Tajwid & Hafazan');

-- Default parent user
INSERT INTO users (email, password_hash, auth_key, `role`, `status`) VALUES
('parent@example.com', '$2y$13$rQdQ3eYzKZvKXMp.N7hIveLhQ9gJfW5P4nZ3XZw9kQeY5TxHqN/im',
 MD5(CONCAT('parent', NOW())), 'Parent', 'Active');

SET @parent_user_id = LAST_INSERT_ID();

INSERT INTO user_profiles (user_id, full_name, ic_number, date_of_birth, gender, race, phone_number,
    address_line_1, city, postcode, state) VALUES
(@parent_user_id, 'Muhammad Bin Hassan', '880808-12-9012', '1988-08-08', 'Male', 'Malay',
 '+60135551234', 'Taman Indah', 'Kota Kinabalu', '88300', 'Sabah');

-- KAFA Subjects
INSERT INTO subjects (subject_code, subject_name, year_level, is_core, description) VALUES
('QUR', 'Al-Quran', NULL, TRUE, 'Quranic recitation and memorization'),
('TAJ', 'Tajwid', NULL, TRUE, 'Rules of Quranic pronunciation'),
('JWI', 'Jawi', NULL, TRUE, 'Jawi reading and writing'),
('AQD', 'Aqidah', NULL, TRUE, 'Islamic creed and beliefs'),
('IBD', 'Ibadah', NULL, TRUE, 'Islamic worship and practices'),
('SIR', 'Sirah', NULL, TRUE, 'Biography of Prophet Muhammad (PBUH)'),
('AKH', 'Akhlak', NULL, TRUE, 'Islamic morals and ethics'),
('ADB', 'Adab', NULL, TRUE, 'Islamic etiquette and manners');

-- Payment Categories
INSERT INTO payment_categories (category_name, description, default_amount, is_recurring, billing_frequency, is_mandatory) VALUES
('Registration Fee', 'One-time registration fee for new students', 50.00, FALSE, 'Once', TRUE),
('Tuition Fee', 'Semester tuition fee', 200.00, TRUE, 'Semester', TRUE),
('Textbook Fee', 'Cost of textbooks and learning materials', 150.00, TRUE, 'Yearly', TRUE),
('Uniform Fee', 'School uniform (2 sets)', 80.00, FALSE, 'Once', FALSE),
('Activity Fee', 'Field trips and activities', 50.00, TRUE, 'Semester', FALSE),
('Exam Fee', 'Assessment and exam materials', 30.00, TRUE, 'Semester', TRUE);

-- Assessment Types
INSERT INTO assessment_types (type_name, weightage_percent, max_score, has_parts, part_a_max, part_b_max, description) VALUES
('Mid Semester Exam', 40.00, 100.00, TRUE, 60.00, 40.00, 'Mid-semester examination'),
('Final Semester Exam', 60.00, 100.00, TRUE, 60.00, 40.00, 'Final semester examination'),
('Assignment', 20.00, 100.00, FALSE, NULL, NULL, 'Homework and take-home assignments'),
('Quiz', 10.00, 100.00, FALSE, NULL, NULL, 'Short quizzes and tests');

-- System Settings (COMPLETE LIST)
INSERT INTO system_settings (setting_key, setting_value, setting_type, description, is_public) VALUES
('system_name', 'E-KAFA PIUMS', 'String', 'System name displayed in interface', TRUE),
('institution_name', 'Pusat Islam Universiti Malaysia Sabah', 'String', 'Full institution name', TRUE),
('contact_email', 'piums@ums.edu.my', 'String', 'Main contact email address', TRUE),
('contact_phone', '+6088-320000', 'String', 'Main contact phone number', TRUE),
('max_students_per_class', '30', 'Integer', 'Default maximum class capacity', FALSE),
('payment_reminder_days', '7', 'Integer', 'Days before due date to send payment reminder', FALSE),
('min_attendance_rate', '80', 'Integer', 'Minimum required attendance rate (%)', FALSE),
('passing_grade', '50', 'Integer', 'Minimum passing grade percentage', FALSE),
('maintenance_mode', 'false', 'Boolean', 'System maintenance mode', FALSE);

-- Academic Session
INSERT INTO academic_sessions (session_year, session_type, start_date, end_date, is_current, 
    registration_open_date, registration_close_date, `status`, created_by) VALUES
('2024/2025', 'Mid Semester', '2024-01-15', '2024-06-30', TRUE,
 '2023-12-01', '2024-01-10', 'Active', @admin_user_id);

SET @current_session_id = LAST_INSERT_ID();

-- Sample Classes
INSERT INTO classes (session_id, teacher_id, class_name, year_level, session_type, max_capacity, 
    classroom_location, schedule_days, start_time, end_time, `status`) VALUES
(@current_session_id, @teacher_user_id, 'Al-Burj', 1, 'Morning', 30, 'Room A1', 'Mon,Wed,Fri', '08:00:00', '10:00:00', 'Open'),
(@current_session_id, NULL, 'Al-Fatih', 2, 'Morning', 30, 'Room A2', 'Mon,Wed,Fri', '08:00:00', '10:00:00', 'Open');

-- RBAC Setup
INSERT INTO auth_item (name, `type`, description, created_at, updated_at) VALUES
('admin', 1, 'Administrator with full system access', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('teacher', 1, 'Teacher with classroom management access', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('parent', 1, 'Parent with view and submission access', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO auth_assignment (item_name, user_id, created_at) VALUES
('admin', @admin_user_id, UNIX_TIMESTAMP()),
('teacher', @teacher_user_id, UNIX_TIMESTAMP()),
('parent', @parent_user_id, UNIX_TIMESTAMP());

-- ============================================================================
-- FINALIZE
-- ============================================================================

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
SET AUTOCOMMIT = 1;

SELECT 'Installation complete! Database ready for use.' AS final_status;
SELECT 'Total Tables Created: 43' AS info;
SELECT 'Total Triggers Created: 9' AS info;
SELECT 'Total Stored Procedures Created: 3' AS info;
SELECT 'Total Views Created: 5' AS info;