<?php
include("database.php");

$message = '';
$message_type = '';

// HANDLE DIRECT PASSWORD CHANGE
if (isset($_POST['direct-change-password'])) {
    $search_user = trim($_POST['search_user']);
    $new_password = $_POST['new_password_direct'];
    $confirm_password = $_POST['confirm_password_direct'];
    
    if ($new_password === $confirm_password) {
        // SEARCH IN admin_accounts
        $admin_sql = "SELECT admin_id FROM admin_accounts WHERE username = '$search_user' OR email = '$search_user'";
        $admin_result = mysqli_query($conn, $admin_sql);
        
        // SEARCH IN tenant_accounts
        $tenant_sql = "SELECT tenant_id FROM tenant_accounts WHERE username = '$search_user' OR email = '$search_user'";
        $tenant_result = mysqli_query($conn, $tenant_sql);
        
        if (mysqli_num_rows($admin_result) > 0) {
            // UPDATE PASSWORD IN admin_accounts
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE admin_accounts SET password = '$hashed_password' WHERE username = '$search_user' OR email = '$search_user'";
            
            if (mysqli_query($conn, $update_sql)) {
                $message = "✅ Admin password reset successfully! You can now log in.";
                $message_type = 'success';
            } else {
                $message = "❌ Failed to reset admin password.";
                $message_type = 'error';
            }
        } elseif (mysqli_num_rows($tenant_result) > 0) {
            // UPDATE PASSWORD IN tenant_accounts
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE tenant_accounts SET password = '$hashed_password' WHERE username = '$search_user' OR email = '$search_user'";
            
            if (mysqli_query($conn, $update_sql)) {
                $message = "✅ Tenant password reset successfully! You can now log in.";
                $message_type = 'success';
            } else {
                $message = "❌ Failed to reset tenant password.";
                $message_type = 'error';
            }
        } else {
            $message = "❌ Account not found. Please check your username or email.";
            $message_type = 'error';
        }
    } else {
        $message = "⚠ New passwords don't match.";
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forgotpassword.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Forgot Password - UnitEase</title>
</head>
<body>
    <div class="navbar">
        <ul>
            <li class="nav-items">
                <a href="index.php" id="unitease"><span style="color: #62929E">U</span>nitEase <img src="images/logo-blue.png" alt="logo blue" style="width: min(40px, 5vw);"></a>
            </li>
            <li class="nav-items">
                <a href="contacts.php" id="contacts">Contacts</a>
            </li>
            <li class="nav-items">
                <a href="aboutus.php" id="aboutus">About Us</a>
            </li>
        </ul>
    </div>

    <div class="main-container">
        <div class="forgot-password-content">
            <img src="images/logo-blue.png" alt="logo blue" style="width: 60px; margin-bottom: 20px;">
            <h1>Forgot Your Password?</h1>
            <p>No worries! Enter your username or email below and we'll help you reset your password.</p>
            
            <button onclick="openDirectPasswordModal()" class="reset-btn">
                <i class="fas fa-key"></i>
                Reset My Password
            </button>
            
            <div class="back-to-login">
                <a href="login.php">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
        
    <!-- DIRECT PASSWORD CHANGE MODAL -->
    <div id="direct-password-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Reset Password</h2>
                <span class="close" onclick="closeDirectPasswordModal()">&times;</span>
            </div>
            <div class="modal-body">
                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $message_type; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <form id="direct-password-form" method="post">
                    <div class="form-group">
                        <label for="search_user">Find Your Account</label>
                        <div class="search-container">
                            <input type="text" id="search_user" name="search_user" placeholder="Enter username or email" required>
                            <button type="button" onclick="searchUser()" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="user-info" class="user-info" style="display: none;">
                        <div class="user-details">
                            <h3>Account Found:</h3>
                            <p><strong>Name:</strong> <span id="found-name"></span></p>
                            <p><strong>Username:</strong> <span id="found-username"></span></p>
                            <p><strong>Email:</strong> <span id="found-email"></span></p>
                            <p><strong>Account Type:</strong> <span id="found-type"></span></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password_direct">New Password</label>
                        <div class="password-input-container">
                            <input type="password" id="new_password_direct" name="new_password_direct" placeholder="Enter new password" required>
                            <i class="fas fa-eye-slash password-toggle" id="toggle-direct-new" onclick="togglePassword('new_password_direct', 'toggle-direct-new')"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password_direct">Confirm New Password</label>
                        <div class="password-input-container">
                            <input type="password" id="confirm_password_direct" name="confirm_password_direct" placeholder="Confirm new password" required>
                            <i class="fas fa-eye-slash password-toggle" id="toggle-direct-confirm" onclick="togglePassword('confirm_password_direct', 'toggle-direct-confirm')"></i>
                        </div>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" onclick="closeDirectPasswordModal()" class="btn-cancel">Cancel</button>
                        <button type="submit" name="direct-change-password" class="btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Password toggle function
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye password-toggle';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye-slash password-toggle';
            }
        }

        // Modal functions
        function openDirectPasswordModal() {
            document.getElementById('direct-password-modal').style.display = 'block';
            document.getElementById('search_user').focus();
        }

        function closeDirectPasswordModal() {
            document.getElementById('direct-password-modal').style.display = 'none';
            document.getElementById('direct-password-form').reset();
            document.getElementById('user-info').style.display = 'none';
        }

        // Search user function
        function searchUser() {
            const searchValue = document.getElementById('search_user').value.trim();
            
            if (searchValue === '') {
                alert('Please enter a username or email');
                return;
            }
            
            // Show loading state
            document.getElementById('user-info').style.display = 'block';
            document.getElementById('found-name').textContent = 'Searching...';
            document.getElementById('found-username').textContent = searchValue;
            document.getElementById('found-email').textContent = 'Searching...';
            document.getElementById('found-type').textContent = 'Searching...';
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'search_user.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.success) {
                            // DISPLAY REAL USER DATA
                            document.getElementById('found-name').textContent = response.data.full_name;
                            document.getElementById('found-username').textContent = response.data.username;
                            document.getElementById('found-email').textContent = response.data.email;
                            document.getElementById('found-type').textContent = response.data.account_type;
                        } else {
                            // USER NOT FOUND
                            document.getElementById('found-name').textContent = 'User Not Found';
                            document.getElementById('found-username').textContent = searchValue;
                            document.getElementById('found-email').textContent = 'Not Found';
                            document.getElementById('found-type').textContent = 'Not Found';
                            
                            // SHOW ERROR MESSAGE
                            alert('User not found. Please check your username or email.');
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        document.getElementById('found-name').textContent = 'Error';
                        document.getElementById('found-username').textContent = searchValue;
                        document.getElementById('found-email').textContent = 'Error';
                        document.getElementById('found-type').textContent = 'Error';
                    }
                }
            };
            
            xhr.send('search_user=' + encodeURIComponent(searchValue));
        }

        // CLOSE MODAL WHEN CLICKING OUTSIDE
        window.onclick = function(event) {
            const modal = document.getElementById('direct-password-modal');
            if (event.target === modal) {
                closeDirectPasswordModal();
            }
        }

        // CLOSE MODAL WITH ESCAPE KEY
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDirectPasswordModal();
            }
        });
    </script>
</body>
</html>