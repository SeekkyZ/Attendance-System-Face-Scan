<?php

if (!function_exists('thai_date')) {
    /**
     * Format date to Thai format
     */
    function thai_date($date, $format = 'd/m/Y H:i:s')
    {
        if (!$date) return '-';
        
        return \Carbon\Carbon::parse($date)
            ->setTimezone('Asia/Bangkok')
            ->format($format);
    }
}

if (!function_exists('thai_date_diff')) {
    /**
     * Get human readable time difference in Thai
     */
    function thai_date_diff($date)
    {
        if (!$date) return '-';
        
        return \Carbon\Carbon::parse($date)
            ->setTimezone('Asia/Bangkok')
            ->diffForHumans();
    }
}

if (!function_exists('now_thai')) {
    /**
     * Get current time in Thailand timezone
     */
    function now_thai($format = null)
    {
        $now = \Carbon\Carbon::now('Asia/Bangkok');
        return $format ? $now->format($format) : $now;
    }
}
