<?php
/*
 * Created on 03.05.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * htpasswd
 * Usage:
        htpasswd [-cmdpsD] passwordfile username
        htpasswd -b[cmdpsD] passwordfile username password

        htpasswd -n[mdps] username
        htpasswd -nb[mdps] username password
 -c  Create a new file.
 -n  Don't update file; display results on stdout.
 -m  Force MD5 encryption of the password (default).
 -d  Force CRYPT encryption of the password.
 -p  Do not encrypt the password (plaintext).
 -s  Force SHA encryption of the password.
 -b  Use the password from the command line rather than prompting for it.
 -D  Delete the specified user.
On Windows, NetWare and TPF systems the '-m' flag is used by default.
On all other systems, the '-p' flag will probably not work.

 */
 
include('../../cfg/config.inc.php');

//create file
fopen($htpasswdfilename,'w') or die("Could not create file $htpasswdfilename");
foreach ($htpasswduser as $user => $pass){
  system("$htpasswd -bs  $htpasswdfilename $user $pass");
}
?>
