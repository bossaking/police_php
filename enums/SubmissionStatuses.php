<?php


enum SubmissionStatuses: int
{

    case OPENED = 1;
    case IN_PROGRESS = 2;
    case CLOSED = 3;


    public function title(): string
    {
        return match ($this) {
            SubmissionStatuses::OPENED => 'Otwarte',
            SubmissionStatuses::IN_PROGRESS => 'W toku',
            SubmissionStatuses::CLOSED => 'Zamknięte'
        };
    }

    public static function getPolishTranslation($id) : string{
        return match ($id) {
            SubmissionStatuses::OPENED->value => 'Otwarte',
            SubmissionStatuses::IN_PROGRESS->value => 'W toku',
            SubmissionStatuses::CLOSED->value => 'Zamknięte',
            default => '',
        };
    }

    public static function getClassName($id) : string{
        return match ($id) {
            SubmissionStatuses::OPENED->value => 'opened',
            SubmissionStatuses::IN_PROGRESS->value => 'in-progress',
            SubmissionStatuses::CLOSED->value => 'closed',
            default => '',
        };
    }

}