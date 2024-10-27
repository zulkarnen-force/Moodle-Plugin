<?php
namespace local_myapi\external;

use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_value;
use external_api;

class GradeCategory extends external_api
{
    public static function get_course_grade_categories_parameters()
    {
        return new external_function_parameters([
            "courseid" => new external_value(PARAM_INT, "Course ID"),
        ]);
    }

    public static function get_course_grade_categories($courseid)
    {
        global $DB;
        $course = $DB->get_record(
            "course",
            ["id" => $courseid],
            "*",
            MUST_EXIST
        );
        $sql = "SELECT gc.id, gc.courseid AS course_id, gc.fullname AS name
                FROM {grade_categories} gc
                WHERE gc.courseid = :courseid";
        $categories = $DB->get_records_sql($sql, ["courseid" => $courseid]);
        $result = [
            "course" => [
                "id" => $course->id,
                "fullname" => $course->fullname,
                "shortname" => $course->shortname,
                "category" => $course->category, // You can include other course fields as needed
            ],
            "gradecategories" => [],
        ];
        foreach ($categories as $category) {
            $result["gradecategories"][] = [
                "id" => $category->id,
                "course_id" => $category->course_id,
                "name" => $category->name,
            ];
        }
        return $result;
    }

    public static function get_course_grade_categories_returns()
    {
        return new external_single_structure([
            "course" => new external_single_structure([
                "id" => new external_value(PARAM_INT, "Course ID"),
                "fullname" => new external_value(
                    PARAM_TEXT,
                    "Full course name"
                ),
                "shortname" => new external_value(
                    PARAM_TEXT,
                    "Short course name"
                ),
                "category" => new external_value(PARAM_INT, "Category ID"),
            ]),
            "gradecategories" => new external_multiple_structure(
                new external_single_structure([
                    "id" => new external_value(PARAM_INT, "Category ID"),
                    "course_id" => new external_value(PARAM_INT, "Course ID"),
                    "name" => new external_value(PARAM_TEXT, "Category name"),
                ])
            ),
        ]);
    }
}
