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
 * List of legantos in course
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

require('../../config.php');

$id = required_param('id', PARAM_INT); // Course id.

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);

require_course_login($course);
$PAGE->set_pagelayout('incourse');

$params = [
    'context' => context_course::instance($course->id),
];
$event = \mod_leganto\event\course_module_instance_list_viewed::create($params);
$event->add_record_snapshot('course', $course);
$event->trigger();

$strleganto       = get_string('modulename', 'leganto');
$strlegantos      = get_string('modulenameplural', 'leganto');
$strname         = get_string('name');
$strintro        = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

$PAGE->set_url('/mod/leganto/index.php', ['id' => $course->id]);
$PAGE->set_title($course->shortname . ': ' . $strlegantos);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strlegantos);
echo $OUTPUT->header();

if (!$legantos = get_all_instances_in_course('leganto', $course)) {
    notice(get_string('thereareno', 'moodle', $strlegantos), "$CFG->wwwroot/course/view.php?id=$course->id");
    exit;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $strsectionname = get_string('sectionname', 'format_' . $course->format);
    $table->head  = [$strsectionname, $strname, $strintro];
    $table->align = ['center', 'left', 'left'];
} else {
    $table->head  = [$strlastmodified, $strname, $strintro];
    $table->align = ['left', 'left', 'left'];
}

$modinfo = get_fast_modinfo($course);
$currentsection = '';
foreach ($legantos as $leganto) {
    $cm = $modinfo->cms[$leganto->coursemodule];
    if ($usesections) {
        $printsection = '';
        if ($leganto->section !== $currentsection) {
            if ($leganto->section) {
                $printsection = get_section_name($course, $leganto->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $leganto->section;
        }
    } else {
        $printsection = '<span class="smallinfo">' . userdate($leganto->timemodified) . "</span>";
    }

    $class = $leganto->visible ? '' : 'class="dimmed"'; // Hidden modules are dimmed.
    $table->data[] = [
        $printsection,
        "<a $class href=\"view.php?id=$cm->id\">" . format_string($leganto->name) . "</a>",
        format_module_intro('leganto', $leganto, $cm->id),
    ];
}

echo html_writer::table($table);

echo $OUTPUT->footer();
