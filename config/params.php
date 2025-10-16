<?php
return [
    'adminEmail' => 'admin@piums.ums.edu.my',
    'senderEmail' => 'noreply@piums.ums.edu.my',
    'senderName' => 'E-KAFA PIUMS',
    
    // Add system settings matching your database
    'systemName' => 'E-KAFA PIUMS',
    'institutionName' => 'Pusat Islam Universiti Malaysia Sabah',
    'contactPhone' => '+6088-320000',
    'contactEmail' => 'piums@ums.edu.my',
    
    // Pagination
    'pageSize' => 20,
    
    // File upload limits
    'maxFileSize' => 5 * 1024 * 1024, // 5MB
    'allowedFileTypes' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
    
    // Payment settings
    'paymentReminderDays' => 7,
    'minAttendanceRate' => 80,
    'passingGrade' => 50,
];