<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Leganto module main user interface.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/leganto/locallib.php');
require_once($CFG->libdir . '/completionlib.php');

// Was this page requested via AJAX?
$ajaxrequest = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

// Stop here with an alert if page was requested via AJAX and the user is not logged in.
if ($ajaxrequest && !isloggedin()) {
    $result = new stdClass();
    $result->error = get_string('sessionerroruser', 'error');
    if (ob_get_contents()) {
        ob_clean();
    }
    echo json_encode($result);
    die();
}

$id = optional_param('id', 0, PARAM_INT);  // Course module id.
$a  = optional_param('a', 0, PARAM_INT);   // Leganto instance id.

// Two ways to specify the module.
if ($a) {
    $leganto = $DB->get_record('leganto', ['id' => $a], '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('leganto', $leganto->id, $leganto->course, true, MUST_EXIST);
} else {
    $cm = get_coursemodule_from_id('leganto', $id, 0, true, MUST_EXIST);
    $leganto = $DB->get_record('leganto', ['id' => $cm->instance], '*', MUST_EXIST);
}

$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/leganto:view', $context);

// Redirect only if page was not requested via AJAX.
if ($leganto->display != LEGANTO_DISPLAY_PAGE && !$ajaxrequest) {
    redirect(course_get_url($leganto->course)->out() . '#module-' . $cm->id);
}

$params = [
    'context' => $context,
    'objectid' => $leganto->id,
];
$event = \mod_leganto\event\course_module_viewed::create($params);
$event->add_record_snapshot('course_modules', $cm);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('leganto', $leganto);
$event->trigger();

// Update 'viewed' state if required by completion system.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Stop processing here if page was requested via AJAX.
if ($ajaxrequest) {
    if (ob_get_contents()) {
        ob_clean();
    }
    echo json_encode('');
    die();
}

$PAGE->set_url('/mod/leganto/view.php', ['id' => $cm->id]);

$PAGE->set_title($course->shortname . ': ' . $leganto->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_activity_record($leganto);

$PAGE->add_body_class('limitedwidth');

$output = $PAGE->get_renderer('mod_leganto');

echo $output->header();

echo $output->display_leganto($leganto);

echo $output->footer();
