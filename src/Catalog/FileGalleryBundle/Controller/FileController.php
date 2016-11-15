<?php

namespace Catalog\FileGalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * EditController
 */
class FileController extends Controller
{
    public function getFileWithOriginNameAction($id) {
        $entity = $this->getDoctrine()->getRepository('CatalogFileGalleryBundle:File')->find($id);
        if (file_exists($entity->getAbsolutePath())) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $entity->getOriginName());
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($entity->getAbsolutePath()));
            // читаем файл и отправляем его пользователю
            if ($fd = fopen($entity->getAbsolutePath(), 'rb')) {
                while (!feof($fd)) {
                    print fread($fd, 1024);
                }
                fclose($fd);
            }
            exit;
        }
    }
}
