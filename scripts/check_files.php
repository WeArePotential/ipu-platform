<?php

use Drupal\file\Entity\File;
// public://images/blah.jpg
$fid = 2305;
$drupal_file_uri = File::load($fid)->getFileUri();
// /sites/default/files/images/blah.jpg
$image_path = file_url_transform_relative(file_create_url($drupal_file_uri));

$counter++;
print("\n - file $fid is $image_path\n");

print("\n - Updated $counter files");
?>