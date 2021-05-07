<?php
/*try
{
	$connection = ssh2_connect("10.169.7.129", 22);
ssh2_auth_password($connection, 'infor', 'Servinfo0');

$sftp = ssh2_sftp($connection);

$stream = fopen('ssh2.sftp://' . intval($sftp) . '/cyrus/sm96/archive/remontee/', 'r');
echo "good";
}
catch (Exception $e) {
    echo $e->getMessage() . "\n";
}*/
/*try {
    $sftp = new SFTPConnection("10.169.7.129", 22);
    $sftp->login("infor", "Servinfo0");
    $sftp->uploadFile("/cyrus/sm96/archive/remontee/", "/Users/file.txt");
    echo connecté;
}
catch (Exception $e) {
    echo $e->getMessage() . "\n";
}*/

/* connexion et ouverture de dossier veneant d'un serveur (sftp)*/
$connection = ssh2_connect("10.169.7.129", 22);
ssh2_auth_password($connection, 'infor', 'Servinfo0');

$sftp = ssh2_sftp($connection);
$sftp_fd = intval($sftp);

$handle = opendir("ssh2.sftp://$sftp_fd//cyrus/sm50/archive/remontee/");
echo "Directory handle: $handle\n";
echo "Entries:\n";
while (false != ($entry = readdir($handle))){
    echo "$entry\n</br>";
}
/*
$file = fopen ("ftp://infor:Servinfo0@10.169.7.129/fichier.txt/cyrus/sm50/archive/remontee/vtefam_maki_20180125.DAT", "r");
 
while (!feof($file))
{
    $page .= fgets($file, 4096);
}
     
echo $page;

/* telechargement d'un fichier via SCP

$connection = ssh2_connect('10.169.7.129', 22);
ssh2_auth_password($connection, 'infor', 'Servinfo0');

ssh2_scp_recv($connection, '/remote/filename', '/local/filename');
*/

/* deconnexion
ssh2_disconnect ($connection);