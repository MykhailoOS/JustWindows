<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ItemFile;
use App\Models\Item;

final class DownloadController
{
    public function serve(array $query): void
    {
        $fileId = (int) ($query['file_id'] ?? 0);
        if (!$fileId) {
            http_response_code(400);
            echo 'Missing file_id parameter';
            return;
        }

        $fileModel = new ItemFile();
        $file = $fileModel->findById($fileId);

        if (!$file) {
            http_response_code(404);
            echo 'File not found';
            return;
        }

        $this->logDownload($fileId, user()['id'] ?? null);

        $absPath = realpath(__DIR__.'/../../storage/files/'.$file['file_path']);
        $basePath = realpath(__DIR__.'/../../storage/files');

        if (!$absPath || !$basePath || !str_starts_with($absPath, $basePath)) {
            http_response_code(403);
            echo 'Access denied';
            return;
        }

        if (!file_exists($absPath)) {
            http_response_code(404);
            echo 'File not found on disk';
            return;
        }

        $fileModel->incrementDownloads($fileId);

        if (function_exists('apache_get_modules') && in_array('mod_xsendfile', apache_get_modules(), true)) {
            header('X-Sendfile: '.$absPath);
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file['file_name']).'"');
            exit;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Length: '.$file['file_size']);
        header('Content-Disposition: attachment; filename="'.basename($file['file_name']).'"');
        header('X-Content-Type-Options: nosniff');
        readfile($absPath);
        exit;
    }

    private function logDownload(int $fileId, ?int $userId): void
    {
        $db = \App\Container::db();
        if (!$db) {
            return;
        }

        $stmt = $db->prepare('
            INSERT INTO download_logs (file_id, user_id, ip, user_agent)
            VALUES (?, ?, INET6_ATON(?), ?)
        ');
        $stmt->execute([
            $fileId,
            $userId,
            $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            $_SERVER['HTTP_USER_AGENT'] ?? '',
        ]);
    }
}
