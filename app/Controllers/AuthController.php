<?php
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
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            require_once ROOT_PATH . '/app/Models/Database.php';
            $db = new Database();
            $db->query("SELECT * FROM users WHERE email = :email");
            $db->bind(':email', $email);
            $user = $db->single();

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    redirect('dashboard');
                } else {
                    $_SESSION['error'] = "Password sala!";
                    redirect('');
                }
            } else {
                $_SESSION['error'] = "Email sala! User la eziste.";
                redirect('');
            }
        }
    }

    public function logout() {
        session_destroy();
        redirect('');
    }
}
