<?php

    /**
     * ------------------
     * Language: English
     * ------------------
     **/

    $lang['LANG'] = "en";

    $lang['FORGOT PASS'] = "Forgot password";
    $lang['LOGIN'] = "Login";
    $lang['USERNAME'] = "Username";
    $lang['NAME'] = "Name";
    $lang['LAST_NAME'] = "Last name";
    $lang['PASSWORD'] = "Password";
    $lang['CONFIRM_PASSWORD'] = "Confirm password";
    $lang['PASSWORD_MISMATCH'] = "Passwords do not match";
    $lang['EMAIL'] = "Email";
    $lang['TEACHER'] = "Teacher";
    $lang['BACK'] = "Back";

    // Profile
    $lang['MY_PROFILE'] = "My Profile";
    $lang['CHANGE_PASS'] = "Change password";
    $lang['ACTUAL_PASS'] = "Current Password";
    $lang['NEW_PASS'] = "New Password";
    $lang['CONFIRM_PASS'] = "Confirm password";
    $lang['MODIFY'] = "Change";
    $lang['MY_MAIL'] = "My email address";

    // Error messages
    $lang['SORRY'] = "Sorry!";
    $lang['PROVIDE_PASSWORD'] = "Please provide a password.";
    $lang['ALL_FIELDS_REQUIRED'] = "All fields are required!";
    $lang['PROVIDE_EMAIL'] = "Invalid Email";
    $lang['SAFE_PASSWORD'] = "Your password must contain at least 8 characters including 1 uppercase and 1 number.";
    $lang['ALREADY_USER'] = "Email or username already used";
    $lang['USER_EXPLOIT'] = "Unexpected error ";
    $lang['USER_INCLUSION_EXPLOIT'] = "Attempting RFI detected ...";

    $lang['NO_INSTRUCTIONS'] = "Sorry, the instructions are not (yet) available.";
    $lang['MAX_SIZE_REACHED'] = "File size greater than 200 MB Check your export.";
    $lang['UNEXPECTED_FILE_TYPE'] = "Unexpected file format.";
    $lang['DIR_CREATION_ERROR'] = "Error creating directory.";
    $lang['NO_FILE'] = "Choose a file!";
    $lang['SELECT_FILE'] = "Select a file";
    $lang['REQUIRED_ANSWER'] = "Answer Required";

    // Inform
    $lang['INVALID_USER_PASS'] = "Invalid username or password.";
    
    // Menu
    $lang['GRADES'] = "Assessments";
    $lang['RESULTS'] = "Results";
    $lang['INSTRUCTIONS'] = "instructions";
    $lang['SUBMIT'] = "Submissions";
    $lang['SKILLS'] = "Skills";
    $lang['ACCOUNT'] = "Account";
    $lang['GALLERY'] = "Hall of Fame";
    $lang['MY_GALLERY'] = "My projects";
    $lang['GRADE_BOOK'] = "Gradebook";
    $lang['LOGOUT'] = "Logout";
    $lang['MANAGE_PROJECTS'] = "Projects";
    $lang['RATE'] = "Correct";
    $lang['SETTINGS'] = "Config";
    $lang['NEW_PROJECT'] = "New Project";
    
    // Java
    $lang['NO_PDF_READER'] = "<p> It Appears your Web browser is not configured to display PDF files. No worries, just <a href='123.pdf'> click here to download the PDF file. </ a > </ p> ";
    
    // Submit
    $lang['SUBMIT_FILE'] = "Submit file";
    $lang['SELF_ASSESSMENT'] = "Self assessment";
    $lang['SUBMIT_WORK'] = "I submit my project";
    $lang['DISACTIVATE_PROJECT'] = "Disactivate project ";
    $lang['ACTIVATE_PROJECT'] = "Activate project";
    $lang['SUBMITTED_FILE'] = "File submitted:";
    
    // Results
    $lang['NOT_GRADED_YET'] = "<p> Your work has not been graded yet </ p>.";
    $lang['PERCENT'] = "Percentage";
    
    // Admin
    $lang['PROJECT_LEN'] = "Lenght of the project";
    $lang['ADMIN'] = "Admin";
    $lang['PROJECT_NOT_FOUND'] = "The project has not been found";
    
    // Add project
    $lang['PROJECT_TITLE'] = "Project Name";
    $lang['PROJECT_INFO'] = "General information";
    $lang['PROJECT_FILE_INFO'] = "instructions";
    $lang['ASSESSMENT_TYPE'] = "Evaluation type";
    $lang['ASSESSMENT_TYPE_1'] = "Formative";
    $lang['ASSESSMENT_TYPE_2'] = "Certification";
    $lang['ASSESSMENT_TYPE_3'] = "Diagnosis";
    $lang['ASSESSMENT_TYPE_4'] = "Summative";
    $lang['PRESS_CTRL_SELECT'] = "Press <kbd>CTRL</kbd> for multiple selection";
    $lang['SKILLS_SEEN'] = "worked Skills";
    $lang['UPLOAD_INSTRUCTIONS'] = "Upload instructions";
    $lang['ONLY_PDF_ALLOWED'] = "PDF only";
    $lang['CRITERIA'] = "Criteria";
    $lang['CRITERION'] = "Test";
    $lang['SKILLS_GROUP'] = "Objectives";
    $lang['SKILL'] = "Objective";
    $lang['FACTOR'] = "Coefficient";
    $lang['ASSESSMENT_GRID'] = "Assessment Grid";
    $lang['CURSORS'] = "Cursors (the student has:)";
    $lang['CURSOR'] = "Cursor (the student has:)";
    $lang['CLASS'] = "Class";
    $lang['DEADLINE'] = "Deadline";
    $lang['NEW_CRITERION'] = "New criterion";
    $lang['ADD_CRITERION'] = "Add criterion"; // Mind the first space!
    $lang['ADD_CURSOR'] = "Add cursor"; // Mind the first space!
    $lang['DEL_CURSOR'] = "Delete cursor"; // Mind the first space!
    $lang['SELF_ASSESSMENT'] = "Self assessment";
    $lang['ADD_QUESTION'] = "Add Question"; // Mind the first space!
    $lang['SAVE_PROJECT'] = "Save project";
    $lang['DEL_PROJECT'] = "Delete Project"; // Mind the first space!
    $lang['PROJECT_SAVED'] = "Project Saved!";
    $lang['EXPECTED_FILE'] = " Expected file extension";
    $lang['NO_EXPECTED_FILE'] = "No file requested";
    
    // Form control
    $lang['REQUIRED_NAME'] = "Name required";
    $lang['REQUIRED_DATE'] = "Deadline required";
    $lang['UNVALID_DATE'] = "Invalid Date";
    
    // Rating
    $lang['NO_HTML5_VIDEO'] = "It appears that your browser does not support HTML5 video but video can be downloaded.";
    $lang['HERE'] = "here";
    $lang['RATING'] = "Grade";
    $lang['SAVE_RATING'] = "Save assessment"; // Mind the first space!
    $lang['NOT_SUBMITTED'] = "Work not submitted";
    $lang['VOTE_00'] = "FX";
    $lang['VOTE_01'] = "F";
    $lang['VOTE_02'] = "F";
    $lang['VOTE_03'] = "F";
    $lang['VOTE_04'] = "F";
    $lang['VOTE_05'] = "E";
    $lang['VOTE_06'] = "D";
    $lang['VOTE_07'] = "C";
    $lang['VOTE_08'] = "B";
    $lang['VOTE_09'] = "A";
    $lang['VOTE_10'] = "A+";
    $lang['ADMIN_SKILLS'] = "Skills management";
    $lang['ADMIN_USERS'] = "User management";
    $lang['HOWTO_PROJECTS'] = "<h4> Welcome ". (isset($_SESSION ["name"])? $_SESSION ["name"] : "") . "</h4>";

?>
