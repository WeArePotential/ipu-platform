<?php

namespace Drupal\ipu_tweaks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\Unicode;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;


/**
 * Defines HelloController class.
 */
class IpuTweaksController extends ControllerBase {

  public function __construct() {
  }

  public function download($fid) {

    /*
     * Copied from file_entity/src/Controller/FileController
     */
    if ($file = File::load($fid)) {
      $headers = [
        'Content-Type' => Unicode::mimeHeaderEncode($file->getMimeType()),
        'Content-Disposition' => 'attachment; filename="' . Unicode::mimeHeaderEncode(drupal_basename($file->getFileUri())) . '"',
        'Content-Length' => $file->getSize(),
        'Content-Transfer-Encoding' => 'binary',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0',
      ];

      // Let other modules alter the download headers.
      \Drupal::moduleHandler()->alter('file_download_headers', $headers, $file);

      // Let other modules know the file is being downloaded.
      \Drupal::moduleHandler()->invokeAll('file_transfer', [
        $file->getFileUri(),
        $headers,
      ]);

      try {
        return new BinaryFileResponse($file->getFileUri(), 200, $headers);
      }
      catch (FileNotFoundException $e) {
        return new Response(t('File @uri not found', ['@uri' => $file->getFileUri()]), 404);
      }

    } else {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }
  }
}