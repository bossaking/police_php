<?php

class Submission
{

    private int $id;
    private string $userName, $userSurname, $userIdentity, $userStation, $topic, $description, $accessCode;
    private int $statusId;
    private Team $team;
    private User|null $employee;
    private bool $accessCodeShowed;


    public function __construct($id, $employee, $userName, $userSurname, $userIdentity, $userStation, $topic, $description, $statusId, $team, $accessCode, $accessCodeShowed)
    {
        $this->id = $id;
        $this->employee = $employee;
        $this->userName = $userName;
        $this->userSurname = $userSurname;
        $this->userIdentity = $userIdentity;
        $this->userStation = $userStation;
        $this->topic = $topic;
        $this->description = $description;
        $this->statusId = $statusId;
        $this->team = $team;
        $this->accessCode = $accessCode;
        $this->accessCodeShowed = $accessCodeShowed;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmployee(): User | null
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): void
    {
        $this->employee = $employee;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserSurname(): string
    {
        return $this->userSurname;
    }

    public function setUserSurname(string $userSurname): void
    {
        $this->userSurname = $userSurname;
    }

    public function getUserIdentity(): string
    {
        return $this->userIdentity;
    }

    public function setUserIdentity(string $userIdentity): void
    {
        $this->userIdentity = $userIdentity;
    }

    public function getUserStation(): string
    {
        return $this->userStation;
    }

    public function setUserStation(string $userStation): void
    {
        $this->userStation = $userStation;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStatus(): int
    {
        return $this->statusId;
    }

    public function setStatus(int $statusId): void
    {
        $this->statusId = $statusId;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): void
    {
        $this->team = $team;
    }

    public function getAccessCode(): string
    {
        return $this->accessCode;
    }

    public function setAccessCode(string $accessCode): void
    {
        $this->accessCode = $accessCode;
    }

    public function isAccessCodeShowed(): bool
    {
        return $this->accessCodeShowed;
    }

    public function setAccessCodeShowed(bool $accessCodeShowed): void
    {
        $this->accessCodeShowed = $accessCodeShowed;
    }


}