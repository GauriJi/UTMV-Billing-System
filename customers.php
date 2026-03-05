<?php
require_once 'auth.php';
require_once 'database.php';
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'add') {
            $sql = "INSERT INTO customers (customer_name,contact_person,phone,email,address,city,state,pincode,gstin) VALUES (?,?,?,?,?,?,?,?,?)";
            $db->query($sql, [$_POST['customer_name'],$_POST['contact_person'],$_POST['phone'],$_POST['email'],$_POST['address'],$_POST['city'],$_POST['state'],$_POST['pincode'],$_POST['gstin']]);
            $_SESSION['success'] = "Customer added successfully!";
        } elseif ($_POST['action'] === 'delete') {
            $db->query("DELETE FROM customers WHERE id=?", [$_POST['id']]);
            $_SESSION['success'] = "Customer deleted successfully!";
        }
        header("Location: customers.php"); exit;
    } catch (Exception $e) { $error = "Error: " . $e->getMessage(); }
}

$customers = $db->fetchAll("SELECT * FROM customers ORDER BY customer_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-container">
    <?php include 'sidebar.php'; ?>
    <main class="main-content">
        <?php $page_title = 'Customer Management'; include 'topbar.php'; ?>
        <div class="content-wrapper">
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if(isset($error)): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>

            <div class="table-section">
                <div class="section-header">
                    <h3 class="section-title">All Customers</h3>
                    <button class="btn btn-primary" onclick="document.getElementById('addModal').style.display='flex'">+ Add Customer</button>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead><tr><th>Customer Name</th><th>Contact Person</th><th>Phone</th><th>Email</th><th>City, State</th><th>GSTIN</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach($customers as $c): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($c['customer_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($c['contact_person']); ?></td>
                                <td><?php echo htmlspecialchars($c['phone']); ?></td>
                                <td><?php echo htmlspecialchars($c['email']); ?></td>
                                <td><?php echo htmlspecialchars($c['city'].', '.$c['state']); ?></td>
                                <td><code><?php echo htmlspecialchars($c['gstin']); ?></code></td>
                                <td>
                                    <?php if(isAdmin()): ?>
                                    <form method="POST" style="display:inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                        <button type="submit" class="btn-icon" onclick="return confirm('Delete this customer?')" title="Delete">🗑️</button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($customers)): ?>
                            <tr><td colspan="7" class="no-data">No customers found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Modal -->
<div id="addModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:560px;max-width:95vw;max-height:90vh;overflow-y:auto;">
        <h3 style="margin-bottom:20px;font-size:18px;">➕ Add New Customer</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-grid">
                <div class="form-group"><label>Customer Name *</label><input type="text" name="customer_name" class="form-control" required></div>
                <div class="form-group"><label>Contact Person</label><input type="text" name="contact_person" class="form-control"></div>
                <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control"></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control"></div>
                <div class="form-group"><label>City</label><input type="text" name="city" class="form-control"></div>
                <div class="form-group"><label>State</label><input type="text" name="state" class="form-control"></div>
                <div class="form-group"><label>Pincode</label><input type="text" name="pincode" class="form-control"></div>
                <div class="form-group"><label>GSTIN</label><input type="text" name="gstin" class="form-control"></div>
                <div class="form-group full-width"><label>Address</label><textarea name="address" class="form-control"></textarea></div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">✅ Add Customer</button>
            </div>
        </form>
    </div>
</div>
<script src="script.js"></script>
</body>
</html>