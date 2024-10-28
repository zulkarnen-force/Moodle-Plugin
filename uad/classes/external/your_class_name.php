<?php
namespace uad_api\external;

use external_function_parameters;
use external_single_structure;
use external_multiple_structure; // Add this line
use external_value;
use external_api;

class your_class_name extends external_api
{
    public static function get_data_parameters()
    {
        return new external_function_parameters([
            "param" => new external_value(PARAM_TEXT, "Example parameter"),
        ]);
    }

    public static function get_data($param)
    {
        global $DB;

        $params = self::validate_parameters(self::get_data_parameters(), [
            "param" => $param,
        ]);

        $data = $DB->get_records_sql(
            "SELECT * FROM {table_name} WHERE field = ?",
            [$param]
        );

        return ["data" => array_values($data)];
    }

    public static function get_data_returns()
    {
        return new external_single_structure([
            "data" => new external_multiple_structure( // Use external_multiple_structure here
                new external_single_structure([
                    "fieldname" => new external_value(
                        PARAM_TEXT,
                        "Field description"
                    ),
                ])
            ),
        ]);
    }

    // Existing get_grade_categories function
    public static function get_grade_categories_parameters()
    {
        return new external_function_parameters([
            "courseid" => new external_value(PARAM_INT, "Course ID"),
        ]);
    }

    public static function get_students_enrolled_by_password_parameters()
    {
        return new external_function_parameters([
            "courseid" => new external_value(PARAM_TEXT, "Some course ID"),
        ]);
    }
    public static function get_students_enrolled_by_password($courseid)
    {
        global $DB;

        $params = self::validate_parameters(
            self::get_grade_categories_parameters(),
            ["courseid" => $courseid]
        );

        $sql = "SELECT u.id, u.firstname, u.lastname, u.email
                FROM {user} u
                JOIN {user_enrolments} ue ON ue.userid = u.id
                JOIN {enrol} e ON e.id = ue.enrolid
                WHERE e.courseid = :courseid
                  AND e.enrol = 'self'
                  AND e.customint1 IS NOT NULL";

        $data = $DB->get_records_sql($sql, ["courseid" => $params["courseid"]]);
        return ["data" => array_values($data)];
    }

    public static function get_students_enrolled_by_password_returns()
    {
        return new external_single_structure([
            "data" => new external_multiple_structure(
                new external_single_structure([
                    "id" => new external_value(PARAM_INT, "User ID"),
                    "firstname" => new external_value(PARAM_TEXT, "First name"),
                    "lastname" => new external_value(PARAM_TEXT, "Last name"),
                    "email" => new external_value(PARAM_TEXT, "Email"),
                ])
            ),
        ]);
    }
}
