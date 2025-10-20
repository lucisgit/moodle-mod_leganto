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
 * Leganto module admin library.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

namespace mod_leganto\admin_setting;

use admin_setting_configselect;

/**
 * Admin setting for code source, adds validation.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */
class codesource extends admin_setting_configselect {
    /**
     * Validate data.
     *
     * This ensures that all required table details are provided if custom table
     * is selected as the code source.
     *
     * @param string $data The submitted data.
     * @return mixed True on success, else error message.
     */
    public function validate($data) {
        $result = parent::validate($data);
        if ($result !== true) {
            return $result;
        }

        $codetable = get_config('leganto', 'codetable');
        $codecolumn = get_config('leganto', 'codecolumn');
        $coursecolumn = get_config('leganto', 'coursecolumn');
        $courseattribute = get_config('leganto', 'courseattribute');
        if ($data === 'codetable' && (empty($codetable) || empty($codecolumn) || empty($coursecolumn) || empty($courseattribute))) {
            return get_string('errorcodesource', 'leganto');
        }

        return true;
    }
}
