<?php #HELPER FUNCTION TO CONNECT TO DATABASE ASSOCIATED WITH WEBSITE

#connect with link to database hosted on university servers
$link = mysqli_connect('132.145.18.222', 'omp2000', 'wnd4VKSANY3', 'omp2000');


if (!$link) 
{ 
    #If the connection does not work then display an error message
    die('Could not connect to MySQL: ' . mysqli_connect_error()); 
} 
?>
