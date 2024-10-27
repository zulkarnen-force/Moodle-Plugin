<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
     'local_myapi_get_data' => [
          'classname' => 'local_myapi\\external\\your_class_name',
          'methodname' => 'get_data',
          'classpath' => 'local/myapi/classes/external/your_class_name.php',
          'description' => 'Returns some data from Moodle',
          'type' => 'read', // Change to 'write' for write operations
          'capabilities' => 'moodle/site:config' // Optional
     ],
     'local_get_students_enrolled_by_password' => [
          'classname' => 'local_myapi\\external\\your_class_name',
          'methodname' => 'get_students_enrolled_by_password',
          'classpath' => 'local/myapi/classes/external/your_class_name.php',
          'description' => 'Returns some data from Moodle',
          'type' => 'read', // Change to 'write' for write operations
          'capabilities' => 'moodle/site:config' // Optional
     ],
     'elearning_get_course_grade_categories' => [
          'classname' => 'local_myapi\\external\\GradeCategory',
          'methodname' => 'get_course_grade_categories',
          'classpath' => 'local/myapi/classes/external/GradeCategory.php',
          'description' => 'Return a list of grade categories in a course',
          'type' => 'read', // Change to 'write' for write operations
          'capabilities' => 'moodle/site:config' // Optional
     ],
     'elearning_uad_grade_student' => [
          'classname' => 'local_myapi\\external\\GradeStudent',
          'methodname' => 'get_student_grades',
          'classpath' => 'local/myapi/classes/external/GradeStudent.php',
          'description' => 'Return a list of grade categories',
          'type' => 'read', // Change to 'write' for write operations
          'capabilities' => 'moodle/site:config' // Optional
     ],
     'elearning_get_course_grades' => [
          'classname' => 'local_myapi\\external\\GradeReport',
          'methodname' => 'get_course_grades_with_users',
          'classpath' => 'local/myapi/classes/external/GradeReport.php',
          'description' => 'Return a list of grade in a course',
          'type' => 'read', // Change to 'write' for write operations
          'capabilities' => 'moodle/site:config' // Optional
     ],
];

$services = [
     'My API Service' => [
          'functions' => ['local_myapi_get_data'],
          'restrictedusers' => 0,
          'enabled' => 1,
     ],
     'Get Students Enrolled By Password' => [
          'functions' => ['local_get_students_enrolled_by_password'],
          'restrictedusers' => 0,
          'enabled' => 1,
     ],
     'Grade Categories Service' => [
          'functions' => ['elearning_get_course_grade_categories'],
          'restrictedusers' => 0,
          'enabled' => 1,
     ],
     'Grade Student Service' => [
          'functions' => ['elearning_uad_grade_student'],
          'restrictedusers' => 0,
          'enabled' => 1,
     ],
     'Grade Report Service' => [
          'functions' => ['elearning_get_course_grades'],
          'restrictedusers' => 0,
          'enabled' => 1,
     ],
];