<?
  $zip = new ZipArchive(); //Создаём объект для работы с ZIP-архивами
  $zip->open("archive.zip", ZIPARCHIVE::CREATE); //Открываем (создаём) архив archive.zip
  $zip->addFile("index.php"); //Добавляем в архив файл index.php
//  $zip->addFile("styles/style.css"); //Добавляем в архив файл styles/style.css
  $zip->close(); //Завершаем работу с архивом
?>
