<!DOCTYPE html>
<?php
session_start();
$con = mysqli_connect("localhost","root","","social");

if(mysqli_connect_errno())
{
	echo "Falied to Connect".mysqli_connect_errno();
}

//create variables too prevent erros

$fname = "";
$lname = "";
$em ="";
$em2 = "";
$password = "";
$password2="";
$date = "";
$error_array=array();

//if button is pressed  start having the form
if(isset($_POST['register_button']))
{
	$fname = strip_tags($_POST['reg_fname']); //remove the HTML tags
	$fname = str_replace('','', $fname); // remove the spaces
	$fname = ucfirst(strtolower($fname)); // convert the first name into capitalize
	$_SESSION['reg_fname'] = $fname;


	$lname = strip_tags($_POST['reg_lname']); //remove the HTML tags
	$lname = str_replace('', '',$lname); // remove the spaces
	$lname = ucfirst(strtolower($lname)); // convert the last name into capitalize
	$_SESSION['reg_lname'] = $lname;


	$em = strip_tags($_POST['reg_email']); //remove the HTML tags
	$em = str_replace('','', $em); // remove the spaces
	$em = ucfirst(strtolower($em)); // convert the email into capitalize
	$_SESSION['reg_email'] = $em;


	$em2 = strip_tags($_POST['reg_email2']); //remove the HTML tags
	$em2 = str_replace('','', $em2); // remove the spaces
	$em2 = ucfirst(strtolower($em2));
	$_SESSION['reg_email2'] = $em2;


	$password = strip_tags($_POST['reg_password']); //remove the HTML tags
	$password2 = strip_tags($_POST['reg_password2']);

	$date = date("Y-m-d");

	if($em == $em2)
	{
		//valid email

		if(filter_var($em, FILTER_VALIDATE_EMAIL))
		{
			$em = filter_var($em,FILTER_VALIDATE_EMAIL);

			//check if email exists aldready
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email = '$em'");

			//count the number of rows returned
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows > 0)
			{
				array_push($error_array, "Email Aldready in use<br>");
			}

		}
		else
		{
			array_push($error_array," Invalid Format<br>");
		}

	}
	else
	{
		array_push($error_array,"Emails don't match<br>");
	}


	if(strlen($fname) >25|| strlen($fname) <=2)
	{
		array_push($error_array,"First Name should be between 2 and 25 characters in length<br>");
	}
	if(strlen($lname) >25|| strlen($lname) <=2)
	{
		array_push($error_array,"Last name should be between 2 and 25 characters in length<br>");
	}
	if($password != $password2)
	{
		array_push($error_array,"Passwords DO NOT match<br>");
	}
	else
	{
		if(preg_match('/[^A-Za-z0-9]/', $password))
		{
			array_push($error_array,"Your passwords can only contains numbers & alphabets<br>");
		}
	}
	if(strlen($password > 30 || strlen($password) < 5))
	{
		array_push($error_array, "Passwords should be greater than 5 and less than 30 characters<br>");
	}

	if(empty($error_array))
	{
		$password = md5($password); //encrypt the password before sending to the data base

		//generate the user name by concatenating the first & last name
		$username = strtolower($fname."_".$lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

		$i = 0;
		//if user name exists, add the number to the username
		while(mysqli_num_rows($check_username_query)!= 0)
		{
			$i = $i+1; //add one to i
			$username = $username."_".$i;
			$check_username_query= mysqli_query($con, "SELECT username from users WHERE username = '$username'");
		}

		//profile picture assignment
		$rand = rand(1,2); //random number between a&2
		if($rand == 1)
{
		$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
	}
	else if($rand == 2)
	{
		$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
	}

	$query = mysqli_query($con , "INSERT INTO users VALUES ('','$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',')");

	array_push($error_array, "<span style = 'color : #14C800'> Youre all set ! Go Ahead and login!!</span><br>");

	//clear session variables
	$_SESSION['reg_fname'] = "";
	$_SESSION['reg_lname'] = "";
	$_SESSION['reg_email'] = "";
	$_SESSION['reg_email2'] = "";
}
}
?>



<html>
<head>
	<title>Welcome to the SwirlFeed</title>
</head>
<body>
	<form action = "register.php" method="POST">
		<input type = "text" name = "reg_fname" placeholder="First Name" value = "<?php if(isset($_SESSION['reg_fname']))
		{
			echo $_SESSION['reg_fname'];
		} ?>" required autocomplete="off">
		<br>
		<?php if(in_array("First Name should be between 2 and 25 characters in length<br>", $error_array))echo "First Name should be between 2 and 25 characters in length<br>"?>;

		<input type="text" name ="reg_lname" placeholder="Last Name" value = "<?php if(isset($_SESSION['reg_lname']))
		{
			echo $_SESSION['reg_lname'];
		} ?>" required autocomplete="off">
		<br>


		<?php if(in_array("Last name should be between 2 and 25 characters in length<br>", $error_array))echo "Last Name should be between 2 and 25 characters in length<br>"?>;
		
		<input type = "email" name = "reg_email" placeholder="Email"value = "<?php if(isset($_SESSION['reg_email']))
		{
			echo $_SESSION['reg_email'];
		} ?>"  required autocomplete="off">
		<br>
		<?php if(in_array("Email Aldready in use<br>", $error_array))echo "Email aldready exists<br>";
		 else if(in_array("Invalid Format<br>", $error_array))echo "Invalid Format<br>";
		 else if (in_array("Emails don't match<br>", $error_array))echo "Emails don't match<br>";?>

		<input type="email" name = "reg_email2" placeholder="Confirm Email"value = "<?php if(isset($_SESSION['reg_email2']))
		{
			echo $_SESSION['reg_email2'];
		} ?>"  required autocomplete="off">
		<br>


		<input type = "password" name = "reg_password" placeholder="password" required autocomplete="off">
		<br>

		<input type="password" name = "reg_password2" placeholder="confirm password" required autocomplete="off">
		<br>

		<?php if(in_array("Passwords DO NOT match<br>", $error_array))echo "Passwords DO NOT match<br>";
		 else if(in_array("Your passwords can only contains numbers & alphabets<br>", $error_array))echo "Your passwords can only contains numbers & alphabets<br>";
		 else if (in_array("Passwords should be greater than 5 and less than 30 characters<br>", $error_array))echo "Passwords should be greater than 5 and less than 30 characters<br><br>";?>

		<input type="submit" name="register_button" value="Register">
		<br>

		<?php if(in_array("<span style = 'color : #14C800'> Youre all set ! Go Ahead and login!!</span><br>", $error_array)) ;echo "<span style = 'color : #14C800'> Youre all set ! Go Ahead and login!!</span><br>";?>



	</form>

</body>
</html>
