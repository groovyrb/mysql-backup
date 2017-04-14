<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript"> window.onload = closeWindow(); </script>
<?php
$host ="localhost";
$name ="databasename";
$pass ="password";
$user ="root";
$tables = '*';

/* for error check up uncomment below lines*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(-1) ;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);

	$con=mysqli_connect($host,$user,$pass,$name);
	// Check connection
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	/* Fetching all tables from database */
	$sql="SHOW TABLES FROM ".$name;

	if ($result=mysqli_query($con,$sql))
	  {		$tables = array();
			while ($row=mysqli_fetch_row($result))
			{
				$tables[] = $row[0];
		
			}
			
			 mysqli_free_result($result);
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	/* datatable ends here*/
	
	$return = '';
	/*Fetching all table data from */
	foreach($tables as $table)
    {	
		$result1 ='SELECT * FROM '.$table;
		$result1=mysqli_query($con,$result1);
        $num_fields = mysqli_num_fields($result1);
       
       //$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
        $return.'='. 'DROP TABLE '.$table.';';
			$create = 'SHOW CREATE TABLE '.$table ;
			$create = mysqli_query($con,$create);
			$row2=mysqli_fetch_row($create) ;
			
        $return.= "\n\n".$row2[1].";\n\n";
        
		for ($i = 0; $i < $num_fields; $i++) 
        {
            while($row = mysqli_fetch_row($result1))
            {
                $return.= 'INSERT INTO '.$table.' VALUES(';
                for($j=0; $j<$num_fields; $j++) 
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
                    
	}
	// sav file to self folder
	$handle = fopen($name.'-'.date("Y-m-d-H-i-s-A").'.sql','w+');
    fwrite($handle,$return);
    fclose($handle);

    echo "<h3>Thank you</h3>";
 	echo "This window will close in next 5 seconds.";
	echo "Thanks Database backup done on ".date("Y-m-d-H-i-s-A");
	echo "<script> close();</script>";
	mysqli_close($con);
?>

<script type="text/javascript">
function closeWindow() {
setTimeout(function() {
window.close();
}, 5000);
}
</script>
