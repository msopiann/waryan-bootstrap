<?php

/**
 * Format tanggal dari 'Y-m-d H:i:s' ke 'd-M-Y'.
 *
 * @param string $datetime Tanggal dalam format 'Y-m-d H:i:s'.
 * @return string Tanggal yang sudah diformat dalam format 'd-M-Y'.
 */
function formatDate($datetime)
{
    $date = new DateTime($datetime);
    return $date->format('d-M-Y');
}
