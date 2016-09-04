# MediaGrade BETA

This is an educational web application.

Specifically created to facilitate the assessment of multimedia students' projects (as photo, video and audio files), this application is designed to suit the belgian high-school system.

I am a Belgian teacher of video and photo production and I wanted to create a platform to ease both teacher and student experience.

Teachers can assess, comment student's work, manage projects, download vote grids and generate assessment sheets.

Students can upload requested projects, access instructions, get assessment details and view others' subbmitted projects.

Further features will show up in the future.

Previews:

- [Last version Demonstration (uploading and email functions are disactivated](http://mg.pierrehelin.eu)
- [Version 0.1 features presentation](https://www.youtube.com/watch?v=-Toms9O7ZUM)


## Current functionalities
### Teacher
- Register and update students' informations
- Add generic skills and group them (Belgian system)
- Manage projects (create, modify, delete)
- Make assessment grids (objective, criterion, cursor)
- Compose self assessment questions
- Grade students work and add comment

### Student
- Get projects' status
- See own submitted projects gallery
- See others students submitted multimedia projects
- Upload finished projects
- Get informed when your project is graded
- Get votes and teacher comments

### Both
- Choose email notification preferences
- Get an avatar (useless but fun!)
 

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

## TODO
- [ ] Code documentation
- [ ] Automated installation
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
- New logo!

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

## INSTALLATION INSTRUCTIONS
- Import mediagrade_min.sql to your database
- Set permission of /assets/upload on 777
- Edit /application/config/constants.php
- Edit /application/config/database.php
- Default login/password is teacher/123456


## LICENCE
AGPL v3
