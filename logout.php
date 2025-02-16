<?php
session_start();
session_destroy(); // Destroy all session data

echo "<script>
    alert('You have been logged out successfully!');
    window.location.href = 'admin_login.php';
</script>";
exit();
?>
