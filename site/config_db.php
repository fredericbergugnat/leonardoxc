<?

if ( $_SERVER['SERVER_NAME'] =="pgforum.home" ) {
	$dbhost = 'localhost';
	$dbname = 'pgforum2';
	$dbuser = 'pgforumftp';
	$dbpasswd = 'K7v#3E!2';
} else if ( $_SERVER['SERVER_NAME'] =='discuz.home'  ) {
	$dbhost = 'localhost';
	$dbname = 'discuz';
	$dbuser = 'root';
	$dbpasswd = '321ox';
} else {
	$dbhost = 'localhost';
	$dbname = 'paraglidingforum';
	$dbuser = 'pgforumftp';
	$dbpasswd = 'K7v#3E!2';
}


?>