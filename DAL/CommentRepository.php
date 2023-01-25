<?php

require_once 'Repository.php';
require_once 'UserRepository.php';
require_once 'models/Comment.php';

class CommentRepository extends Repository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createComment($text, $submissionId, $employeeId)
    {
        $sql = "INSERT INTO comment(text, employee_id, submission_id) VALUES ('$text', '$employeeId', '$submissionId')";
        $this->conn->query($sql);
    }

    public function getSubmissionComments($subId): array
    {
        $comments = array();
        $sql = "SELECT * FROM comment WHERE submission_id = '$subId'";
        $result = $this->conn->query($sql);
        $userRepo = new UserRepository();
        while ($row = $result->fetch_assoc()){
            $employee = $userRepo->getUserById($row['employee_id']);
            $comments[] = new Comment($row['id'], $subId, $employee, $row['text']);
        }
        return $comments;
    }

}