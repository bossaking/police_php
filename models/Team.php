<?php

class Team{

    private int $id;
    private string $teamName;

    public function __construct($id, $teamName){
        $this->id = $id;
        $this->teamName = $teamName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTeamName(): string
    {
        return $this->teamName;
    }

    public function setTeamName(string $teamName): void
    {
        $this->teamName = $teamName;
    }




}