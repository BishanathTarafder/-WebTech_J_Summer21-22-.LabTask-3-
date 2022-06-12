<?php 

	$fname = $lname = "";
	$firstnameErrMsg = "";
	$adressErrMsg = "";

	if ($_SERVER['REQUEST_METHOD'] === "POST") 
	{

		function test_input($data) 
		{
			$data = trim($data);
			$data = stripcslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		$fname = $_POST['fn'];
		$lname = $_POST['ln'];

		$gender = isset($_POST['gender']) ? test_input($_POST['gender']) : NULL;

		$email = $_POST['e'];
		$mobilenumber = $_POST['mn'];
        $shr = $_POST['shr']; 
		$country = $_POST['c'];

		$op = $_POST['op'];

		$count = 0;
		$message = "";
		if (empty($fname))
	    {

			$firstnameErrMsg = "First Name is Empty";
			$message .= "First Name is Empty";
			$message .= "<br>";
		}
		else 
		{
			if (!preg_match("/^[a-zA-Z-' ]*$/",$fname)) 
			{
				$firstnameErrMsg = "Only letters and spaces";
			}
			else
			{
				$count++;
			}
		}
		if (empty($lname))
		{

			$firstnameErrMsg = "Last Name is Empty";
			$message .= "Last Name is Empty";
			$message .= "<br>";
		}
		else 
		{
			if (!preg_match("/^[a-zA-Z-' ]*$/",$lname)) 
			{
				$firstnameErrMsg = "Only letters and spaces";
			}
			else
			{
				$count++;
			}
		}

		if (!isset($_POST['gender'])) 
		{
			$message .= "Gender not Selected";
			$message .= "<br>";
		}
		else
		{
			$count++;
		}

		if (empty($email))
	    {
			$message .= "Email is Empty";
			$message .= "<br>";
		}
		else 
		{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$message .= "Please correct your email";
				$message .= "<br>";
			}
			else
			{
				$count++;
			}
		}
		if (empty($mobilenumber))
		{
			$message .= "Mobileno is Empty";
			$message .= "<br>";
		}
		else
		{
			if(!preg_match('/^[0-9]{11}+$/', $mobilenumber)) 
		    {
		    	$message .= "Invalid Phone Number";
		    	$message .= "<br>";

		    } 
		    else
			{
				$count++;
			}
		}


		if (empty($shr)) 
		{
			$message .= "Street/House/Road is Empty";
			$message .= "<br>";
		}
		else 
		{
			if(preg_match("/^[a-z0-9 ,#-'\/]{3,50}$/i", $shr) === 0 )
			{
			    $adressErrMsg = "Address is wrong";
			    $message .= "Address is wrong";
			    $message .= "<br>";
			}
			else
			{
				$count++;
			}
		}

		if($_POST['c'] === "none")
		{
			$message .= "Country not Seletect";
			$message .= "<br>";
		}
		else
		{
			$count++;
		}


		if ($message === "") 
		{
			echo "Operation Successful";
		}
		else 
		{
			echo $message;
		}
        if($_POST['op'] === "update")
		{
			$data = file_get_contents('data.json');

			$json_arr = json_decode($data, true);

			foreach ($json_arr as $key => $value) 
			{
			    if ($value['fn'] == $fname && $value['ln'] == $lname) 
			   	{
			   		$json_arr[$key]['fn'] = $fname;
			   		$json_arr[$key]['ln'] = $lname;
			   		$json_arr[$key]['gender'] = $gender;
			   		$json_arr[$key]['e'] = $email;
			   		$json_arr[$key]['mn'] = $mobilenumber;
			   		$json_arr[$key]['shr'] = $shr;
			   		$json_arr[$key]['c'] = $country;
		        }
			}
            file_put_contents('data.json', json_encode($json_arr));


		}
		else if($_POST['op'] === "delete")
		{
			$data = file_get_contents('data.json');
			$json_arr = json_decode($data, true);

			$arr_index = array();

			foreach ($json_arr as $key => $value) 
			{
			    if ($value['fn'] == $fname && $value['ln'] == $lname) 
			    {
			            $arr_index[] = $key;
			    }
            }

            foreach ($arr_index as $i) 
            {
                unset($json_arr[$i]);
            }

            $json_arr = array_values($json_arr);
            file_put_contents('data.json', json_encode($json_arr));
		}
		else if($_POST['op'] === "show")
		{
			$f2 = fopen("data.json", 'r');

        	$s = fread($f2, filesize("data.json"));

	        $data = json_decode($s);

	        echo "<hr><hr>";


	        echo "<table border=1>";
	        echo "<tr>";
	        echo "<th>Firstname</th>";
	        echo "<th>LastName</th>";
	        echo "<th>Gender</th>";
	        echo "<th>Email</th>";
	        echo "<th>Mobileno</th>";
	        echo "<th>Street/House/Road</th>";
	        echo "<th>country</th>";
	        echo "</tr>";

	        foreach ($data as $data)
	        {
	        	echo "<tr>";
	            echo "<td>" . $data->fn . "</td>";
	            echo "<td>" . $data->ln . "</td>";
	            echo "<td>" . $data->gender . "</td>";
	            echo "<td>" . $data->e . "</td>";
	            echo "<td>" . $data->mn . "</td>";
    	        echo "<td>" . $data->shr . "</td>";
	            echo "<td>" . $data->c . "</td>";
	            echo "</tr>";
	        }
	        
	        echo "</table>";
	

	        fclose($f2);
		}
		else if($count === 7 && $_POST['op'] === "insert") 
		{
			class obj
			{
			    public $fn;
			    public $ln;
			    public $gender;
			    public $e;
			    public $mn;
			    public $shr;
			    public $c;
			}

			$obj1 = new obj();
			$obj1->fn=$fname;
			$obj1->ln=$lname;
			$obj1->gender=$gender;
			$obj1->e=$email;
			$obj1->mn=$mobilenumber;
			$obj1->shr=$shr;
			$obj1->c=$country;



	     	 $file = file_get_contents('data.json', true);
	         $data = json_decode($file,true);
	         unset($file);

	         $data[] = $obj1;

	         $result=json_encode($data);
	         file_put_contents('data.json', $result);
	         unset($result);
	        

		}


	}
?>
