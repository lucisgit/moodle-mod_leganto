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

use context_module;
use leganto;
use plugin_renderer_base;
use stdClass;

/**
 * Leganto module renderer class.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */
class renderer extends plugin_renderer_base {

    /**
     * Return the HTML to display the content of the customised reading list.
     *
     * @param stdClass $leganto Record from 'leganto' table.
     * @return string
     */
    public function display_leganto(stdClass $leganto) {
        $output = '';
        $legantoinstances = get_fast_modinfo($leganto->course)->get_instances_of('leganto');
        if (!isset($legantoinstances[$leganto->id]) ||
                !($cm = $legantoinstances[$leganto->id]) ||
                !($context = context_module::instance($cm->id))) {
            // Some error in parameters.
            // Don't throw any errors in renderer, just return empty string.
            // Capability to view module must be checked before calling renderer.
            return $output;
        }

        $legantolist = new leganto_list($leganto, $cm);
        if ($leganto->display != LEGANTO_DISPLAY_PAGE) {
            if ($cm->showdescription && trim($leganto->intro)) {
                $output .= format_module_intro('leganto', $leganto, $cm->id, false);
                $desc = true;
            }
            $viewlink = (string) $cm->url;
            $expanded = $leganto->display == LEGANTO_DISPLAY_INLINE_EXPANDED;
            $listid = $cm->modname . '-' . $cm->id;

            // YUI function to hide inline reading list until user clicks 'view' link.
            $this->page->requires->js_init_call('M.mod_leganto.initList', [$cm->id, $viewlink, $expanded, !empty($desc)]);
            $output .= $this->output->container($this->render($legantolist), 'legantobox', $listid);
        } else {
            $output .= $this->output->container($this->render($legantolist), '', 'leganto');
        }

        return $output;
    }

    /**
     * Render the HTML for the customised reading list.
     *
     * @param leganto_list $list The list renderable.
     * @return string The HTML to render the list.
     */
    public function render_leganto_list(leganto_list $list) {
        global $CFG;

        require_once($CFG->dirroot . '/mod/leganto/locallib.php');

        $leganto = new leganto($list->context, $list->cm, null);
        $output = $leganto->get_list_html($list->leganto->citations, $list->leganto->display);

        return $output;
    }
}
