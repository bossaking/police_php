<?php

require_once 'Repository.php';
require_once 'TeamRepository.php';
require_once 'UserRepository.php';
require_once 'enums/SubmissionStatuses.php';


class SubmissionRepository extends Repository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createSubmission($userName, $userSurname, $userIdentity, $userStation, $teamId, $topic, $description): int
    {
        $accessCode = $this->generateRandomAccessCode();
        $statusCode = SubmissionStatuses::OPENED->value;

        $sql = "INSERT INTO submission(user_name, user_surname, user_identity, user_station, topic, description, team_id, access_code, access_code_showed, status_id)" .
            "VALUES ('$userName','$userSurname','$userIdentity','$userStation','$topic','$description','$teamId','$accessCode',false,'$statusCode')";

        $this->conn->query($sql);
        return $this->conn->insert_id;
    }

    public function generateRandomAccessCode(): string
    {
        return sha1(rand());
    }

    public function getSubmissionById($id): Submission|null
    {
        $sql = "SELECT * FROM submission WHERE id = '$id'";
        $result = $this->conn->query($sql);
        if ($result->num_rows == 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        return $this->mapSubmissionRow($row);
    }

    public function setAccessCodeShowed($id, $status)
    {
        $sql = "UPDATE submission SET access_code_showed = '$status' WHERE id = '$id'";
        $this->conn->query($sql);
    }

    public function getAllSubmissions(): array
    {
        $submissions = array();
        $sql = "SELECT * FROM submission";
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $this->mapSubmissionRow($row);
        }

        return $submissions;
    }

    public function getSubmissionsByStatus($statusId): array
    {
        if ($statusId == 0) {
            return $this->getAllSubmissions();
        }

        $submissions = array();
        $sql = "SELECT * FROM submission WHERE status_id = '$statusId'";
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $this->mapSubmissionRow($row);
        }

        return $submissions;
    }

    public function getSubmissionsByStatusAndEmployee($statusId, $employeeId): array
    {
        $submissions = array();
        if ($statusId == 0 && $employeeId != 0) {
            $sql = "SELECT * FROM submission WHERE employee_id = '$employeeId'";
        } else if ($employeeId == 0) {
            return $this->getSubmissionsByStatus($statusId);
        } else {
            $sql = "SELECT * FROM submission WHERE status_id = '$statusId' AND employee_id = '$employeeId'";
        }
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $this->mapSubmissionRow($row);
        }

        return $submissions;
    }

    public function getSubmissionByAccessCode($accessCode, $userIdentity): Submission|null
    {
        $sql = "SELECT * FROM submission WHERE access_code = '$accessCode' AND user_identity = '$userIdentity'";
        $result = $this->conn->query($sql);
        if ($result->num_rows == 0) return null;
        return $this->mapSubmissionRow($result->fetch_assoc());
    }

    public function mapSubmissionRow($row): Submission
    {
        $team = (new TeamRepository())->getTeamById($row['team_id']);
        $employee = null;
        if ($row['employee_id'] != null) {
            $employee = (new UserRepository())->getUserById($row['employee_id']);
        }
        return new Submission($row['id'], $employee, $row['user_name'], $row['user_surname'], $row['user_identity'], $row['user_station'], $row['topic'], $row['description'], $row['status_id'], $team
            , $row['access_code'], $row['access_code_showed']);
    }

    public function getSubmissionsCountInTeam($teamId): int
    {
        $sql = "SELECT COUNT(*) as count FROM submission WHERE team_id = '$teamId'";
        $result = $this->conn->query($sql);
        return ($result->fetch_assoc())['count'];
    }

    public function updateSubmission($id, $teamId, $employeeId)
    {
        if ($employeeId == '0') {
            $sql = "UPDATE submission SET team_id = '$teamId', employee_id = NULL WHERE id = '$id'";
            $this->changeSubmissionStatus($id, SubmissionStatuses::OPENED->value);
        } else {
            $sql = "UPDATE submission SET team_id = '$teamId', employee_id = '$employeeId' WHERE id = '$id'";
        }

        $this->conn->query($sql);
    }

    public function changeSubmissionStatus($id, $statusId)
    {
        $sql = "UPDATE submission SET status_id = '$statusId' WHERE id = '$id'";
        $this->conn->query($sql);
    }

    public function deleteSubmission($id)
    {
        $sql = "DELETE FROM submission WHERE id = '$id'";
        $this->conn->query($sql);
    }

}