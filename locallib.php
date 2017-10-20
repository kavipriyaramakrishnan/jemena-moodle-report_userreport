<?php
/**
 * Jemena User Report - Local library function
 *
 * @package     report_userreport
 * @copyright   2017 Pukunui Technology
 * @author      Priya Ramakrishnan, Pukunui {@link http://pukunui.com}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
ini_set('max_execution_time', 500);
function report_userreport_generate($courseid) {
	global $DB, $CFG;

	$sql = "Select CONCAT(u.id, c.shortname),
		u.lastname,
		u.firstname,
		u.email,u.username,
		c.id as 'courseid',
		c.fullname as course,
		cgt.name as category,
		FROM_UNIXTIME(c.startdate) as coursestartdate,
		from_unixtime(ue.timestart) as enrolstartdate,
		from_unixtime(cc.timecompleted) as completiondate,
		from_unixtime(a.last_accessed) as lastaccesseddate,
		gg1.finalgrade
	        from {user} u
		Left join {user_enrolments} ue on u.id = ue.userid
		Left Join {enrol} e on e.id = ue.enrolid
		Left join {grade_items} gi on gi.courseid =e.courseid
		Left join {grade_grades} gg1 on gi.id = gg1.itemid and gg1.userid = u.id
		Left Join {course} c on c.id = e.courseid
		LEFT JOIN {course_categories} cgt ON cgt.id = c.category
		Left Join {course_completions} cc on cc.userid = u.id
		and cc.course = c.id
		Left Join ( Select l.userid, l.course,
                            max(time) as last_accessed
			    from {log} l
			    Group by 1,2
	        ) as a on a.userid = u.id
	        and a.course = c.id
		WHERE u.deleted = 0
		and u.suspended = 0
		and gi.itemtype = 'course'";
        if ($courseid) {
            $sql .= " AND c.id = $courseid ";
        }    
        $sql .= " ORDER BY 2,3,4";
        $records = $DB->get_records_sql($sql);

	// Generate report 
        $filename = clean_filename("userreport.csv");
        @header('Content-Disposition: attachment; filename='.$filename);
        @header('Content-Type: text/csv');
        $csvhead = get_string('lastname', 'report_userreport').','.get_string('firstname', 'report_userreport').','.get_string('email', 'report_userreport').','.get_string('username', 'report_userreport').','.get_string('courseid', 'report_userreport').','.get_string('course', 'report_userreport').','.get_string('category', 'report_userreport').','.get_string('coursestartdate', 'report_userreport').','. get_string('enrolstartdate', 'report_userreport').','. get_string('completiondate', 'report_userreport').','.get_string('lastaccesseddate', 'report_userreport').','. get_string('finalgrade', 'report_userreport');
        $csvhead .= "\r\n";
        echo $csvhead;
        $csvdata = array();
        foreach ($records as $r) {
           $csvdata = $r->lastname.','.$r->firstname.','.$r->email.','.$r->username.','.$r->courseid.',"'.$r->course.'","'.$r->category.'",'.$r->coursestartdate.','.$r->enrolstartdate.','.$r->completiondate.','.$r->lastaccesseddate.','.$r->finalgrade."\r\n";
           echo $csvdata; 
        }
} 
