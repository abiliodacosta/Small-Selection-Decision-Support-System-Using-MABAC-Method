<?php
class CriteriaController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    public function index() {
        $this->db->query("SELECT * FROM criteria ORDER BY code ASC");
        $criteria = $this->db->resultSet();
        view('layouts/header', ['title' => 'Manage Criteria']);
        view('criteria/index', ['criteria' => $criteria]);
        view('layouts/footer');
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = $_POST['code'];
            $name = $_POST['name'];
            $type = $_POST['type'];
            $weight = $_POST['weight'];
            $this->db->query("INSERT INTO criteria (code, name, type, weight) VALUES (:code, :name, :type, :weight)");
            $this->db->bind(':code', $code); $this->db->bind(':name', $name);
            $this->db->bind(':type', $type); $this->db->bind(':weight', $weight);
            $this->db->execute();
            $_SESSION['msg'] = "Criterion added successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('criteria');
        }
    }
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = $_POST['code'];
            $name = $_POST['name'];
            $type = $_POST['type'];
            $weight = $_POST['weight'];
            $this->db->query("UPDATE criteria SET code = :code, name = :name, type = :type, weight = :weight WHERE id = :id");
            $this->db->bind(':code', $code); $this->db->bind(':name', $name);
            $this->db->bind(':type', $type); $this->db->bind(':weight', $weight);
            $this->db->bind(':id', $id);
            $this->db->execute();
            $_SESSION['msg'] = "Criterion updated successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('criteria');
        }
    }
    public function delete($id) {
        $this->db->query("DELETE FROM criteria WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        $_SESSION['msg'] = "Criterion deleted successfully!";
        $_SESSION['msg_type'] = "danger";
        redirect('criteria');
    }
}
