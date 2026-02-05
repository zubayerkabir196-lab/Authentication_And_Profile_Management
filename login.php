<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-10">
  <div class="flex justify-center">
    <div class="w-full max-w-md">
      <div class="bg-white shadow rounded-lg">
        <div class="border-b px-6 py-4 text-center">
          <h3 class="text-xl font-semibold">Login</h3>
        </div>
        <div class="p-6">
          <?php if(isset($error)) echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>$error</div>"; ?>
          <?php if(isset($_SESSION['success'])) { echo "<div class='bg-green-100 text-green-700 p-3 rounded mb-4'>".$_SESSION['success']."</div>"; unset($_SESSION['success']); } ?>
          
          <form method="POST" action="">
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2">Email</label>
              <input type="email" name="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2">Password</label>
              <input type="password" name="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Login</button>
          </form>
          
          <p class="mt-4 text-center text-gray-600">
            Don’t have an account? 
            <a href="register.php" class="text-blue-600 hover:underline">Register</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>



<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: profile.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with that email!";
    }
}
?>

</body>
</html>