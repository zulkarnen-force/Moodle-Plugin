<?php
namespace local_myapi\utils;

class CourseUtil
{

     public static function get_course($courseid)
     {
          global $DB;
          return $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
     }
}
