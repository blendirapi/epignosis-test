<?php

require_once 'Database.php';

class Vacations {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection(); 
    }

    /**
     * Retrieve all vacation requests.
     *
     * @return array Returns an array of vacation requests with their details like date range, reason, and approval status.
     */
    public function getVacationRequests() {
        $sql = "SELECT users.username, vacation_requests.created_at, date_from, date_to, reason, vacation_approval_status.approval_status_name, vacation_requests.id, user_id
                FROM vacation_requests
                JOIN vacation_approval_status ON vacation_requests.approval_status = vacation_approval_status.id
                JOIN users ON vacation_requests.user_id = users.id";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Calculate the total number of business days (working days) between two dates.
     *
     * @param string $date_from The start date of the vacation.
     * @param string $date_to The end date of the vacation.
     * @return int The total number of working days in the specified date range.
     */
    public function calculateTotalDays($date_from, $date_to) {
        $startDate = new DateTime($date_from);
        $endDate = new DateTime($date_to);
        $endDate->modify('+1 day');
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($startDate, $interval, $endDate);

        $total_days = 0;

        foreach ($dateRange as $date) {
            if ($date->format('N') < 6) { // Only count weekdays (Monday to Friday)
                $total_days++;
            }
        }

        return $total_days;
    }
    
    /**
     * Delete a vacation request by its ID.
     *
     * @param int $id The ID of the vacation request to be deleted.
     * @return bool Returns true if the vacation request was successfully deleted, false otherwise.
     */
    public function deleteVacation($id) {
        $sql = "DELETE FROM vacation_requests WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $isDeleted = $stmt->affected_rows > 0;
        $stmt->close();

        return $isDeleted;
    }

    /**
     * Submit a new vacation request.
     *
     * @param string $date_from The start date of the vacation.
     * @param string $date_to The end date of the vacation.
     * @param string $reason The reason for the vacation.
     * @return bool Returns true if the vacation request was successfully created, false otherwise.
     */
    public function requestVacation($date_from, $date_to, $reason) {
        session_start();

        $sql = "INSERT INTO vacation_requests (user_id, date_from, date_to, reason, approval_status) VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param('isss', $_SESSION['user_id'], $date_from, $date_to, $reason);
        $stmt->execute();
    
        $isInserted = $stmt->affected_rows > 0;
        $stmt->close();
    
        return $isInserted;
    }    

    /**
     * Approve a vacation request by its ID.
     *
     * @param int $id The ID of the vacation request to approve.
     * @return bool Returns true if the vacation request was successfully approved, false otherwise.
     */
    public function approveVacation($id) {
        $sql = "UPDATE vacation_requests SET approval_status = 2 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);

        $isUpdated = $stmt->execute();
        $stmt->close();

        return $isUpdated;
    }

    /**
     * Reject a vacation request by its ID.
     *
     * @param int $id The ID of the vacation request to reject.
     * @return bool Returns true if the vacation request was successfully rejected, false otherwise.
     */
    public function rejectVacation($id) {
        $sql = "UPDATE vacation_requests SET approval_status = 3 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);

        $isUpdated = $stmt->execute();
        $stmt->close();

        return $isUpdated;
    }

    /**
     * Destructor to close the connection to the vacation requests database.
     */
    public function __destruct() {
        $this->conn->close();
    }
}
?>
