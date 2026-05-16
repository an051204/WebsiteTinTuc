<?php
/**
 * Quản Lý Thành Viên (Admin)
 * 
 * Variables passed from Controller:
 * @var array $users - Danh sách users
 * @var array $roles - Danh sách roles
 * @var int $page - Trang hiện tại
 * @var int $totalPages - Tổng số trang
 * @var int $totalUsers - Tổng số users
 * @var ?int $roleId - Role filter (null = all)
 * @var string $search - Search keyword
 */

$basePath = '/WebsiteTinTuc/public';
$pageTitle = 'Quản Lý Thành Viên - Admin';

$roleNames = [1 => 'Admin', 2 => 'Editor', 3 => 'User'];

include dirname(__FILE__) . '/../layouts/header-admin.php';
?>

<div style="max-width: 1000px; margin: 30px auto; padding: 0 15px;">
    <h1 style="font-size: 2em; margin-bottom: 25px; color: #2c3e50;">📋 Quản Lý Thành Viên</h1>

    <?php if (isset($_SESSION['success'])): ?>
    <div
        style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #28a745;">
        ✓ <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div
        style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
        ✗ <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div
        style="background: white; padding: 20px; border-radius: 6px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <form method="GET" action="<?= $basePath ?>/quan-tri/tai-khoan/"
            style="display: grid; grid-template-columns: 1fr 200px 120px; gap: 12px; align-items: end;">
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 0.9em;">🔍 Tìm
                    kiếm:</label>
                <input type="text" name="search" placeholder="Tên hoặc email..."
                    value="<?= htmlspecialchars($search) ?>"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.95em;">
            </div>

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 0.9em;">📊
                    Quyền:</label>
                <select name="role_id"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.95em;">
                    <option value="">Tất Cả</option>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['id'] ?>" <?= $roleId == $role['id'] ? 'selected' : '' ?>>
                        <?= $roleNames[$role['id']] ?? $role['name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit"
                style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; transition: background 0.3s;">🔍
                Tìm</button>
        </form>
    </div>

    <div style="background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; font-weight: bold; color: #333; width: 8%;">ID</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold; color: #333; width: 22%;">Tên</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold; color: #333; width: 24%;">Email</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold; color: #333; width: 14%;">Quyền</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold; color: #333; width: 14%;">Trạng Thái
                    </th>
                    <th style="padding: 12px; text-align: center; font-weight: bold; color: #333; width: 18%;">Hành Động
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: #999;">Không tìm thấy người dùng
                        nào</td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr style="border-bottom: 1px solid #dee2e6; transition: background 0.2s;">
                    <td style="padding: 12px;"><strong>#<?= $user['id'] ?></strong></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($user['full_name']) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($user['email']) ?></td>

                    <td style="padding: 12px;">
                        <form method="POST" action="<?= $basePath ?>/quan-tri/tai-khoan/cap-nhat-role/"
                            style="display: inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <select name="role_id" onchange="this.form.submit()"
                                style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 3px; font-size: 0.9em; cursor: pointer;">
                                <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"
                                    <?= $user['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                    <?= $roleNames[$role['id']] ?? $role['name'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>

                    <td style="padding: 12px;">
                        <?php
                                $badgeBg = $user['status'] === 'active' ? '#d4edda' : '#f8d7da';
                                $badgeColor = $user['status'] === 'active' ? '#155724' : '#721c24';
                                $statusText = $user['status'] === 'active' ? '✓ Hoạt Động' : '✗ Khóa';
                                ?>
                        <span
                            style="background: <?= $badgeBg ?>; color: <?= $badgeColor ?>; padding: 6px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold;">
                            <?= $statusText ?>
                        </span>
                    </td>

                    <td style="padding: 12px; text-align: center;">
                        <?php
                                $nextStatus = $user['status'] === 'active' ? 'locked' : 'active';
                                $btnText = $user['status'] === 'active' ? '🔒 Khóa' : '🔓 Mở';
                                $btnBg = $user['status'] === 'active' ? '#dc3545' : '#28a745';
                                ?>
                        <form method="POST" action="<?= $basePath ?>/quan-tri/tai-khoan/cap-nhat-status/"
                            style="display: inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <input type="hidden" name="status" value="<?= $nextStatus ?>">
                            <button type="submit" onclick="return confirm('Bạn có chắc chắn?')"
                                style="padding: 6px 12px; background: <?= $btnBg ?>; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 0.85em; transition: background 0.2s;">
                                <?= $btnText ?>
                            </button>
                        </form>

                        <form method="POST" action="<?= $basePath ?>/quan-tri/tai-khoan/xoa/"
                            style="display: inline; margin-left: 6px;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit"
                                onclick="return confirm('⚠️ XÓA TÀI KHOẢN\n\nHành động này KHÔNG THỂ HOÀN TÁC!\nTất cả bài viết, bình luận và dữ liệu liên quan sẽ bị xóa vĩnh viễn.\n\nBạn có chắc chắn muốn xóa tài khoản của: <?= htmlspecialchars(addslashes($user['full_name'])) ?>?')"
                                style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 0.85em; transition: background 0.2s; font-weight: bold;">
                                🗑️ Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div style="margin-top: 25px; display: flex; justify-content: center; gap: 6px; flex-wrap: wrap;">
        <?php if ($page > 1): ?>
        <a href="<?= $basePath ?>/quan-tri/tai-khoan/?page=<?= $page - 1 ?>&role_id=<?= $roleId ?>&search=<?= urlencode($search) ?>"
            style="padding: 8px 12px; background: #3498db; color: white; text-decoration: none; border-radius: 3px; transition: background 0.2s;">←
            Trước</a>
        <?php endif; ?>

        <?php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);

            if ($startPage > 1): ?>
        <a href="<?= $basePath ?>/quan-tri/tai-khoan/?page=1&role_id=<?= $roleId ?>&search=<?= urlencode($search) ?>"
            style="padding: 8px 12px; background: #f0f0f0; text-decoration: none; border-radius: 3px;">1</a>
        <?php if ($startPage > 2): ?>
        <span style="padding: 8px 12px;">...</span>
        <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
        <?php if ($i === $page): ?>
        <span
            style="padding: 8px 12px; background: #3498db; color: white; border-radius: 3px; font-weight: bold;"><?= $i ?></span>
        <?php else: ?>
        <a href="<?= $basePath ?>/quan-tri/tai-khoan/?page=<?= $i ?>&role_id=<?= $roleId ?>&search=<?= urlencode($search) ?>"
            style="padding: 8px 12px; background: #f0f0f0; text-decoration: none; border-radius: 3px; transition: background 0.2s;"><?= $i ?></a>
        <?php endif; ?>
        <?php endfor; ?>

        <?php if ($endPage < $totalPages): ?>
        <?php if ($endPage < $totalPages - 1): ?>
        <span style="padding: 8px 12px;">...</span>
        <?php endif; ?>
        <a href="<?= $basePath ?>/quan-tri/tai-khoan/?page=<?= $totalPages ?>&role_id=<?= $roleId ?>&search=<?= urlencode($search) ?>"
            style="padding: 8px 12px; background: #f0f0f0; text-decoration: none; border-radius: 3px;"><?= $totalPages ?></a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
        <a href="<?= $basePath ?>/quan-tri/tai-khoan/?page=<?= $page + 1 ?>&role_id=<?= $roleId ?>&search=<?= urlencode($search) ?>"
            style="padding: 8px 12px; background: #3498db; color: white; text-decoration: none; border-radius: 3px; transition: background 0.2s;">Sau
            →</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div
        style="margin-top: 25px; padding: 15px; background: #e7f3ff; border-left: 4px solid #3498db; border-radius: 4px; color: #0c5aa0;">
        📊 <strong>Tổng cộng:</strong> <?= $totalUsers ?> người dùng
        <?php if ($search || $roleId): ?>
        | <strong>Kết quả:</strong> <?= count($users) ?> người dùng
        <?php endif; ?>
    </div>
</div>

<?php
include dirname(__FILE__) . '/../layouts/footer-admin.php';
?>