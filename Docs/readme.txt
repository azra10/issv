

README.TXT

OS X has 2 web document roots ‘/Library/WebServer/Documents’ and ‘/Users/username/Sites/’ also known as ‘~/Sites’ this guide uses  ‘~/Sites’.

A link  ‘/Library/WebServer/Documents/lwp’  points to ‘/Users/azrafatima/sites/lwp’ where the actual wordpress code exists

 link  ‘/Library/WebServer/Documents/prod’  points to ‘/Users/azrafatima/sites/prod’ where the actual wordpress production code exists

‘/Library/WebServer/Documents/phpmyadmin’ has the mysql code.
————————————————


http://localhost/phpmyadmin/
username: root password: Password1

siteurl
http://localhost/lwp  or http://localhost/lwp/wp-login.php
username: admin  password: Password1

Username wpuser
Password Password1


1. Activate ISS Roles plugin
    (admin, board, teacher, parent and student roles created. Admin account receives )
2. Activate ISS Parent/Student Management  
     (Parents & Students menu options are not shown because the role is not activated and assigned to the user)
3. Activate ISS Admin Preferences
4. Activate ISS User Preferences
5. Activate ISS Export to CSV
6. Activate ISS Import From CSV
7. Activate ISS Registration For Next Year

999. Activate ISS Unit Test Plugin


—Loading Data
make sure the birth date is in yyyy-mm-dd, all amounts do not have a ‘,’ comma,
remove any ‘&’ or ‘$’ from data columns
format and save the file in comma separated file before importing into the database

--Loading custom css in theme page template
copy wp-content/themes/<name>/page.php to regpage.php to theme folder
add add_action ( 'wp_head', 'load_custom_issv_style' );  before header part.


create a page 'register' with registration page template
Add a shortcode [issv_edit_parent] to page body
