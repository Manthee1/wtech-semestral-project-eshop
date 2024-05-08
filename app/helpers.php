<?php


if (!function_exists('formatPrice')) {
    function formatPrice($price)
    {
        return number_format($price, 0, ',', ' ') . ' €';
    }
}


if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phone)
    {
        return substr($phone, 0, 3) . ' ' . substr($phone, 3, 2) . ' ' . substr($phone, 5, 2) . ' ' . substr($phone, 7, 2) . ' ' . substr($phone, 9, 2);
    }
}
