<?php

class Comment{

    private int $id, $submissionId;
    private User $employee;
    private string $text;


    public function __construct(int $id, int $submissionId, User $employee, string $text)
    {
        $this->id = $id;
        $this->submissionId = $submissionId;
        $this->employee = $employee;
        $this->text = $text;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSubmissionId(): int
    {
        return $this->submissionId;
    }

    public function setSubmissionId(int $submissionId): void
    {
        $this->submissionId = $submissionId;
    }

    public function getEmployee(): User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): void
    {
        $this->employee = $employee;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }



}