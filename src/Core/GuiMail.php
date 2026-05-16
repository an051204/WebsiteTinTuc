<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class GuiMail
{
    private PHPMailer $mail;
    private string $senderEmail;
    private string $senderName;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->senderEmail = $_ENV['MAIL_FROM_EMAIL'] ?? 'noreply@websitetintuc.local';
        $this->senderName = $_ENV['MAIL_FROM_NAME'] ?? 'Website Tin Tức';
        
        $this->configureSMTP();
    }

    private function configureSMTP(): void
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['MAIL_USERNAME'] ?? '';
            $this->mail->Password = $_ENV['MAIL_PASSWORD'] ?? '';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = (int)($_ENV['MAIL_PORT'] ?? 587);
            $this->mail->CharSet = 'UTF-8';
        } catch (Exception $e) {
            error_log("PHPMailer Config Error: " . $e->getMessage());
        }
    }

    public function sendPasswordResetEmail(string $email, string $fullName, string $resetLink): bool
    {
        $subject = 'Đặt lại mật khẩu - Website Tin Tức';

        $body = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 5px; }
        .header { color: #333; margin-bottom: 20px; }
        .content { color: #666; line-height: 1.6; }
        .button { background-color: #667eea; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 15px; }
        .footer { color: #999; font-size: 0.9em; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Yêu cầu đặt lại mật khẩu</h2>
        </div>
        <div class='content'>
            <p>Xin chào <strong>" . htmlspecialchars($fullName) . "</strong>,</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
            <p>Nếu đây là bạn, hãy nhấn vào nút dưới đây để đặt lại mật khẩu:</p>
            <a href='" . htmlspecialchars($resetLink) . "' class='button'>Đặt lại mật khẩu</a>
            <p>Hoặc sao chép và dán liên kết này vào trình duyệt của bạn:</p>
            <p><a href='" . htmlspecialchars($resetLink) . "'>" . htmlspecialchars($resetLink) . "</a></p>
            <p><strong>Lưu ý:</strong> Liên kết này sẽ hết hạn trong 1 giờ.</p>
            <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
        </div>
        <div class='footer'>
            <p>© 2026 Website Tin Tức. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
        ";

        return $this->sendEmail($email, $subject, $body);
    }

    public function sendWelcomeEmail(string $email, string $fullName): bool
    {
        $subject = 'Chào mừng bạn tham gia Website Tin Tức';

        $body = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 5px; }
        .header { color: #333; margin-bottom: 20px; }
        .content { color: #666; line-height: 1.6; }
        .button { background-color: #667eea; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 15px; }
        .footer { color: #999; font-size: 0.9em; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Chào mừng tới Website Tin Tức!</h2>
        </div>
        <div class='content'>
            <p>Xin chào <strong>" . htmlspecialchars($fullName) . "</strong>,</p>
            <p>Tài khoản của bạn đã được tạo thành công. Cảm ơn bạn đã tham gia cộng đồng của chúng tôi!</p>
            <p>Bây giờ bạn có thể:</p>
            <ul>
                <li>Đọc và khám phá hàng nghìn bài viết hay</li>
                <li>Lưu bài viết yêu thích của bạn</li>
                <li>Bình luận và chia sẻ quan điểm của bạn</li>
                <li>Cập nhật hồ sơ cá nhân của bạn</li>
            </ul>
            <a href='" . htmlspecialchars('/WebsiteTinTuc/public/') . "' class='button'>Truy cập trang chủ</a>
        </div>
        <div class='footer'>
            <p>© 2026 Website Tin Tức. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
        ";

        return $this->sendEmail($email, $subject, $body);
    }

    private function sendEmail(string $email, string $subject, string $body): bool
    {
        try {
            $this->mail->setFrom($this->senderEmail, $this->senderName);
            $this->mail->addAddress($email);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->AltBody = strip_tags($body);
            
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("PHPMailer Send Error: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}