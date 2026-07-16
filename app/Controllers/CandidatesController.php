<?php
class CandidatesController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    public function index() {
        $this->db->query("SELECT * FROM candidates ORDER BY id DESC");
        $candidates = $this->db->resultSet();
        view('layouts/header', ['title' => 'Manage Candidates']);
        view('candidates/index', ['candidates' => $candidates]);
        view('layouts/footer');
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $household_code = $_POST['household_code'];
            $name = $_POST['name'];
            $nik = $_POST['nik'];
            $address = $_POST['address'];
            $family = $_POST['family_members'];
            $this->db->query("INSERT INTO candidates (household_code, name, nik, address, family_members) VALUES (:household_code, :name, :nik, :address, :family)");
            $this->db->bind(':household_code', $household_code);
            $this->db->bind(':name', $name); $this->db->bind(':nik', $nik);
            $this->db->bind(':address', $address); $this->db->bind(':family', $family);
            $this->db->execute();
            $_SESSION['msg'] = "Candidate added successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('candidates');
        }
    }
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $household_code = $_POST['household_code'];
            $name = $_POST['name'];
            $nik = $_POST['nik'];
            $address = $_POST['address'];
            $family = $_POST['family_members'];
            $this->db->query("UPDATE candidates SET household_code = :household_code, name = :name, nik = :nik, address = :address, family_members = :family WHERE id = :id");
            $this->db->bind(':household_code', $household_code);
            $this->db->bind(':name', $name); $this->db->bind(':nik', $nik);
            $this->db->bind(':address', $address); $this->db->bind(':family', $family);
            $this->db->bind(':id', $id);
            $this->db->execute();
            $_SESSION['msg'] = "Candidate updated successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('candidates');
        }
    }
    public function delete($id) {
        $this->db->query("DELETE FROM candidates WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        $_SESSION['msg'] = "Candidate deleted successfully!";
        $_SESSION['msg_type'] = "danger";
        redirect('candidates');
    }
}
