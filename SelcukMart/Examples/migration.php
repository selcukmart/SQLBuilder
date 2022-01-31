<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 17:17
 */


use SelcukMart\Migration2SQLBuilder\Migration2SQLBuilder;

require(__DIR__ . '/../../vendor/autoload.php');

//$migration = new Migration();
//$migration->extractSQL();

$sql = "SELECT 
    a,
       b,
       c.s,
  gg.*, 
  so.sube_id, 
  so.ana_yetki_id, 
  so.ad AS KUL_AD, 
  so.soyad AS KUL_SOYAD, 
  os.isim ODEME_SECENEKLERI, 
  nes2.isim AS KARGO_FIRMASI, 
  ei.id AS INVOICE_ID, 
  ei.parasut_fatura_id, 
  ei.e_fatura_or_arsiv, 
  ei.parasut_e_fatura_id, 
  ei.trackable_job_id, 
  ei.status, 
  ei.proccess_status, 
  ei.sent, 
  ei.pdf_url, 
  ei.kargo_gonderildi, 
  ei.kargo_firmasina_bildirildi, 
  ei.bildirim_tarihi 
FROM 
  tes_gelir_gider AS gg 
      LEFT JOIN tes_nesne AS nes2 ON nes2.id = gg.kargo_firma_id 
  AND nes2.tip = '15' AND nes2.isim='12ssd' XOR nes2.ad='sdsdsd' && (nes2.k='12' || nes2.k='sdsd') 
  INNER JOIN tes_kullanici so ON so.id = gg.kisi_id 
  LEFT JOIN tes_odeme_secenekleri os ON os.id = gg.odeme_secenekleri 
  
  LEFT JOIN tes_adresler adr ON adr.id = gg.teslimat_adresi_id 
  LEFT JOIN tes_adresler adr2 ON adr2.id = gg.fatura_adresi_id 
  LEFT JOIN tes_il_ilce_semt_mahalle iism1 ON iism1.id = adr.il 
  AND iism1.kume_id = '0' 
  LEFT JOIN tes_il_ilce_semt_mahalle iism5 ON iism5.id = adr2.il 
  AND iism5.kume_id = '0' 
  LEFT JOIN tes_e_invoice ei ON ei.gg_id = gg.id 
WHERE 
  gg.durum < '4'
AND ei.status between 1 and 10
AND ei.status not between 4 and 6
(xor os.durum='1')
or so.ad='asdasdasd' 
 GROUP BY a.sira,b.c,d.e ORDER by az.sira,c.x LIMIT 10, 15";
$migration = new Migration2SQLBuilder();
$output = $migration->sqlBuilder($sql);

$export_filename = 'sql-migration-example.php';

include __DIR__.'/export-file.php';