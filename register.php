<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-10">
  <div class="flex justify-center">
    <div class="w-full max-w-md">
      <div class="bg-white shadow rounded-lg">
        <div class="border-b px-6 py-4 text-center">
          <h3 class="text-xl font-semibold">Register</h3>
        </div>
        <div class="p-6">
          <?php if(isset($error)) echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>$error</div>"; ?>
          
          <form method="POST" action="">
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2">Full Name</label>
              <input type="text" name="name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2">Email</label>
              <input type="email" name="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2">Password</label>
              <input type="password" name="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required minlength="6">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Register</button>
          </form>
          
          <p class="mt-4 text-center text-gray-600">
            Already have an account? 
            <a href="login.php" class="text-blue-600 hover:underline">Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>




<?php
session_start();
include 'config.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into DB using prepared statement
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Email already exists!";
        }
    }
}
?>
</body>
</html>