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
 * Leganto module upgrade code.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

/**
 * Upgrade the leganto module to the current version.
 *
 * @param int $oldversion The old version of the leganto module.
 * @return bool True.
 */
function xmldb_leganto_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // Moodle v3.4.0 release upgrade line.
    // Put any upgrade step following this.

    if ($oldversion < 2018013000) {
        // Add a 'displaynotes' field to the 'leganto' table.
        $table = new xmldb_table('leganto');
        $field = new xmldb_field('displaynotes', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'citations');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Leganto savepoint reached.
        upgrade_mod_savepoint(true, 2018013000, 'leganto');
    }

    return true;
}
