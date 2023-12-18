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
 * Leganto module admin settings and defaults.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/leganto/adminlib.php');

if ($ADMIN->fulltree) {

    // General settings.
    $settings->add(new admin_setting_heading('leganto/generalsettings', get_string('generalsettings', 'leganto'), ''));
    $settings->add(new admin_setting_configcheckbox('leganto/requiremodintro', get_string('requiremodintro', 'leganto'),
            get_string('requiremodintro_desc', 'leganto'), 0));

    // Display settings.
    $optionsdd = [];
    $optionsdd[0] = get_string('displaypage', 'leganto');
    $optionsdd[1] = get_string('displayinlinecollapsed', 'leganto');
    $optionsdd[2] = get_string('displayinlineexpanded', 'leganto');
    $settings->add(new admin_setting_configselect('leganto/defaultdisplay', get_string('defaultdisplay', 'leganto'),
            get_string('defaultdisplay_desc', 'leganto'), 0, $optionsdd));

    // Authors in module config form.
    $settings->add(new admin_setting_configcheckbox('leganto/authorsinconfig', get_string('authorsinconfig', 'leganto'),
            get_string('authorsinconfig_desc', 'leganto'), 0));

    // API settings.
    $settings->add(new admin_setting_heading('leganto/apisettings', get_string('apisettings', 'leganto'), ''));
    $settings->add(new admin_setting_configtext('leganto/apiurl', get_string('apiurl', 'leganto'),
            get_string('apiurl_desc', 'leganto'), get_string('apiurl_default', 'leganto'), PARAM_URL));
    $settings->add(new admin_setting_configtext('leganto/apikey', get_string('apikey', 'leganto'),
            get_string('apikey_desc', 'leganto'), '', PARAM_TEXT));

    // Code settings.
    $settings->add(new admin_setting_heading('leganto/codesettings', get_string('codesettings', 'leganto'), ''));

    // Code source.
    $optionscs = [];
    $optionscs['idnumber'] = get_string('idnumbercourse');
    $optionscs['shortname'] = get_string('shortnamecourse');
    $optionscs['codetable'] = get_string('codetable', 'leganto');
    $settings->add(new leganto_codesource_setting('leganto/codesource', get_string('codesource', 'leganto'),
            get_string('codesource_desc', 'leganto'), 'idnumber', $optionscs));

    // Code regexes.
    $settings->add(new admin_setting_configtext('leganto/coderegex', get_string('coderegex', 'leganto'),
            get_string('coderegex_desc', 'leganto'), '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext('leganto/yearregex', get_string('yearregex', 'leganto'),
            get_string('yearregex_desc', 'leganto'), '', PARAM_TEXT));

    // Code table details.
    $settings->add(new leganto_codetable_setting('leganto/codetable', get_string('codetable', 'leganto'),
            get_string('codetable_desc', 'leganto'), $CFG->prefix, PARAM_TEXT));
    $settings->add(new leganto_codecolumn_setting('leganto/codecolumn', get_string('codecolumn', 'leganto'),
            get_string('codecolumn_desc', 'leganto'), '', PARAM_TEXT));
    $settings->add(new leganto_coursecolumn_setting('leganto/coursecolumn', get_string('coursecolumn', 'leganto'),
            get_string('coursecolumn_desc', 'leganto'), '', PARAM_TEXT));
    $settings->add(new leganto_courseattribute_setting('leganto/courseattribute',
            get_string('courseattribute', 'leganto'), get_string('courseattribute_desc', 'leganto'), '', PARAM_TEXT));

    // Meta child codes.
    $settings->add(new admin_setting_configcheckbox('leganto/includechildcodes', get_string('includechildcodes', 'leganto'),
            get_string('includechildcodes_desc', 'leganto'), 0));

}
