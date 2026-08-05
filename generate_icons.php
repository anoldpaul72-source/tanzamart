<?php
// Hakikisha folder la assets lipo
if (!file_exists('assets')) {
    mkdir('assets', 0777, true);
}

// URL za picha za placeholder
$url_192 = "https://placehold.co/192x192/00bcd4/ffffff/png?text=TM";
$url_512 = "https://placehold.co/512x512/00bcd4/ffffff/png?text=TM";

echo "Inapakua picha...<br>";

// Kupakua na kuhifadhi kwenye folder la assets
if (copy($url_192, 'assets/icon-192.png') && copy($url_512, 'assets/icon-512.png')) {
    echo "✅ Icon zote mbili zimepakuliwa na kuhifadhiwa kikamilifu ndani ya folder la assets!";
} else {
    echo "❌ Imeshindikana kupakua. Hakikisha una internet kwenye kompyuta yako.";
}
?>