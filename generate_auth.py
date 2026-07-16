import os

base_dir = 'c:/xampp/htdocs/app-dss'

files_content = {
    '.htaccess': '''<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^$ public/ [L]
    RewriteRule (.*) public/$1 [L]
</IfModule>''',

    'app/Controllers/AuthController.php': '''<?php
class AuthController {
    public function index() {
        // If already logged in, go to dashboard
        if (isset($_SESSION['user_id'])) {
            redirect('dashboard');
        }
        view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            require_once ROOT_PATH . '/app/Models/Database.php';
            $db = new Database();
            $db->query("SELECT * FROM users WHERE email = :email");
            $db->bind(':email', $email);
            $user = $db->single();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                redirect('dashboard');
            } else {
                $_SESSION['error'] = "Email ou Password sala!";
                redirect('auth');
            }
        }
    }

    public function logout() {
        session_destroy();
        redirect('auth');
    }
}
''',

    'resources/views/auth/login.php': '''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MABAC DSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; padding: 2rem; background: white; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold">MABAC DSS</h3>
            <p class="text-muted">Halo Login ba Sistema</p>
        </div>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="<?= APP_URL ?>/auth/login" method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" required placeholder="admin@dss.com">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" required placeholder="password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold">Login <i class="fas fa-sign-in-alt ms-1"></i></button>
        </form>
    </div>
</body>
</html>
'''
}

for path, content in files_content.items():
    full_path = os.path.join(base_dir, path)
    os.makedirs(os.path.dirname(full_path), exist_ok=True)
    with open(full_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("Auth files created.")
