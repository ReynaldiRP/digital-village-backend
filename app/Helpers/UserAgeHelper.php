<?php

namespace App\Helpers;

class UserAgeHelper
{
    public static function classifyAge(int $age): string
    {
        if ($age >= 0 && $age <= 1) {
            return 'Bayi';
        } elseif ($age >= 2 && $age <= 5) {
            return 'Balita';
        } elseif ($age >= 6 && $age <= 11) {
            return 'Anak-anak';
        } elseif ($age >= 12 && $age <= 25) {
            return 'Remaja';
        } elseif ($age >= 26 && $age <= 45) {
            return 'Dewasa';
        } elseif ($age >= 46 && $age <= 55) {
            return 'Lansia Awal';
        } elseif ($age >= 56 && $age <= 65) {
            return 'Lansia Akhir';
        } else {
            return 'Manula';
        }
    }

    public static function calculateAge(\DateTime $birthDate): int
    {
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;
        return $age;
    }
}
