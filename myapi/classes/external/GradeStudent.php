<?php
namespace local_myapi\external;

use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_value;
use external_api;

class GradeStudent extends external_api
{
    public static function get_student_grades_parameters()
    {
        return new external_function_parameters([
            "courseid" => new external_value(PARAM_INT, "Course ID"),
        ]);
    }

    public static function get_student_grades($courseid)
    {
        global $DB;

        $sql = "SELECT u.id AS student_id, u.firstname, u.lastname, gi.itemname AS grade_item,
                       gc.fullname AS category_name, g.finalgrade
                FROM {user} u
                JOIN {user_enrolments} ue ON ue.userid = u.id
                JOIN {enrol} e ON e.id = ue.enrolid
                JOIN {grade_grades} g ON g.userid = u.id
                JOIN {grade_items} gi ON gi.id = g.itemid
                LEFT JOIN {grade_categories} gc ON gc.id = gi.categoryid
                WHERE e.courseid = :courseid";
        $grades = $DB->get_records_sql($sql, ["courseid" => $courseid]);

        $result = [];
        foreach ($grades as $grade) {
            $result[] = [
                "student_id" => $grade->student_id,
                "firstname" => $grade->firstname,
                "lastname" => $grade->lastname,
                "grade_item" => $grade->grade_item,
                "category_name" => $grade->category_name,
                "finalgrade" => $grade->finalgrade,
            ];
        }

        return $result;
    }

    public static function get_student_grades_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                "student_id" => new external_value(PARAM_INT, "Student ID"),
                "firstname" => new external_value(
                    PARAM_TEXT,
                    "First name of student"
                ),
                "lastname" => new external_value(
                    PARAM_TEXT,
                    "Last name of student"
                ),
                "grade_item" => new external_value(
                    PARAM_TEXT,
                    "Name of the grade item"
                ),
                "category_name" => new external_value(
                    PARAM_TEXT,
                    "Name of the grade category"
                ),
                "finalgrade" => new external_value(PARAM_FLOAT, "Final grade"),
            ])
        );
    }
}
