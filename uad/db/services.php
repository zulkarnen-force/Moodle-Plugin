<?php
defined("MOODLE_INTERNAL") || die();

$functions = [
    "local_get_students_enrolled_by_password" => [
        "classname" => 'uad_api\\external\\your_class_name',
        "methodname" => "get_students_enrolled_by_password",
        "classpath" => "local/uad/classes/external/your_class_name.php",
        "description" => "Returns some data from Moodle",
        "type" => "read", // Change to 'write' for write operations
        "capabilities" => "moodle/site:config", // Optional
    ],
    "uad_get_gradecategories_course" => [
        "classname" => 'uad_api\\external\\GradeCategory',
        "methodname" => "get_course_grade_categories",
        "classpath" => "local/uad/classes/external/GradeCategory.php",
        "description" => "Return a list of grade categories in a course",
        "type" => "read", // Change to 'write' for write operations
        "capabilities" => "moodle/site:config", // Optional
    ],
    "elearning_uad_grade_student" => [
        "classname" => 'uad_api\\external\\GradeStudent',
        "methodname" => "get_student_grades",
        "classpath" => "local/uad/classes/external/GradeStudent.php",
        "description" => "Return a list of grade categories",
        "type" => "read", // Change to 'write' for write operations
        "capabilities" => "moodle/site:config", // Optional
    ],
    "uad_get_gradereport" => [
        "classname" => 'uad_api\\external\\GradeReport',
        "methodname" => "get_course_grades_with_users",
        "classpath" => "local/uad/classes/external/GradeReport.php",
        "description" => "Return a list of grade in a course",
        "type" => "read", // Change to 'write' for write operations
        "capabilities" => "moodle/site:config", // Optional
    ],
    "uad_get_gradereport_selfenrol" => [
        "classname" => 'uad_api\\external\\GradeReport',
        "methodname" => "get_selfenroll_grades_with_password",
        "classpath" => "local/uad/classes/external/GradeReport.php",
        "description" =>
            "Return a list of grade in a course with self enrol password",
        "type" => "read", // Change to 'write' for write operations
        "capabilities" => "moodle/site:config", // Optional
    ],
    "uad_get_selfenrol" => [
        "classname" => 'uad_api\\external\\SelfEnrol',
        "methodname" => "get_selfenrol_user_in_course",
        "classpath" => "local/uad/classes/external/SelfEnrol.php",
        "description" => "Return a list of self enrol users in a course",
        "type" => "read", // Change to 'write' for write operations
        "capabilities" => "moodle/site:config", // Optional
    ],
    // Add the new function for self-enrollment creation
    "uad_create_selfenrol" => [
        "classname" => 'uad_api\\external\\SelfEnrol',
        "methodname" => "create_selfenrol_user_in_course",
        "classpath" => "local/uad/classes/external/SelfEnrol.php",
        "description" => "Enroll a user in a course by self-enrolment",
        "type" => "write", // Set to 'write' since this is a write operation
    ],
];

$services = [
    "Get Students Enrolled By Password" => [
        "functions" => ["local_get_students_enrolled_by_password"],
        "restrictedusers" => 0,
        "enabled" => 1,
    ],
    "Grade Categories Service" => [
        "functions" => ["uad_get_gradecategories_course"],
        "restrictedusers" => 0,
        "enabled" => 1,
    ],
    "Grade Student Service" => [
        "functions" => ["elearning_uad_grade_student"],
        "restrictedusers" => 0,
        "enabled" => 1,
    ],
    "Grade Report Service" => [
        "functions" => ["uad_get_gradereport", "uad_get_gradereport_selfenrol"],
        "restrictedusers" => 0,
        "enabled" => 1,
    ],
    "Student Self Enrol Service" => [
        "functions" => ["uad_get_selfenrol", "uad_create_selfenrol"],
        "restrictedusers" => 0,
        "enabled" => 1,
    ],
];
