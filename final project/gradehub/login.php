<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="shortcut icon" href="images/logo.jpg" type="">

  <title> IT Department </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  
  <style>
    time
    {
      float: left;
    }
  </style>

</head>

<body>

  <div class="hero_area">

    <div class="hero_bg_box">
      <div class="bg_img_box">
        <img src="images/hero-bg.png" alt="">
      </div>
    </div>

    <!-- header section strats -->
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <img src="images/logo.jpg">
            <span>
              IT Department
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  ">
              <li class="nav-item active">
                <a class="nav-link" href="index.html">Home<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="login.php">Courses</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.html">About Us</a>
              </li>
              <form class="form-inline">
                <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </form>
            </ul>
          </div>
        </nav>
      </div>
    </header>
  </body>
</html>


<?php
session_start();
// here is the connection to the database
$conn = mysqli_connect("localhost", "root", "", "student_marks");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Login Form
if (!isset($_SESSION['username'])) {
  echo "
  <form class='container bg-light p-4 rounded' method='post' action='login.php'>
      <div class='form-group'>
          <h3 class='text-center'>Log In</h3>
      </div>
      <div class='form-group'>
          <label for='username'>Username:</label>
          <input type='text' class='form-control' id='username' name='username' placeholder='Enter your username' required>
      </div>
      <div class='form-group'>
          <label for='password'>Password:</label>
          <input type='password' class='form-control' id='password' name='password' placeholder='Enter your password' required>
      </div>
      <button type='submit' name='login' class='btn btn-primary btn-block'>Login</button>
  </form>
  ";
  

    // Login Process
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM students WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            header("Location: login.php");
        } else {
            echo "Invalid username or password.";
        }
    }
} else {
    // Display the student name
    echo "<div style='position: absolute; top: 100px; left: 20px;'>";
    echo "<strong><h2 style='color: white;'>Welcome, {$_SESSION['username']}</strong>!</h2>";

    echo "</div>";




    // Fetch Courses from Database
    $sql_courses_marks = "SELECT marks.course, marks.mark FROM marks
                      INNER JOIN students ON students.id = marks.student_id 
                      WHERE students.username='{$_SESSION['username']}'";
$result_courses_marks = mysqli_query($conn, $sql_courses_marks);

if (mysqli_num_rows($result_courses_marks) > 0) {
  // Table if you want to modify 
  echo "
<div class='container mt-5 mb-4'> <!-- Added mt-5 (margin-top) for moving the table down -->
    <h3 class='text-center'><strong><h2 style='color: white;'>Courses, Grades:</h2></strong></h3>
    <div class='table-responsive d-flex justify-content-center'>
        <table class='table table-striped table-bordered' style='width: 70%; background-color: #f8f9fa;'>
            <thead class='thead-dark'>
                <tr>
                    <th class='text-center'>Course</th>
                    <th class='text-center'>Grade</th>
                     <!-- Added a new column for actual marks -->
                </tr>
            </thead>
            <tbody>
";

while ($row = mysqli_fetch_assoc($result_courses_marks)) {
    $grade = '';
    $actual_mark = $row['mark']; // Store the actual mark in a variable

    // Assign grades based on marks
    if ($actual_mark >= 95) {
        $grade = 'A+';
    } elseif ($actual_mark >= 90) {
        $grade = 'A';
    } elseif ($actual_mark >= 85) {
        $grade = 'B+';
    } elseif ($actual_mark >= 80) {
        $grade = 'B';
    } elseif ($actual_mark >= 75) {
        $grade = 'C+';
    } elseif ($actual_mark >= 70) {
        $grade = 'C';
    } elseif ($actual_mark >= 65) {
        $grade = 'D+';
    } elseif ($actual_mark >= 60) {
        $grade = 'D';
    } else {
        $grade = 'F';
    }

    echo "<tr><td>{$row['course']}</td><td>{$grade}</td></tr>"; // Added the actual mark in a new column
}

echo "
            </tbody>
        </table>
    </div>
</div>
";
  
} else {
    echo "No courses and marks found.";
}


     // Logout Button
     echo "
     <div class='fixed-bottom container-fluid' style='margin-bottom: 170px;'>
         <div class='row justify-content-center'>
             <div class='col-auto'>
                 <a href='logout.php' class='btn btn-danger'>Logout</a>
             </div>
         </div>
     </div>";
     

}

 


mysqli_close($conn);



// table only marks
/* 
  if (mysqli_num_rows($result_courses_marks) > 0) {
  // Table
  echo "
<div class='container mt-5 mb-4'> <!-- Added mt-5 (margin-top) for moving the table down -->
    <h3 class='text-center'><strong>Courses and Marks:</strong></h3>
    <div class='table-responsive d-flex justify-content-center'>
        <table class='table table-striped table-bordered' style='width: 70%; background-color: #f8f9fa;'>
            <thead class='thead-dark'>
                <tr>
                    <th class='text-center'>Course</th>
                    <th class='text-center'>Mark</th>
                </tr>
            </thead>
            <tbody>
";

while ($row = mysqli_fetch_assoc($result_courses_marks)) {
    echo "<tr><td>{$row['course']}</td><td>{$row['mark']}</td></tr>";
}

echo "
            </tbody>
        </table>
    </div>
</div>
";
  
}

*/

?>

