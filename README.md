This file is part of Moodle - http://moodle.org/

Moodle is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Moodle is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

    copyright   2017 Lancaster University (http://www.lancaster.ac.uk/)
    license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
    author      Tony Butler <a.butler4@lancaster.ac.uk>


Leganto reading list module for Moodle
======================================

The Leganto reading list module enables a teacher to include a selection of
citations from associated Leganto reading lists directly within the content
of their course.

The reading list can be displayed either in a separate, linked page, or
embedded in the course page itself (hidden initially, with a link to toggle
visibility).


Changelog
---------

2024-10-07  v4.5.0

  * Fix inline display of show/hide arrow for Moodle 4.5
  * Address new issues identified by code checker

2024-01-05  v4.1.0

  * Fix inline list display inconsistencies in Moodle 4.1+
  * Refactor output renderer and renderable classes
  * Address new issues identified by code checker and CI tests

2022-06-01  v4.0.0

  * Fix JS selectors and make transitions less jarring
  * Various fixes to improve support for Moodle 4.0
  * Address issues identified by Moodle Plugin CI tests

2020-10-27  v3.5.1

  * Tell set_content() that list content is already formatted
  * Reset cached list data when loading course module config

2018-09-07  v3.5.0

  * Add option to display list content inline and auto-expanded
  * Redirect to the list rather than just the course section
  * Add null privacy provider for GDPR support
  * Add support for new chapter metadata attributes in API

2018-01-30  v3.1.1, v3.3.1

  * Display chapter titles for citations of type 'Book Chapter'
  * Support course/list/section/citation ids of > 16 characters
  * Add option to include public notes in citation display
  * Expand list fieldset if it contains pre-selected citations

2017-09-29  v3.1.0, v3.3.0

  * Initial stable release


Installation
------------

Installing from the Git repository (recommended if you installed Moodle from
Git):

Follow the instructions at
https://docs.moodle.org/405/en/Git_for_Administrators#Installing_a_contributed_extension_from_its_Git_repository,
e.g. for the Moodle 4.5.x code:

    $ cd /path/to/your/moodle/
    $ cd mod/
    $ git clone https://github.com/tonyjbutler/moodle-mod_leganto.git leganto
    $ cd leganto/
    $ git checkout -b MOODLE_405_STABLE origin/MOODLE_405_STABLE
    $ git branch -d master
    $ cd /path/to/your/moodle/
    $ echo /mod/leganto/ >> .git/info/exclude


Installing from a zip archive downloaded from
https://moodle.org/plugins/pluginversions.php?plugin=mod_leganto:

1. Download and unzip the appropriate release for your version of Moodle.
2. Place the extracted "leganto" folder in your "/mod/" subdirectory.

Whichever method you use to get the module code in place, the final step is to
visit your Site Administration > Notifications page in a browser to invoke the
installation script and make the necessary database changes.


Updating Moodle
---------------
If you installed Moodle and the Leganto reading list module from Git you can
run the following commands to update both (see
https://docs.moodle.org/405/en/Git_for_Administrators#Installing_a_contributed_extension_from_its_Git_repository):

    $ cd /path/to/your/moodle/
    $ git pull
    $ cd mod/leganto/
    $ git pull

If you installed from a zip archive you will need to repeat the installation
procedure using the appropriate zip file downloaded from
https://moodle.org/plugins/pluginversions.php?plugin=mod_leganto for your
new Moodle version.
