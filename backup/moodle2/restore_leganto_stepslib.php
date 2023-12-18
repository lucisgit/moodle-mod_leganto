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
 * Define all the restore steps that will be used by the restore_leganto_activity_task.
 *
 * @package    mod_leganto
 * @category   backup
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

/**
 * Structure step to restore a leganto activity.
 *
 * @package    mod_leganto
 * @category   backup
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */
class restore_leganto_activity_structure_step extends restore_activity_structure_step {

    /**
     * Prepare the activity data.
     *
     * @return array
     */
    protected function define_structure() {
        $paths = [];
        $paths[] = new restore_path_element('leganto', '/activity/leganto');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Restore the activity data.
     *
     * @param object $data
     */
    protected function process_leganto($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Insert the leganto record.
        $newitemid = $DB->insert_record('leganto', $data);
        // Immediately after inserting leganto record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Post-restore tasks.
     */
    protected function after_execute() {
        // Add leganto related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_leganto', 'intro', null);
    }
}
