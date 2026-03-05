<?php
// topbar.php — include this at the top of <main class="main-content"> on every page
// It renders the sticky top bar with page title + logout button
// Set $page_title before including, or pass nothing for default

$_topbar_title = $page_title ?? basename($_SERVER['PHP_SELF'], '.php');
?>
<header class="top-bar">
    <h2 class="page-title"><?php echo htmlspecialchars($_topbar_title); ?></h2>
    <div style="display:flex;align-items:center;gap:16px;">
        <span style="color:#64748b;font-size:14px;font-weight:500;">
            <?php echo date('D, d M Y'); ?>
        </span>
        <span style="color:#94a3b8;font-size:13px;font-weight:600;">
            👤 <?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?>
        </span>
        <a href="logout.php"
           onclick="return confirm('Are you sure you want to logout?')"
           style="display:inline-flex;align-items:center;gap:6px;
                  padding:8px 18px;background:#fee2e2;color:#991b1b;
                  border-radius:8px;font-size:13px;font-weight:700;
                  text-decoration:none;border:2px solid #fecaca;transition:all 0.2s;"
           onmouseover="this.style.background='#fecaca';this.style.transform='translateY(-1px)'"
           onmouseout="this.style.background='#fee2e2';this.style.transform='none'">
            🚪 Logout
        </a>
    </div>
</header>