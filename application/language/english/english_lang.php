<?php

    /**
     * ------------------
     * Language: English
     * ------------------
     **/

    define('LANG', 'en');
    
    define('LABEL_DEMO_ACCOUNTS', 'Demo Accounts');

    define('LABEL_FORGOT_PASS', 'Forgot password');
    define('LABEL_LOGIN', 'Login');
    define('LABEL_USERNAME', 'Username');
    define('LABEL_NAME', 'Name');
    define('LABEL_LAST_NAME', 'Last name');
    define('LABEL_PASSWORD', 'Password');
    define('LABEL_CONFIRM_PASSWORD', 'Confirm password');
    define('LABEL_PASSWORD_MISMATCH', 'Passwords do not match');
    define('LABEL_EMAIL', 'Email');
    define('LABEL_TEACHER', 'Teacher');
    define('LABEL_BACK', 'Back');

    // Profile
    define('LABEL_MY_PROFILE', 'My Profile');
    define('LABEL_CHANGE_PASS', 'Change password');
    define('LABEL_ACTUAL_PASS', 'Current Password');
    define('LABEL_NEW_PASS', 'New Password');
    define('LABEL_CONFIRM_PASS', 'Confirm password');
    define('LABEL_MODIFY', 'Change');
    define('LABEL_MY_MAIL', 'My email address');

    // Error messages
    define('LABEL_SORRY', 'Sorry!');
    define('LABEL_PROVIDE_PASSWORD', 'Please provide a password.');
    define('LABEL_ALL_FIELDS_REQUIRED', 'All fields are required!');
    define('LABEL_PROVIDE_EMAIL', 'Invalid Email');
    define('LABEL_SAFE_PASSWORD', 'Your password must contain at least 8 characters including 1 uppercase and 1 number.');
    define('LABEL_ALREADY_USER', 'Email or username already used');
    define('LABEL_USER_EXPLOIT', 'Unexpected error ');
    define('LABEL_USER_INCLUSION_EXPLOIT', 'Attempting RFI detected ...');

    define('LABEL_NO_INSTRUCTIONS', 'Sorry, the instructions are not (yet) available.');
    define('LABEL_MAX_SIZE_REACHED', 'File size greater than 200 MB Check your export.');
    define('LABEL_UNEXPECTED_FILE_TYPE', 'Unexpected file format.');
    define('LABEL_DIR_CREATION_ERROR', 'Error creating directory.');
    define('LABEL_NO_FILE', 'Choose a file!');
    define('LABEL_SELECT_FILE', 'Select a file');
    define('LABEL_REQUIRED_ANSWER', 'Answer Required');

    // Inform
    define('LABEL_INVALID_USER_PASS', 'Invalid username or password.');
    
    // Menu
    define('LABEL_GRADES', 'Assessments');
    define('LABEL_RESULTS', 'Results');
    define('LABEL_INSTRUCTIONS', 'Instructions');
    define('LABEL_SUBMIT', 'Submit');
    define('LABEL_SKILLS', 'Knowledge');
    define('LABEL_ACCOUNT', 'Account');
    define('LABEL_GALLERY', 'Hall of Fame');
    define('LABEL_MY_GALLERY', 'My projects');
    define('LABEL_GRADE_BOOK', 'Gradebook');
    define('LABEL_LOGOUT', 'Logout');
    define('LABEL_MANAGE_PROJECTS', 'Projects');
    define('LABEL_RATE', 'Grade');
    define('LABEL_CONFIG', 'Settings');
    define('LABEL_NEW_PROJECT', 'New Project');
    define('LABEL_SUBMITTED_PROJECT', 'Submitted');
    
    // Java
    define('LABEL_NO_PDF_READER', 'It Appears your Web browser is not configured to display PDF files.');
    
    // Submit
    define('LABEL_SUBMIT_FILE', 'Submit file');
    define('LABEL_SUBMIT_FILES', 'Submit files');
    define('LABEL_SUBMIT_WORK', 'I submit my project');
    define('LABEL_DISACTIVATE_PROJECT', 'Disactivate project ');
    define('LABEL_ACTIVATE_PROJECT', 'Activate project');
    define('LABEL_SUBMITTED_FILE', 'File submitted:');
    
    // Results
    define('LABEL_NOT_GRADED_YET', '<p> Your work has not been graded yet </ p>.');
    define('LABEL_PERCENT', 'Percentage');
    define('LABEL_ALL_PERIODS', 'All terms');
    define('LABEL_STUDENTS_FROM', 'Grade:');
    define('LABEL_PERIOD', 'Term');  
    define('LABEL_AVERAGE', 'Average'); 
  
    // Admin
    define('LABEL_PROJECT_LEN', 'Lenght of the project');
    define('LABEL_ADMIN', 'Admin');
    define('LABEL_PROJECT_NOT_FOUND', 'The project has not been found');
    define('LABEL_CLASS_ROLL', 'Class Roll');
    
    // Add project
    define('LABEL_PROJECT_TITLE', 'Project Name');
    define('LABEL_PROJECT_INFO', 'General information');
    define('LABEL_PROJECT_FILE_INFO', 'Instructions');
    define('LABEL_ASSESSMENT_TYPE', 'Evaluation type');
    define('LABEL_ASSESSMENT_TYPE_1', 'Formative');
    define('LABEL_ASSESSMENT_TYPE_2', 'Certification');
    define('LABEL_ASSESSMENT_TYPE_3', 'Diagnosis');
    define('LABEL_ASSESSMENT_TYPE_4', 'Summative');
    define('LABEL_PRESS_CTRL_SELECT', 'Press <kbd>CTRL</kbd> for multiple selection');
    define('LABEL_SKILLS_SEEN', 'Knowledge (Students will be able to:)');
    define('LABEL_UPLOAD_INSTRUCTIONS', 'Upload instructions');
    define('LABEL_ONLY_PDF_ALLOWED', 'PDF only');
    define('LABEL_CRITERIA', 'Criteria');
    define('LABEL_CRITERION', 'Criterion');
    define('LABEL_SKILLS_GROUP', 'Objectives');
    define('LABEL_SKILL', 'Objective');
    define('LABEL_COEFFICIENT', 'Maximum');
    define('LABEL_ASSESSMENT_GRID', 'Assessment Grid');
    define('LABEL_CURSORS', 'Evidences (the student is able to:)');
    define('LABEL_CURSOR', 'Evidence (the student  is able to:)');
    define('LABEL_CLASS', 'Grade / Group');
    define('LABEL_DEADLINE', 'Deadline');
    define('LABEL_NEW_CRITERION', 'New criterion');
    define('LABEL_ADD_CRITERION', 'Add criterion'); // Mind the first space!
    define('LABEL_ADD_CURSOR', 'Add cursor'); // Mind the first space!
    define('LABEL_DEL_CURSOR', 'Delete cursor'); // Mind the first space!
    define('LABEL_SELF_ASSESSMENT', 'Self assessment');
    define('LABEL_ADD_QUESTION', 'Add Question'); // Mind the first space!
    define('LABEL_SAVE_PROJECT', 'Save project');
    define('LABEL_DEL_PROJECT', 'Delete Project'); // Mind the first space!
    define('LABEL_PROJECT_SAVED', 'Project Saved!');
    define('LABEL_EXPECTED_FILE', ' Expected file extension');
    define('LABEL_NO_EXPECTED_FILE', 'No file requested');
    define('LABEL_HOW_MANY_FILES', 'Number of requested files');
    define('LABEL_DELETE', 'Delete');
    define('LABEL_PROJECT_UPDATED', 'Project updated!');
    
    // Form control
    define('LABEL_REQUIRED_NAME', 'Name required');
    define('LABEL_REQUIRED_DATE', 'Deadline required');
    define('LABEL_UNVALID_DATE', 'Invalid Date');
    define('LABEL_FORM_NOT_COMPLETE', 'Form not complete');
    
    // Grading
    define('LABEL_NO_HTML5_VIDEO', 'It appears that your browser does not support HTML5 video but video can be downloaded.');
    define('LABEL_HERE', 'here');
    define('LABEL_RATING', 'Grade');
    define('LABEL_SAVE_RATING', 'Save assessment'); // Mind the first space!
    define('LABEL_NOT_SUBMITTED', 'Work not submitted');
    define('LABEL_EVERY_CLASSES', 'Every grades');
    define('LABEL_NO_AVAILABLE_RESULTS', 'Result no available.');
    define('LABEL_SUBMITTED_ON', 'Submitted on: ');
    define('ASSESSMENT', 'Assessment');
    define('LABEL_COMMENT', 'Comment');
            
    define('LABEL_VOTE_00', 'FX');
    define('LABEL_VOTE_01', 'F');
    define('LABEL_VOTE_02', 'F');
    define('LABEL_VOTE_03', 'F');
    define('LABEL_VOTE_04', 'F');
    define('LABEL_VOTE_05', 'E');
    define('LABEL_VOTE_06', 'D');
    define('LABEL_VOTE_07', 'C');
    define('LABEL_VOTE_08', 'B');
    define('LABEL_VOTE_09', 'A');
    define('LABEL_VOTE_10', 'A+');
    define('LABEL_ADMIN_SKILLS', 'Knowledge management');
    define('LABEL_ADMIN_USERS', 'User management');
  
    // settings
    define('LABEL_CONFIG_WELCOME', 'Welcome message');
    define('LABEL_MAIL_TEST', 'Test mail sending');
    define('LABEL_SYSTEM', 'System');
    define('LABEL_SUBJECT', 'Subject');
    define('LABEL_MESSAGE', 'Body message');
    define('LABEL_ITS_A_TEST', 'This is a test');
    define('LABEL_FREE_DISK_SPACE', 'Free disk space');
    define('LABEL_SEND_ME_A_MAIL', 'Send me a mail');
    define('LABEL_USED', 'Used');
    define('LABEL_FREE', 'Free');
    define('LABEL_SENT_MAIL', 'We have just sent you a mail!');
?>
