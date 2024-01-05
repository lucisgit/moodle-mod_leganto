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

namespace mod_leganto\output;

use cm_info;
use context;
use context_module;
use renderable;
use stdClass;

/**
 * Leganto list renderable class.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */
class leganto_list implements renderable {

    /** @var context The context of the course module for this leganto_list instance. */
    public $context;

    /** @var stdClass The leganto database record for this leganto_list instance. */
    public $leganto;

    /** @var cm_info The course module info object for this leganto_list instance. */
    public $cm;

    /**
     * Constructor for the leganto_list class.
     *
     * @param stdClass $leganto The leganto record.
     * @param cm_info $cm The course module info.
     */
    public function __construct($leganto, $cm) {
        $this->leganto = $leganto;
        $this->cm = $cm;
        $this->context = context_module::instance($cm->id);
    }
}
