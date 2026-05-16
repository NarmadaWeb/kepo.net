<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Handle Status Update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    header('Location: orders.php?updated=1');
    exit;
}

// Fetch Orders
$stmt = $pdo->query("SELECT o.*, u.name as user_name, u.phone as user_phone, p.name as package_name, t.name as technician_name
                     FROM orders o
                     JOIN users u ON o.user_id = u.id
                     JOIN packages p ON o.package_id = p.id
                     LEFT JOIN technicians t ON o.technician_id = t.id
                     ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h1 style="font-size: 24px; letter-spacing: -0.025em;">Manajemen Pesanan</h1>
        <div class="flex gap-10">
            <input type="text" placeholder="Cari pesanan..." style="width: 280px;">
            <button class="btn btn-outline"><span class="material-symbols-outlined">filter_list</span></button>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-container" style="border: none; border-radius: 0;">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Teknisi</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: var(--primary);">#<?= $order['order_number'] ?></td>
                            <td>
                                <div style="font-weight: 600;"><?= $order['user_name'] ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><?= $order['user_phone'] ?></div>
                            </td>
                            <td><strong><?= $order['package_name'] ?></strong></td>
                            <td>
                                <?php
                                $status_badges = [
                                    'pending' => 'badge-pending',
                                    'waiting_payment' => 'badge-pending',
                                    'paid' => 'badge-info',
                                    'processing' => 'badge-info',
                                    'completed' => 'badge-success',
                                    'cancelled' => 'badge-error'
                                ];
                                ?>
                                <span class="badge <?= $status_badges[$order['status']] ?>"><?= $order['status'] ?></span>
                            </td>
                            <td>
                                <?php if ($order['technician_name']): ?>
                                    <div class="flex items-center gap-10">
                                        <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--success);"></div>
                                        <span style="font-size: 13px; font-weight: 500;"><?= $order['technician_name'] ?></span>
                                    </div>
                                <?php elseif (in_array($order['status'], ['paid', 'processing'])): ?>
                                    <a href="assign_technician.php?order_id=<?= $order['id'] ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 11px;">Assign</a>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 12px;">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="" method="POST" class="flex gap-10">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="status" style="padding: 6px; font-size: 12px; width: 130px; background-color: var(--bg-main);">
                                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="waiting_payment" <?= $order['status'] == 'waiting_payment' ? 'selected' : '' ?>>Wait Pay</option>
                                        <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-outline" style="padding: 6px;"><span class="material-symbols-outlined" style="font-size: 18px;">save</span></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
