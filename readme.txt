ccms installation manual:

installing in root:
1- open c_config.php file with a text editor
2- change values for

date_default_timezone_set
DB_NAME
DB_LOC
DB_USER
DB_PASS

save the file.
3- open ccms installer at c_install/
4- follow instructions.

installing in sub directiory:
1- open c_config.php file with a text editor
2- change values for

SUBDIR

date_default_timezone_set
DB_NAME
DB_LOC
DB_USER
DB_PASS

save the file.
4- rename .htaccess to something else. like "old.htaccess.file"
5- rename "sub_htaccess.txt" to ".htaccess" and open it with a text editor
6- in lines 4 and 7 edit 'c' with your sub directory name(s)
7- save the file
3- open ccms installer at c_install/
4- follow instructions.