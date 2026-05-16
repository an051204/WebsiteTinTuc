<?php
/**
 * Cron Job: Tự động đăng bài viết theo lịch
 * 
 * Chức năng: Kiểm tra và xuất bản các bài viết có published_at  NOW()
 * Chạy: Mỗi 5 phút từ cron schedule hoặc manual test
 * 
 * Cách dùng:
 *   Tự động Cron: every 5 minutes: php /path/to/scripts/cron_publish_articles.php
 *   Test thủ công: php cron_publish_articles.php --test
 *   Test với debug: php cron_publish_articles.php --test --verbose
 */

date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once dirname(__DIR__) . '/vendor/autoload.php';

function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("File .env not found at: {$filePath}");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $_ENV[$key] = $value;
        }
    }
}

loadEnv(dirname(__DIR__) . '/.env');

use App\Core\KetNoiCSDL;

try {
    $db = KetNoiCSDL::getInstance();
    $isTest = in_array('--test', $argv);
    $isVerbose = in_array('--verbose', $argv);
    $logFile = dirname(__DIR__) . '/logs/cron_publish.log';
    
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    
    $logMessage = function($msg, $type = 'info') use ($logFile, $isVerbose) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$type}] {$msg}";
        
        file_put_contents($logFile, $logEntry . "\n", FILE_APPEND);
        
        if ($isVerbose || $type !== 'info') {
            echo $logEntry . "\n";
        }
    };
    
    $logMessage('=== Cron job bắt đầu ===');
    
    $sql = "SELECT id, title, published_at, status, author_id, category_id
            FROM articles 
            WHERE status = 'pending' 
            AND published_at IS NOT NULL 
            AND published_at <= NOW()
            ORDER BY published_at ASC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $pendingArticles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    if (empty($pendingArticles)) {
        $logMessage('Không có bài viết cần xuất bản', 'info');
        exit(0);
    }
    
    $logMessage('Tìm thấy ' . count($pendingArticles) . ' bài viết cần xuất bản', 'info');
    
    $updateSql = "UPDATE articles 
                  SET status = 'published', updated_at = NOW()
                  WHERE id = :id 
                  AND status = 'pending'
                  AND published_at IS NOT NULL 
                  AND published_at <= NOW()";
    
    $updateStmt = $db->prepare($updateSql);
    $successCount = 0;
    
    foreach ($pendingArticles as $article) {
        $updateStmt->bindParam(':id', $article['id'], \PDO::PARAM_INT);
        
        if ($updateStmt->execute()) {
            $successCount++;
            $logMessage(
                "✓ ID={$article['id']}, Tiêu: {$article['title']}, Giờ: {$article['published_at']}",
                'success'
            );
        } else {
            $logMessage(
                "✗ Lỗi ID={$article['id']}: {$article['title']}",
                'error'
            );
        }
    }
    
    $logMessage("Đã xuất bản {$successCount}/" . count($pendingArticles) . " bài viết", 'success');
    $logMessage('=== Cron job kết thúc ===');
    
    exit(0);
    
} catch (\Exception $e) {
    $timestamp = date('Y-m-d H:i:s');
    $logFile = dirname(__DIR__) . '/logs/cron_publish.log';
    
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    
    $errorMsg = "[{$timestamp}] [ERROR] {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}";
    file_put_contents($logFile, $errorMsg . "\n", FILE_APPEND);
    
    echo $errorMsg . "\n";
    exit(1);
}
?>
