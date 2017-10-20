<?php
/**
 * Jemena User Report - Settings
 *
 * @package     report_userreport
 * @copyright   2017 Pukunui Technology
 * @author      Priya Ramakrishnan, Pukunui {@link http://pukunui.com}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

$ADMIN->add('reports', new admin_externalpage('report_userreport', get_string('pluginname', 'report_userreport'),
            "$CFG->wwwroot/report/userreport/index.php", 'reports/userreport:view'));

