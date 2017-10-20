<?php
/**
 * Jemena User Report
 *
 * @package     report_userreport
 * @copyright   2017 Pukunui Technology
 * @author      Priya Ramakrishnan, Pukunui {@link http://pukunui.com}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once($CFG->dirroot.'/report/userreport/locallib.php');
require($CFG->dirroot.'/report/userreport/userreport_form.php');

require_login();
$systemcontext = context_system::instance();
$url = new moodle_url('/report/userreport/index.php');
$strtitle = get_string('pluginname', 'report_userreport');
// Set up PAGE object.
$PAGE->set_url($url);
$PAGE->set_context($systemcontext);
$PAGE->set_title($strtitle);
$PAGE->set_pagelayout('report');
$PAGE->set_heading($strtitle);

// Load up the form.
$mform = new userreport_form();

// Process the data.
if ($data = $mform->get_data()) {
    $courseid = 0;
    if (!empty($data->generate)) {
        if ($data->course) {
            $courseid = $data->course;
        }
        report_userreport_generate($courseid);
        exit;
    }
}
// Form & header Output.
echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();
