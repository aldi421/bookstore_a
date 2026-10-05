<?php

if (!function_exists('h')) {
    function h($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new RuntimeException('Session harus aktif sebelum membuat CSRF token.');
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_input')) {
    function csrf_input(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
    }
}

if (!function_exists('verify_csrf_or_abort')) {
    function verify_csrf_or_abort(): void
    {
        $submitted = $_POST['csrf_token'] ?? '';
        $stored = $_SESSION['csrf_token'] ?? '';

        if ($submitted === '' || $stored === '' || !hash_equals($stored, $submitted)) {
            http_response_code(419);
            exit('Permintaan ditolak karena token keamanan tidak valid. Silakan kembali dan coba lagi.');
        }
    }
}

if (!function_exists('require_role')) {
    function require_role(string $role, string $loginPath = '../login.php'): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            header('Location: ' . $loginPath);
            exit;
        }
    }
}

if (!function_exists('upload_book_image')) {
    /**
     * @return string Nama file aman yang sudah dipindahkan ke folder tujuan.
     */
    function upload_book_image(array $file, string $targetDir): string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload gambar gagal.');
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > 5 * 1024 * 1024) {
            throw new RuntimeException('Ukuran gambar maksimal 5 MB.');
        }

        $tmp = $file['tmp_name'] ?? '';
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            throw new RuntimeException('File upload tidak valid.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp);
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowed[$mime])) {
            throw new RuntimeException('Format gambar harus JPG, PNG, atau WEBP.');
        }

        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            throw new RuntimeException('Folder gambar tidak dapat dibuat.');
        }

        $filename = 'buku_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        $destination = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($tmp, $destination)) {
            throw new RuntimeException('Gambar gagal disimpan.');
        }

        return $filename;
    }
}
