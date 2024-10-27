<?php
namespace local_myapi\external;

use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_value;
use external_api;

class GradeReport extends external_api
{

     public static function get_course_grades_with_users_parameters()
     {
          return new external_function_parameters([
               'courseid' => new external_value(PARAM_INT, 'Course ID to retrieve grades for'),
          ]);
     }

     public static function get_course_grades_with_users($courseid)
     {
          global $DB;

          $params = self::validate_parameters(self::get_course_grades_with_users_parameters(), [
               'courseid' => $courseid
          ]);

          if (!$DB->record_exists('course', ['id' => $params['courseid']])) {
               throw new \moodle_exception('invalidcourseid', 'error');
          }

          $sql = "
              
        SELECT 
                   gg.id AS gg_id, 
                   u.id AS userid, 
                   CONCAT(u.firstname, ' ', u.lastname) AS userfullname,
                   u.idnumber AS useridnumber,
                   gi.id AS grade_item_id,
                   gi.itemname AS grade_item_name,
                   gi.itemtype AS item_type,
                   gi.itemmodule AS item_module,
                   gi.iteminstance AS item_instance,
                   gi.itemnumber AS item_number,
                   gi.idnumber AS grade_item_idnumber,
                   gi.categoryid AS category_id,
                   gc.fullname AS category_name,
                   gg.rawgrade AS raw_grade,
                   gi.grademin AS grade_min,
                   gi.grademax AS grade_max,
                   gg.finalgrade AS final_grade,
                   gg.timemodified AS grade_modified,
                   gg.locked AS grade_locked,
                   gg.hidden AS grade_hidden
        FROM {grade_items} gi
        JOIN {grade_grades} gg ON gg.itemid = gi.id
        JOIN {user} u ON u.id = gg.userid
        JOIN {grade_categories} gc ON gc.id = gi.categoryid
        WHERE gi.courseid = :courseid;
        ";

          $records = $DB->get_records_sql($sql, ['courseid' => $courseid]);
          if ($DB->get_last_error()) {
               error_log('SQL Error: ' . $DB->get_last_error());
          }
          $result = [];
          foreach ($records as $record) {
               if (!isset($result[$record->userid])) {
                    $result[$record->userid] = [
                         'userid' => $record->userid,
                         'userfullname' => $record->userfullname,
                         'useridnumber' => $record->useridnumber,
                         'gradeitems' => []
                    ];
               }

               $result[$record->userid]['gradeitems'][] = [
                    'id' => $record->grade_item_id,
                    'itemname' => $record->grade_item_name,
                    'itemtype' => $record->item_type,
                    'itemmodule' => $record->item_module,
                    'iteminstance' => $record->item_instance,
                    'itemnumber' => $record->item_number,
                    'idnumber' => $record->grade_item_idnumber,
                    'categoryid' => $record->category_id,
                    'categoryname' => $record->category_name,
                    'graderaw' => $record->raw_grade,
                    'grademin' => $record->grade_min,
                    'grademax' => $record->grade_max,
                    'finalgrade' => $record->final_grade,
                    'timemodified' => date('Y-m-d H:i:s', $record->grade_modified),
                    'grade_locked' => $record->grade_locked,
                    'grade_hidden' => $record->grade_hidden,
               ];
          }
          return array_values($result);
     }

     public static function get_course_grades_with_users_returns()
     {
          return new external_multiple_structure(
               new external_single_structure([
                    'userid' => new external_value(PARAM_INT, 'User ID'),
                    'userfullname' => new external_value(PARAM_TEXT, 'Full name of the user'),
                    'useridnumber' => new external_value(PARAM_TEXT, 'User ID number', VALUE_OPTIONAL),
                    'gradeitems' => new external_multiple_structure(
                         new external_single_structure([
                              'id' => new external_value(PARAM_INT, 'Grade item ID'),
                              'itemname' => new external_value(PARAM_TEXT, 'Name of the grade item', VALUE_OPTIONAL),
                              'itemtype' => new external_value(PARAM_TEXT, 'Type of the grade item (e.g., mod, category, course)'),
                              'itemmodule' => new external_value(PARAM_TEXT, 'Module type if applicable', VALUE_OPTIONAL),
                              'iteminstance' => new external_value(PARAM_INT, 'Instance ID related to the grade item', VALUE_OPTIONAL),
                              'itemnumber' => new external_value(PARAM_INT, 'Item number of the grade', VALUE_OPTIONAL),
                              'idnumber' => new external_value(PARAM_TEXT, 'ID number of the grade item', VALUE_OPTIONAL),
                              'categoryid' => new external_value(PARAM_INT, 'Category ID if applicable', VALUE_OPTIONAL),
                              'categoryname' => new external_value(PARAM_TEXT, 'Name of the category', VALUE_OPTIONAL),
                              'graderaw' => new external_value(PARAM_FLOAT, 'Raw grade for the item', VALUE_OPTIONAL),
                              'grademin' => new external_value(PARAM_FLOAT, 'Minimum grade for the item'),
                              'grademax' => new external_value(PARAM_FLOAT, 'Maximum grade for the item'),
                              'finalgrade' => new external_value(PARAM_FLOAT, 'Final calculated grade for the item', VALUE_OPTIONAL),
                              'timemodified' => new external_value(PARAM_TEXT, 'Timestamp of when the grade was last modified', VALUE_OPTIONAL),
                              'grade_locked' => new external_value(PARAM_BOOL, 'Grade item for user locked', VALUE_OPTIONAL),
                              'grade_hidden' => new external_value(PARAM_BOOL, 'Grade is hidden', VALUE_OPTIONAL),
                         ])
                    ),
               ])
          );
     }
}