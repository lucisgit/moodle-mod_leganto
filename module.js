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
 * Javascript helper function for leganto module.
 *
 * @package    mod_leganto
 * @copyright  2017 Lancaster University {@link http://www.lancaster.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Tony Butler <a.butler4@lancaster.ac.uk>
 */

M.mod_leganto = {};

M.mod_leganto.initList = function(Y, cmid, url, expanded, desc) {
    Y.use('node', 'transition', function(Y) {
        /**
         * Set relative position style for a list node.
         *
         * @method setRelativePosition
         */
        function setRelativePosition() {
            this.setStyle('position', 'relative');
        }
        Y.Transition.fx.slideFadeOut = {
            opacity: 0,
            top: '-100px',
            left: '0px',
            duration: 0.2,
            easing: 'ease-out',
            on: {start: setRelativePosition}
        };
        Y.Transition.fx.slideFadeIn = {
            opacity: 1.0,
            top: '0px',
            left: '0px',
            duration: 0.2,
            easing: 'ease-in',
            on: {start: setRelativePosition}
        };

        var listid = '#leganto-' + cmid,
            arrowid = '#showhide-' + cmid;

        // Hide list if not expanded by default.
        if (!expanded) {
            Y.one(listid).hide('slideFadeOut');
            // Have to hide again without transition to get display: none.
            Y.one(listid).hide();
            Y.one(arrowid).addClass('collapsed');
        }

        // Move list node out of 'activity-altcontent' div and remove if no description.
        var listNode = document.getElementById('leganto-' + cmid),
            altNode = listNode.parentNode.parentNode;
        altNode.parentNode.parentNode.appendChild(listNode);
        if (desc === false) {
            altNode.remove();
        }

        Y.delegate('click', function(e) {
            if (e.currentTarget.getAttribute('href') === url) {
                var list = Y.one(listid),
                    arrow = Y.one(arrowid);

                // Add a JavaScript loading icon.
                var spinner = M.util.add_spinner(Y, arrow);
                spinner.removeClass('iconsmall');
                spinner.setStyle('position', 'static');

                // Display the JS loading icon.
                spinner.show();

                if (arrow.hasClass('collapsed')) {
                    // Send AJAX request for view.php (to trigger log/completion events).
                    var httpRequest = new XMLHttpRequest();

                    // Parse the response and check for errors.
                    httpRequest.onreadystatechange = function() {
                        if (httpRequest.readyState === 4) {
                            var data = Y.JSON.parse(httpRequest.responseText);
                            if (data.hasOwnProperty('error')) {
                                // Alert user if an error has occurred.
                                require(['core/notification'], function(notification) {
                                    notification.alert('', data.error);
                                });
                                window.location.href = url;
                            } else {
                                // If all is well, expand the list.
                                list.show('slideFadeIn');
                                arrow.removeClass('collapsed');
                                // Hide the JS loading icon.
                                spinner.hide();
                            }
                        }
                    };
                    httpRequest.open('GET', url);
                    httpRequest.setRequestHeader('X-Requested-With', 'xmlhttprequest');
                    httpRequest.send();
                } else {
                    list.hide('slideFadeOut');
                    arrow.addClass('collapsed');
                    // Hide the JS loading icon.
                    spinner.hide();
                }
            }

        }, document, 'a');
    });
};
