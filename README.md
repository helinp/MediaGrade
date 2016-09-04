# MediaGrade BETA

This is an education web application.

Created to facilitate assessment of media projects as photo, video or audio files
this application is optimized for the Belgian high-school assessment system.

As a Belgian teacher of video and photo production, I wanted to set up a platform to enhance teacher - students and to ease rating of submitted work, add some statistics, facilitate self assessment and exhibit students work.

Students can upload requested projects, access instructions, get assessment and view others projects submissions.
Further features will show up as it still a Work in Progress...

Previews:

- [Youtube features presentation](https://www.youtube.com/watch?v=-Toms9O7ZUM)
- [Demo version 1.0 BETA](http://mg.pierrehelin.eu)


## Current functionalities
- Register and update students,
- Add generic skills (Belgian system)
- Manage projects
- Make assessment grids (objective, criterion, cursor)
- Compose self assessment questions
- Grade students work


## Requirements
- PHP5 & SQL
- Disk space, likely a lot, depending on the number of students and the projects size
- Access to php.ini to change "upload_max_filesize" and "post_max_size" to 200M

## MediaGrade is using:
- CodeIgniter 3.0.6
- Bootstrap 3
- TCPDF Class
- TinyMCE
- Twitter Typeahead
- Lightbox

## TODO
- [ ] Code documentation
- [x] TODO / User mail preference not yet implemented
- [x] TODO / User password modification not yet implemented
- [ ] PATCH / Prevent from duplicate project name
- [ ] Typeahead
- [ ] Form confirmation (CodeIgniter)


## FUTURE IMPLEMENTATIONS
- [x] Multi teachers support
- [x] Historic of submitted works
- [x] Fixed aside menu (CSS)
- [ ] Add users from CSV file
- [x] Statistics!
- [x] Multi teachers support
- [ ] Audio and Doc files submission
- [ ] Duplicate projects


## CHANGE LOG

**2016-08-26**
- Improved UX
- Multi-Admin
- Enhanced dashboard
- Statistical charts
- Code structure improvement (work in progress)
- Removed Movie Advisor (will become an independent project)
- Manages school years

**2016-06-01**
- Not submitted or projects without file are not anymore showed in gallery
- Gradebook graph is now functional
- Highchart js files are now local

**2016-05-04**
- Is it now possible not to grade one or few criteria
- Projects with no requested file doesn't show on gallery anymore BUGGED

**2016-04-24**
- Project with no file requested but only self-assessment is now implemented
- Minor debugging

**2016-04-15**
*Major Update*
- Now powered by CodeIgniter 3.0.6
- Object and MCV oriented
- Admin Dashboard
- Group of skills admin
- Movie Advisor addon
- Database updated
- Filters on Hall of fame page
- Users can have an avatar

## INSTALLATION

- Import mediagrade_min.sql to your database
- Set permission of /assets/upload on 777
- Edit /application/config/constants.php
- Edit /application/config/database.php
- Default login/password is teacher/123456


## LICENCE

AGPL v3
