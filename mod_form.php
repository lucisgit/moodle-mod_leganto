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
 * Leganto configuration form.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once($CFG->dirroot . '/mod/leganto/locallib.php');

/**
 * Instance configuration form for the leganto module.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */
class mod_leganto_mod_form extends moodleform_mod {

    /** @var leganto The standard base class for mod_leganto. */
    private $leganto;

    /**
     * Called to define this moodle form.
     *
     * @return void
     */
    public function definition() {
        global $CFG, $DB;
        $mform = $this->_form;

        $ctx = null;
        if ($this->current && $this->current->coursemodule) {
            $cm = get_coursemodule_from_instance('leganto', $this->current->id, 0, false, MUST_EXIST);
            $ctx = context_module::instance($cm->id);
        }
        $this->leganto = new leganto($ctx, null, null);
        if ($this->current && $this->current->course) {
            if (!$ctx) {
                $ctx = context_course::instance($this->current->course);
            }
            $course = $DB->get_record('course', ['id' => $this->current->course], '*', MUST_EXIST);
            $this->leganto->set_course($course);
        }

        // General section.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('listname', 'leganto'), ['size' => '64']);
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $this->standard_intro_elements();

        // Citation selection section.
        if ($lists = $this->leganto->get_lists($this->leganto->get_course(), true)) {
            $this->setup_list_elements($mform, $lists);
        } else {
            $strcourse = strtolower(get_string('course'));
            $nolists = html_writer::div(get_string('nolists', 'leganto', $strcourse), 'alert alert-warning');
            $mform->addElement('html', $nolists);
        }

        // Appearance section.
        $adminconfig = $this->leganto->get_admin_config();

        $mform->addElement('header', 'appearance', get_string('appearance'));
        $mform->setExpanded('appearance', true);

        $mform->addElement('select', 'display', get_string('display', 'leganto'), [
                LEGANTO_DISPLAY_PAGE             => get_string('displaypage', 'leganto'),
                LEGANTO_DISPLAY_INLINE_COLLAPSED => get_string('displayinlinecollapsed', 'leganto'),
                LEGANTO_DISPLAY_INLINE_EXPANDED  => get_string('displayinlineexpanded', 'leganto'),
            ]
        );
        $mform->addHelpButton('display', 'display', 'leganto');
        $mform->setDefault('display', $adminconfig->defaultdisplay);

        $mform->addElement('advcheckbox', 'displaynotes', get_string('displaypublicnotes', 'leganto'));
        $mform->addHelpButton('displaynotes', 'displaypublicnotes', 'leganto');

        // Common elements section.
        $this->standard_coursemodule_elements();

        // Form action buttons.
        $this->add_action_buttons();
    }

    /**
     * Set up the config form elements for the Leganto reading lists associated with this course.
     *
     * @param MoodleQuickForm $mform The config form.
     * @param array $lists The Leganto lists to set up.
     */
    private function setup_list_elements(&$mform, $lists) {
        $listindex = 0;
        $checkboxgrp = 1;

        // Get a list of previously selected citations for updates.
        $selected = $this->leganto->get_selected_citations();

        foreach ($lists as $list) {
            $mform->addElement('header', 'list-' . $listindex, get_string('selectcitations', 'leganto', $list->name));

            $sectionid = '';
            $expandlist = false;
            foreach ($list->citations->citation as $citation) {
                if (!empty($citation->section_info->id) && $citation->section_info->id != $sectionid) {
                    // This is a new section, so fetch its data and set up its elements.
                    $sectionid = $citation->section_info->id;
                    if ($sectionhtml = $this->leganto->get_section_html($list, $sectionid)) {
                        $mform->addElement('html', $sectionhtml);
                        $this->add_checkbox_controller($checkboxgrp);
                        $checkboxgrp++;
                    }
                }

                // Fetch citation data and set up its elements.
                $parentpath = 'course-' . $list->courseid . '_list-' . $list->id . '_section-' . $sectionid;
                if ($citationdata = $this->leganto->get_citation_data($list, $citation->id, $parentpath)) {
                    $expandlist = $this->setup_citation_elements($mform, $checkboxgrp, $citationdata, $selected) || $expandlist;
                }
            }

            // Expand list fieldset automatically if any of its citations were pre-selected.
            $mform->setExpanded('list-' . $listindex, $expandlist);
            $listindex++;
        }

        // If only one reading list was found, expand its fieldset automatically.
        if (count($lists) == 1) {
            $mform->setExpanded('list-0', true);
        }
    }

    /**
     * Set up the config form elements for the given Leganto citation object.
     *
     * @param MoodleQuickForm $mform The config form.
     * @param int $checkboxgrp The current checkbox group id.
     * @param stdClass $citation The citation data to set up.
     * @param array $selected A list of previously selected citations.
     * @return bool Whether or not this citation was pre-selected.
     */
    private function setup_citation_elements(&$mform, $checkboxgrp, $citation, $selected) {
        $adminconfig = $this->leganto->get_admin_config();

        // Pre-select previously selected citations if this is an update.
        $default = 0;
        if (!empty($selected)) {
            if (in_array($citation->path, $selected)) {
                $default = 1;
            }
        }

        $label = $citation->title;
        if (!empty($citation->permalink)) {
            $label .= ' ' . $citation->permalink;
        }
        if (!empty($adminconfig->authorsinconfig) && !empty($citation->author)) {
            $label .= html_writer::empty_tag('br') . $citation->author;
        }
        $mform->addElement('advcheckbox', $citation->path, null, $label, ['group' => $checkboxgrp - 1]);
        $mform->setDefault($citation->path, $default);

        return !empty($default);
    }

    /**
     * Validate submitted data and uploaded files.
     *
     * @param array $data Submitted data.
     * @param array $files Uploaded files.
     * @return array Validation errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        return $errors;
    }
}
