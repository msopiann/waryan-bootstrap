<?php

// Fungsi untuk membuat slug dari nama produk
function generateSlug($name)
{
    // Ubah nama menjadi huruf kecil
    $slug = strtolower($name);
    // Gantikan spasi dan karakter khusus dengan "-"
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    // Hapus tanda minus di awal dan akhir, jika ada
    $slug = trim($slug, '-');

    return $slug;
}
