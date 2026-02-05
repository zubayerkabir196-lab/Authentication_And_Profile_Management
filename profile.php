

<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $hashedPassword, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name;
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>
  <!-- Removed Matcha CSS -->
  <!-- Added Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-10">
  <div class="flex justify-center">
    <div class="w-full max-w-2xl">
      <div class="bg-white shadow rounded-lg">
        <div class="border-b px-6 py-4 text-center">
          <h3 class="text-xl font-semibold">My Profile</h3>
        </div>
        <div class="p-6">
          <?php if(isset($success)) echo "<div class='bg-green-100 text-green-700 p-3 rounded mb-4'>$success</div>"; ?>
          <?php if(isset($error)) echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>$error</div>"; ?>
          
          <form method="POST" action="">
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2">Full Name</label>
              <input type="text" name="name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2">Email</label>
              <input type="email" name="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2">New Password (optional)</label>
              <input type="password" name="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" minlength="6">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Update Profile</button>
          </form>
          
          <p class="mt-4 text-center">
            <a href="logout.php" class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Logout</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>