<?php
function formatToRupiah($number)
{
    return 'Rp' . number_format($number, 0, ',', '.');
}
