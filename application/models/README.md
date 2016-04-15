# MediaGrade BETA

This is an education web application.

Created to facilitate assessement of media projects as photo, video or audio files
this application is optimized for the belgian high-school assessment system.

As a belgian teacher of video and photo production, I wanted to set up a platform to enhance teacher - students and to ease rating of submitted work, add some statistics, facilitate self assessment and exibit students work.

Students can upload requested projects, access instructions, get assessement and view others projects submissions.
Further features will show up as it still a Work in Progress...

Previews:

- [Youtube features presentation](https://www.youtube.com/watch?v=-Toms9O7ZUM)
- [Demo version](http://mg.pierrehelin.eu)


## Current functionalities
- Register and update students,
- Add generic skills (belgian system)
- Create projects, disactivate them, give an assessment grid (objective, criterion, cursor) and compose self assessment questions
- Evaluate students work

# Movies Advisor
- Advise and vote for a movie picture

## Requirements
- PHP5 & SQL
- Disk space, likely a lot, depending on the number of students and the projects size
- Access to php.ini to change "upload_max_filesize" and "post_max_size" to 200M

## MediaGrade is using:
- CodeIgniter 3.0.6
- Bootstrap 3
- TCPDF Class
- MyAPIFilms 

## TODO
- TODO / User mail preference not yet implemented
- TODO / User password modification not yet implemented
- PATCH / Prevent from duplicate project name


## FUTURE IMPLEMENTATIONS

- Historic of submitted works
- Fixed aside menu (CSS)
- Add users from CSV file
- Statistics!
- Multi teachers support
- Audio and Doc files submission 


## CHANGE LOG

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

