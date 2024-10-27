<?php
namespace local_myapi\external;

use local_myapi\utils\CourseUtil;
use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_value;
use external_api;


class SelfEnrol extends external_api
{
     public static function get_selfenrol_user_in_course_parameters()
     {
          return new external_function_parameters([
               'courseid' => new external_value(PARAM_INT, 'Course ID'),
               'password' => new external_value(PARAM_TEXT, 'Self Enrol Password')
          ]);
     }

     public static function get_selfenrol_user_in_course($courseid, $password)
     {
          global $DB;
          $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
          $selfenrol = $DB->get_record('enrol', ['courseid' => $courseid, 'enrol' => 'self', 'password' => $password], '*', MUST_EXIST);
          $sql = "SELECT 
                    u.id,
                    u.username,
                    CONCAT(u.firstname, ' ', u.lastname) AS userfullname,
                    u.email,
                    e.name as enrol_name,
                    e.password as enrol_password
               FROM {user} u
               JOIN {user_enrolments} ue ON ue.userid = u.id
               JOIN {enrol} e ON e.id = ue.enrolid
               WHERE e.courseid = :courseid
               AND e.enrol = 'self'
               AND e.password = :pwd";
          $users = $DB->get_records_sql($sql, ['courseid' => $courseid, 'pwd' => $password]);
          $result = [
               'course' => [
                    'id' => $course->id,
                    'fullname' => $course->fullname,
                    'shortname' => $course->shortname,
                    'category' => $course->category // You can include other course fields as needed
               ],
               'selfenrol' => [
                    'name' => $selfenrol->name,
                    'password' => $selfenrol->password
               ],
               'users' => []
          ];
          foreach ($users as $user) {
               $result['users'][] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'userfullname' => $user->userfullname,
                    'email' => $user->email,
               ];
          }
          return $result;
     }

     public static function get_selfenrol_user_in_course_returns()
     {
          return new external_single_structure([
               'course' => new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'Course ID'),
                    'fullname' => new external_value(PARAM_TEXT, 'Full course name'),
                    'shortname' => new external_value(PARAM_TEXT, 'Short course name'),
                    'category' => new external_value(PARAM_INT, 'Category ID')
               ]),
               'selfenrol' => new external_single_structure([
                    'name' => new external_value(PARAM_TEXT, 'Self Enrol name'),
                    'password' => new external_value(PARAM_TEXT, 'Self Enrol password'),
               ]),
               'users' => new external_multiple_structure(
                    new external_single_structure([
                         'id' => new external_value(PARAM_INT, 'User ID'),
                         'username' => new external_value(PARAM_TEXT, 'User name'),
                         'userfullname' => new external_value(PARAM_TEXT, 'User full name'),
                         'email' => new external_value(PARAM_TEXT, 'User email'),
                    ])
               )
          ]);
     }
}