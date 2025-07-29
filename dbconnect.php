<?php
    $conn = mysqli_connect("localhost","root","","project");
    if(!$conn)
    {
        die("Database is not connected". mysqli_connect_error());
    }
?>