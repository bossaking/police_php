<?php

require_once 'Repository.php';

class TeamRepository extends Repository
{

    function __construct()
    {
        parent::__construct();
    }


    public function teamExists($teamName): bool
    {
        $sql = "SELECT * FROM team WHERE team_name = '$teamName'";

        $result = $this->conn->query($sql);

        return $result->num_rows > 0;
    }


    public function createTeam($teamName)
    {
        $sql = "INSERT INTO team(team_name) VALUES ('$teamName')";
        $this->conn->query($sql);
    }

    public function getTeamById($id): Team|null
    {
        $sql = "SELECT * FROM team WHERE id = '$id'";
        $result = $this->conn->query($sql);
        if ($result->num_rows == 0) {
            return null;
        }

        $teamRow = $result->fetch_assoc();
        return new Team($teamRow['id'], $teamRow['team_name']);
    }

    public function getAllTeams(): array
    {
        $teams = array();
        $sql = "SELECT * FROM team";
        $result = $this->conn->query($sql);
        while ($teamRow = $result->fetch_assoc()) {
            $teams[] = new Team($teamRow['id'], $teamRow['team_name']);
        }

        return $teams;
    }

    public function updateTeam($teamId, $teamName)
    {
        $sql = "UPDATE team SET team_name = '$teamName' WHERE id = '$teamId'";
        $this->conn->query($sql);
    }

    public function deleteTeam($id)
    {
        $sql = "DELETE FROM team WHERE id = '$id'";
        $this->conn->query($sql);
    }

}