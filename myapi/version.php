<?php
require_once(__DIR__ . '/utils/CourseUtil.php');
defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_myapi';
$plugin->version = 2024102708;
$plugin->requires = 2022112800; // Moodle 4.1 release date.
$plugin->maturity = MATURITY_STABLE;
$plugin->release = '1.0';
