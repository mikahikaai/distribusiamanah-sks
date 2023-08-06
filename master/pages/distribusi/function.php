<?php

function hitungUpah($jarak, $upah, $jam)
{
  $KONSTANTA_LUAR_KOTA = 110;
  $upah_kirim = ($jarak / $KONSTANTA_LUAR_KOTA) * $upah;
  if ($upah_kirim >= $upah){
    $upah_kirim = $upah;
  }
  if ($jam >= 24){
    $upah_kirim = $upah_kirim * ($jam/24);
  }
  return round($upah_kirim, 0);
}

function hitungInsentifBongkar($cup, $a330, $a500, $a600, $refill)
{
  //KONSTANTA PRODUK
  $KONSTANTA_INSENTIF_CUP = 20;
  $KONSTANTA_INSENTIF_330 = 20;
  $KONSTANTA_INSENTIF_500 = 20;
  $KONSTANTA_INSENTIF_600 = 20;
  $KONSTANTA_INSENTIF_REFILL = 30;

  $insentif_cup = $KONSTANTA_INSENTIF_CUP * $cup;
  $insentif_a330 = $KONSTANTA_INSENTIF_330 * $a330;
  $insentif_a500 = $KONSTANTA_INSENTIF_500 * $a500;
  $insentif_a600 = $KONSTANTA_INSENTIF_600 * $a600;
  $insentif_refill = $KONSTANTA_INSENTIF_REFILL * $refill;

  $insentif = $insentif_cup + $insentif_a330 + $insentif_a500 + $insentif_a600 + $insentif_refill;

  return round($insentif, 0);
}

function hitungInsentifOntime($jarak, $mobil)
{

  switch ($mobil) {
    case 'S':
      $KONSTANTA_ARMADA = 63;
      break;
    case 'M':
      $KONSTANTA_ARMADA = 84;
      break;
    case 'L':
      $KONSTANTA_ARMADA = 101.5;
      break;
    case 'XL':
      $KONSTANTA_ARMADA = 118;
      break;
  }

  $ontime = $jarak * $KONSTANTA_ARMADA * 0.6;

  return round($ontime, 0);
}
