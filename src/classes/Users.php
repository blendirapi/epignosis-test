<?php

require_once 'Database.php';

class Users {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection(); 
    }

    /**
     * Authenticate user login.
     *
     * @param string $username Username provided by the user.
     * @param string $password Password provided by the user.
     * @return bool Returns true if authentication is successful, false otherwise.
     */
    public function loginUser($username, $password) {
        $sql = "SELECT id, username, password, role_id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        // Verify password and start a session if valid
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_manager'] = $user['role_id'] == 1;
            return true;
        }
        return false;
    }

    /**
     * Register a new user.
     *
     * @param string $username New user's username.
     * @param string $email New user's email.
     * @param string $employee_code New user's employee code.
     * @param string $password New user's password.
     * @param int $role User's role ID.
     * @return bool Returns true if the new user registration is successful, false otherwise.
     */
    public function registerUser($username, $email, $employee_code, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
        $sql = "INSERT INTO users (username, email, employee_code, password, role_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssi', $username, $email, $employee_code, $hashedPassword, $role);
        $stmt->execute();

        $isInserted = $stmt->affected_rows > 0;
        $stmt->close();

        return $isInserted;
    }

    /**
     * Retrieve all users.
     *
     * @return array Returns an array of users with their IDs, usernames, and emails.
     */
    public function getUsers() {
        $sql = "SELECT id, username, email FROM users";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id User ID to be deleted.
     * @return bool Returns true if the user was successfully deleted, false otherwise.
     */
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $isDeleted = $stmt->affected_rows > 0;
        $stmt->close();

        return $isDeleted;
    }

    /**
     * Get user data by ID.
     *
     * @param int $id User ID.
     * @return array|null Returns user data as an associative array, or null if not found.
     */
    public function getUserData($id) {
        $sql = "SELECT id, username, email, employee_code, role_id FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $stmt->close();

        return $user_data;
    }

    /**
     * Edit user data by ID.
     *
     * @param int $id User ID.
     * @param string $username User's new username.
     * @param string $email User's new email.
     * @param string $employee_code User's new employee code.
     * @param string $password User's new password.
     * @param int $role User's new role ID.
     * @return bool Returns true if the user was updated, false otherwise.
     */
    public function editUserData($id, $username, $email, $employee_code, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
        $sql = "UPDATE users SET username = ?, email = ?, employee_code = ?, password = ?, role_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssii', $username, $email, $employee_code, $hashedPassword, $role, $id);

        $isUpdated = $stmt->execute();
        $stmt->close();

        return $isUpdated;
    }

    /**
     * Retrieve all user roles.
     *
     * @return array Returns an array of user roles with their IDs and names.
     */
    public function getUserRoles() {
        $sql = "SELECT id, role_name FROM roles";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Destructor to close the database connection.
     */
    public function __destruct() {
        $this->conn->close();
    }
}
?>
