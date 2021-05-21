<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $email = $contact = $course = "";
$name_err = $email_err = $contact_err = $course_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter a valid email ";     
    } 
    // check if e-mail address is well-formed
    if (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Invalid email format";
    }
    else{
        $email = $input_email;
    }
  
    // Validate contact
    $input_contact = trim($_POST["contact"]);
    if(empty($input_contact)){
        $contact_err = "Please enter a valid contact number.";     
    }
    if(!filter_var($input_contact, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9]+$/")))){
        $name_err = "Please enter a valid number.";
    }
    if(strlen($input_contact)< 10 || strlen($input_contact)> 10){
        $contact_err = "Please enter a valid contact number.";     
    }
    else{
        $contact = $input_contact;
    }
    
    // Validate course
    $input_course = trim($_POST["course"]);
    if(empty($input_course)){
        $course_err = "Please enter the valid course .";     
    } else{
        $course = $input_course;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($contact_err) && empty($course_err)&& empty($email_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Student (name,email,contact, course) VALUES (?, ?, ?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_email, $param_contact, $param_course);
            
            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_contact = $contact;
            $param_course = $course;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add student record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control  <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $email; ?>"></input>
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Contact</label>
                            <input type="text" name="contact"   class="form-control  <?php echo (!empty($contact_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $contact; ?>"></input>
                            <span class="invalid-feedback"><?php echo $contact_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Course</label>
                            <input type="text" name="course" class="form-control <?php echo (!empty($course_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $course; ?>">
                            <span class="invalid-feedback"><?php echo $course_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>