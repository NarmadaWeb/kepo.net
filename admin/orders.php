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
        <h2 style="font-size: 18px;">Manajemen Pesanan</h2>
        <div style="display: flex; gap: 10px;">
            <input type="text" placeholder="Cari ID, Nama..." style="padding: 8px 15px; width: 250px;">
            <button class="btn btn-outline"><span class="material-symbols-outlined">filter_list</span> Filter</button>
        </div>
    </div>

    <div style="padding: 30px 0;">
        <div class="card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Teknisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td style="font-family: 'JetBrains Mono'; font-size: 12px;">#<?= $order['order_number'] ?></td>
                                <td>
                                    <strong><?= $order['user_name'] ?></strong><br>
                                    <small><?= $order['user_phone'] ?></small>
                                </td>
                                <td><?= $order['package_name'] ?></td>
                                <td style="max-width: 200px; font-size: 13px; color: var(--text-secondary);"><?= $order['address'] ?></td>
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
                                        <span style="font-size: 13px;"><?= $order['technician_name'] ?></span>
                                    <?php elseif (in_array($order['status'], ['paid', 'processing'])): ?>
                                        <a href="assign_technician.php?order_id=<?= $order['id'] ?>" class="btn btn-primary" style="padding: 4px 8px; font-size: 11px;">Assign</a>
                                    <?php else: ?>
                                        <span style="color: var(--text-secondary); font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="" method="POST" style="display: flex; gap: 5px;">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" style="padding: 4px; font-size: 12px; width: 110px;">
                                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="waiting_payment" <?= $order['status'] == 'waiting_payment' ? 'selected' : '' ?>>Wait Pay</option>
                                            <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                            <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Process</option>
                                            <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Done</option>
                                            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancel</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-outline" style="padding: 4px;"><span class="material-symbols-outlined" style="font-size: 16px;">save</span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
