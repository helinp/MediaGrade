# MediaGrade BETA
**CS50x 2015 Final Project**

This is an education web application.

Created to facilitate assessement of media projects as photo, video or audio files
this application is optimized for the belgian high-school assessment system but it is intended to be internationalized as much as possible. 

As a belgian teacher of video and photo production, I wanted to set up a platform to enhance teacher - students and to ease rating of submitted work, add some statistics, facilitate self assessment and exibit students work.

Students can upload requested projects, access instructions, get assessement and view others projects submissions.
Further features will show up as it still a Work in Progress...

Previews:

- [Youtube features presentation](https://www.youtube.com/watch?v=-Toms9O7ZUM)
- [Demo version](http://mg.pierrehelin.eu)


## Requirements
- PHP5 & SQL
- Disk space, likely a lot, depending on the number of students and the projects size
- Access to php.ini to change "upload_max_filesize" and "post_max_size" to 200M


## Current functionalities
- Register and update students,
- Add generic skills (belgian system)
- Create projects, disactivate them, give an assessment grid (objective, criterion, cursor) and compose self assessment questions
- Evaluate students work


## TODO

- PATCH / Severe code optimisation required (as: too many queries, redudancy) on public/project.php.
- PATCH / Format new submission mail alert
- NEW   / User cannot review self-assessment answers
- PATCH / Prevent from duplicate project name
- PATCH / Users have to reupload PDF when project is updated
- PATCH / Add audio files display 
- PATCH / Remove hardcoded data
- PATCH / Format displayed deadline date

## FUTURE IMPLEMENTATIONS

- Historic of submitted works
- Results PDF export (by student, project and class)
- Fixed aside menu (CSS)
- Add users from CSV file
- Statistics!
- Multi teachers support
- Clean database and files
- Function to sort tables


## CHANGE LOG

**2016-02-24**
- Updated project submenu disposition for clearer view
- Added mail sending test page
- Added disk space monitor page
- Added format_byte function on functions.php

**2016-02-20**
- Maximum vote now saved in database (projects.php)
- Number of entries in assessment table reduced 

**2016-02-19**
- Fixed bug: add user button is now submit in adduser template
- Added sanitize function that converts accentued letters too
- Fixed bug: removed hardcoded url on gallery.php
- upload directories are now ordered by schoolyear 
- Hall of fame is now public
- normalized thumbnails height on gallery.php

**2016-02-12**
- Shows self-assessment answers on submit page
- Sorted projects menu (projects.periode DESC, submitted.time ASC)
- Notification mail goes to the teacher's mail
