<?php


enum Roles: int
{

    case ADMIN = 1;
    case SUPERIOR = 2;
    case EMPLOYEE = 3;


    public function title(): string
    {
        return match ($this) {
            Roles::ADMIN => 'Administrator',
            Roles::SUPERIOR => 'Przełożony',
            Roles::EMPLOYEE => 'Pracownik'
        };
    }

    public static function getPolishTranslation($id) : string{
        return match ($id) {
            Roles::ADMIN->value => 'Administrator',
            Roles::SUPERIOR->value => 'Przełożony',
            Roles::EMPLOYEE->value => 'Pracownik',
            default => '',
        };
    }

}