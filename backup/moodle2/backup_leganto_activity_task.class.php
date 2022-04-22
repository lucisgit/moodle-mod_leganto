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
 * Defines the backup_leganto_activity_task class.
 *
 * @package    mod_leganto
 * @category   backup
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/leganto/backup/moodle2/backup_leganto_stepslib.php');

/**
 * Provides the steps to perform one complete backup of the leganto instance.
 *
 * @package    mod_leganto
 * @category   backup
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */
class backup_leganto_activity_task extends backup_activity_task {

    /**
     * Define (add) particular settings this activity can have.
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Defines a backup step to store the instance data in the leganto.xml file.
     */
    protected function define_my_steps() {
        $this->add_step(new backup_leganto_activity_structure_step('leganto_structure', 'leganto.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts.
     *
     * @param string $content Some HTML text that eventually contains URLs to the activity instance scripts.
     * @return string The content with the URLs encoded.
     */
    public static function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        // Link to the list of leganto instances.
        $search = '/(' . $base . '\/mod\/leganto\/index.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@LEGANTOINDEX*$2@$', $content);

        // Link to leganto view by module id.
        $search = '/(' . $base . '\/mod\/leganto\/view.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@LEGANTOVIEWBYID*$2@$', $content);

        return $content;
    }
}
