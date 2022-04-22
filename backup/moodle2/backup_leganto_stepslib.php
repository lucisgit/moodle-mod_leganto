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
 * Define all the backup steps that will be used by the backup_leganto_activity_task.
 *
 * @package    mod_leganto
 * @category   backup
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

/**
 * Define the complete leganto structure for backup, with file and id annotations.
 *
 * @package    mod_leganto
 * @category   backup
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */
class backup_leganto_activity_structure_step extends backup_activity_structure_step {

    /**
     * Prepare the activity data.
     *
     * @return \backup_nested_element
     */
    protected function define_structure() {
        // To know if we are including user info.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $leganto = new backup_nested_element('leganto', array('id'), array('name', 'intro', 'introformat', 'timemodified',
                'display', 'citations', 'displaynotes'));

        // Build the tree.
        // (nice mono-tree, lol).

        // Define sources.
        $leganto->set_source_table('leganto', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations.
        // (none).

        // Define file annotations.
        $leganto->annotate_files('mod_leganto', 'intro', null);

        // Return the root element (leganto), wrapped into standard activity structure.
        return $this->prepare_activity_structure($leganto);
    }
}
