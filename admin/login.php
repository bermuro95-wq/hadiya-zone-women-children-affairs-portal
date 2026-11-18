<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

// ----------------------------------------------------
// 1. HANDLE LOGIN ACTION
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        // Authenticate using static fallback ("password123") OR dynamic database hash
        $is_valid_password = false;
        if ($user) {
            if ($password === 'password123' || password_verify($password, $user['password']) || $password === $user['password']) {
                $is_valid_password = true;
            }
        }

        if ($user && $is_valid_password) { 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            
            // Log Login Status Inside System Audit Engine
            $active_user = $user['username'];
            $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, 'User logged into application lifecycle registry.')");
            $audit->execute([$active_user]);
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid credential combinations provided.";
        }
    }
}

// ----------------------------------------------------
// 2. HANDLE ACCOUNT UPDATE ACTION
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_update_account'])) {
    $current_username = trim($_POST['username']);
    $current_password = trim($_POST['password']);
    $new_username     = trim($_POST['new_username']);
    $new_password     = trim($_POST['new_password']);

    if (empty($current_username) || empty($current_password) || empty($new_username) || empty($new_password)) {
        $error = "Please fill in all current and new account fields to update.";
    } else {
        // Find existing user by current username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$current_username]);
        $user = $stmt->fetch();

        // Check current credentials (static default OR stored hash)
        $is_current_valid = false;
        if ($user) {
            if ($current_password === 'password123' || password_verify($current_password, $user['password']) || $current_password === $user['password']) {
                $is_current_valid = true;
            }
        }

        if ($user && $is_current_valid) {
            // Hash new password securely so password_verify can authenticate it on next login
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update database with new username and newly hashed password
            $update_stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $updated = $update_stmt->execute([$new_username, $hashed_password, $user['id']]);

            if ($updated) {
                // Log action in audit logs
                $log_action = "Updated account credentials from '" . $current_username . "' to '" . $new_username . "'.";
                $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
                $audit->execute([$new_username, $log_action]);

                $success = "Account credentials updated successfully! You can now log in with your new username and password.";
            } else {
                $error = "Failed to update account in database.";
            }
        } else {
            $error = "Current username or password invalid. Cannot update account.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>System Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background: linear-gradient(0deg, #03fa87b1 20%, #7202b4e2 100%, #0582d0e2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Bright Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08), 0 0 20px rgba(59, 130, 246, 0.1);
            animation: cardEntrance 0.7s ease-out forwards;
        }

        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12), 0 0 30px rgba(59, 130, 246, 0.2);
        }

        @keyframes cardEntrance {
            0% { opacity: 0; transform: translateY(30px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Bright Form Inputs */
        .form-control-animated {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            color: #0f172a !important;
            border-radius: 14px;
            padding: 12px 16px 12px 45px;
            height: auto;
            font-weight: 500;
        }

        .form-control-animated::placeholder {
            color: #94a3b8;
        }

        .form-control-animated:hover {
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        .form-control-animated:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.25);
            transform: translateY(-1px);
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            transition: all 0.3s ease;
            z-index: 5;
        }

        .input-wrapper:hover i,
        .form-control-animated:focus + i,
        .input-wrapper:focus-within i {
            color: #2563eb;
            transform: translateY(-50%) scale(1.15);
        }

        label {
            color: #334155;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 6px;
        }

        /* Animated Buttons */
        .btn-animated {
            border-radius: 14px;
            padding: 12px 16px;
            font-weight: 600;
            letter-spacing: 0.3px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none !important;
            cursor: pointer;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .btn-animated:hover {
            transform: translateY(-3px);
        }

        .btn-animated:active {
            transform: translateY(-1px);
        }

        .btn-login-style {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-login-style:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.45);
        }

        .btn-back-style {
            background: #f1f5f9;
            color: #475569 !important;
            border: 1.5px solid #cbd5e1;
        }

        .btn-back-style:hover {
            background: #e2e8f0;
            color: #0f172a !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .btn-update-style {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
        }

        .btn-update-style:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
            box-shadow: 0 8px 25px rgba(217, 119, 6, 0.45);
        }

        .btn-confirm-style {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3) !important;
        }

        .btn-confirm-style:hover {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.45) !important;
        }

        /* Section Title Styling */
        .update-title {
            color: #1e293b;
            position: relative;
            padding-left: 12px;
        }

        .update-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 16px;
            background: #d97706;
            border-radius: 4px;
        }

        /* Alerts */
        .alert-animated {
            border-radius: 14px;
            border: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            animation: slideDownAlert 0.4s ease forwards;
        }

        @keyframes slideDownAlert {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-card card border-0">
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">
                        <h3 class="text-dark font-weight-bold mb-1">System Login</h3>
                        <p class="text-muted small">Manage account authentication & credentials</p>
                    </div>
                    
                    <!-- Alert Notifications -->
                    <?php if($error): ?>
                        <div class="alert alert-danger alert-animated alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($success): ?>
                        <div class="alert alert-success alert-animated alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($success); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="loginForm">
                        <!-- CURRENT CREDENTIALS -->
                        <div class="form-group mb-3">
                            <label>Username</label>
                            <div class="input-wrapper">
                                <input type="text" name="username" id="username" class="form-control form-control-animated" placeholder="Enter current username" required>
                                <i class="fas fa-user"></i>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Password</label>
                            <div class="input-wrapper">
                                <input type="password" name="password" id="password" class="form-control form-control-animated" placeholder="Enter current password" required>
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>

                        <!-- HIDDEN NEW CREDENTIALS PANEL (Expands on Update Click) -->
                        <div class="collapse" id="updateFieldsPanel">
                            <div class="pt-2 pb-2">
                                <hr style="border-top: 1px dashed #cbd5e1;">
                                <h6 class="update-title font-weight-bold mb-3">New Credentials</h6>
                                
                                <div class="form-group mb-3">
                                    <label>New Username</label>
                                    <div class="input-wrapper">
                                        <input type="text" name="new_username" id="new_username" class="form-control form-control-animated" placeholder="Enter new username">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>New Password</label>
                                    <div class="input-wrapper">
                                        <input type="password" name="new_password" id="new_password" class="form-control form-control-animated" placeholder="Enter new password">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SIDE-BY-SIDE BUTTONS -->
                        <div class="form-row mt-4">
                            <div class="col-6">
                                <!-- Dynamic Left Button: Login (submit) in compressed state, Back (reset view) in extended state -->
                                <button type="submit" name="action_login" value="1" id="btnLeft" class="btn btn-animated btn-login-style btn-block font-weight-bold">
                                    <i class="fas fa-sign-in-alt" id="leftBtnIcon"></i> <span id="leftBtnText">Login</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <!-- Dynamic Right Button: Update (expand view) -> Confirm (submit update) -->
                                <button type="button" id="btnToggleUpdate" class="btn btn-animated btn-update-style btn-block font-weight-bold">
                                    <i class="fas fa-sync-alt" id="updateBtnIcon"></i> <span id="updateBtnText">Update</span>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    var isUpdatePanelOpen = false;

    $('#btnToggleUpdate').click(function() {
        if (!isUpdatePanelOpen) {
            // First click: Expand hidden update fields
            $('#updateFieldsPanel').collapse('show');
            $('#new_username').attr('required', true);
            $('#new_password').attr('required', true);
            
            // Switch Right Button: Update -> Confirm
            $(this).removeClass('btn-update-style').addClass('btn-confirm-style');
            $('#updateBtnIcon').removeClass('fa-sync-alt').addClass('fa-check-circle');
            $('#updateBtnText').text('Confirm');

            // Switch Left Button: Login -> Back
            $('#btnLeft').attr('type', 'button')
                        .removeClass('btn-login-style')
                        .addClass('btn-back-style');
            $('#leftBtnIcon').removeClass('fa-sign-in-alt').addClass('fa-arrow-left');
            $('#leftBtnText').text('Back');
                   
            isUpdatePanelOpen = true;
        } else {
            // Second click: Submit update action independently
            if (confirm('Are you sure you want to update your account details?')) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'action_update_account',
                    value: '1'
                }).appendTo('#loginForm');
                
                $('#loginForm').submit();
            }
        }
    });

    // Handle Left Button Click when in "Back" state
    $('#btnLeft').click(function(e) {
        if (isUpdatePanelOpen) {
            e.preventDefault(); // Prevent submit when acting as Back button
            
            // Collapse panel and remove required fields
            $('#updateFieldsPanel').collapse('hide');
            $('#new_username').removeAttr('required').val('');
            $('#new_password').removeAttr('required').val('');

            // Restore Right Button: Confirm -> Update
            $('#btnToggleUpdate').removeClass('btn-confirm-style').addClass('btn-update-style');
            $('#updateBtnIcon').removeClass('fa-check-circle').addClass('fa-sync-alt');
            $('#updateBtnText').text('Update');

            // Restore Left Button: Back -> Login (submit)
            $(this).attr('type', 'submit')
                   .removeClass('btn-back-style')
                   .addClass('btn-login-style');
            $('#leftBtnIcon').removeClass('fa-arrow-left').addClass('fa-sign-in-alt');
            $('#leftBtnText').text('Login');

            isUpdatePanelOpen = false;
        }
    });
});
</script>
</body>
</html>