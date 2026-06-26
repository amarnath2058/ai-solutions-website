<?php
session_start();

// 🔒 If not logged in, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin.html');
    exit;
}

$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AI Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            color: #1e293b;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100%;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-header img {
            height: 40px;
            width: auto;
        }

        .sidebar-header h2 {
            color: white;
            font-size: 20px;
            font-weight: 700;
        }

        .sidebar-header p {
            color: #94a3b8;
            font-size: 11px;
            margin-top: 2px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 24px;
            margin: 4px 16px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            cursor: pointer;
            font-weight: 500;
        }

        .menu-item i {
            width: 22px;
            font-size: 18px;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .menu-item.active {
            background: #ff6b35;
            color: white;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 16px;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        .page-title p {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
        }

        .notification-icon i {
            font-size: 22px;
            color: #64748b;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #ff6b35;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 20px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .admin-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #ff6b35, #f97316);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-avatar i {
            font-size: 20px;
            color: white;
        }

        .admin-details h4 {
            font-size: 14px;
            font-weight: 600;
        }

        .admin-details p {
            font-size: 12px;
            color: #64748b;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
            margin-top: 8px;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .stat-change.up {
            background: #d1fae5;
            color: #10b981;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .chart-card, .recent-card, .quick-actions {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .activity-item:hover {
            background: #f8fafc;
        }

        .activity-icon {
            width: 42px;
            height: 42px;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-icon i {
            font-size: 18px;
            color: #ff6b35;
        }

        .activity-details {
            flex: 1;
        }

        .activity-details h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .activity-details p {
            font-size: 12px;
            color: #64748b;
        }

        .activity-time {
            font-size: 11px;
            color: #94a3b8;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .action-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .action-card:hover {
            background: #ff6b35;
            transform: translateY(-3px);
        }

        .action-card i {
            font-size: 28px;
            color: #ff6b35;
            margin-bottom: 10px;
            display: block;
        }

        .action-card span {
            font-size: 13px;
            font-weight: 500;
        }

        .action-card:hover i,
        .action-card:hover span {
            color: white;
        }

        /* Content Cards */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        .content-type-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .content-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-color: #ff6b35;
        }

        .content-type-card i {
            font-size: 48px;
            color: #ff6b35;
            margin-bottom: 15px;
        }

        .content-type-card h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .content-type-card p {
            font-size: 13px;
            color: #64748b;
        }

        .content-list {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            margin-top: 20px;
        }

        .add-btn {
            background: #ff6b35;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .edit-btn, .delete-content-btn {
            padding: 5px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 3px;
        }

        .edit-btn {
            background: #3b82f6;
            color: white;
        }

        .delete-content-btn {
            background: #ef4444;
            color: white;
        }

        /* Tables */
        .inquiries-container, .accounts-container {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #1e293b;
        }

        .data-table tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-replied {
            background: #d1fae5;
            color: #10b981;
        }

        .reply-btn, .delete-btn {
            padding: 6px 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 0 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .reply-btn {
            background: #3b82f6;
            color: white;
        }

        .delete-btn {
            background: #ef4444;
            color: white;
        }

        .export-btn {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
        }

        .submit-btn {
            background: #ff6b35;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            font-size: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 600px;
            border-radius: 24px;
            padding: 28px;
        }

        .modal-content textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin: 15px 0;
            font-family: inherit;
        }

        .send-btn {
            background: #ff6b35;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
        }

        .close-modal {
            float: right;
            font-size: 24px;
            cursor: pointer;
            color: #94a3b8;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        @media (max-width: 1000px) {
            .sidebar {
                width: 80px;
            }
            .sidebar-header h2, .sidebar-header p, .menu-item span {
                display: none;
            }
            .sidebar-header img {
                margin: 0 auto;
            }
            .menu-item {
                justify-content: center;
                padding: 14px;
            }
            .main-content {
                margin-left: 80px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .two-column {
                grid-template-columns: 1fr;
            }
            .content-grid {
                grid-template-columns: 1fr;
            }
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .actions-grid {
                grid-template-columns: 1fr;
            }
            .gallery-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <img src="images/logo.png" alt="Logo" style="height: 40px;" onerror="this.src='https://placehold.co/40x40/ff6b35/white?text=AI'">
        <div>
            <h2>AI Solutions</h2>
            <p>Admin Panel</p>
        </div>
    </div>
    <div class="sidebar-menu">
        <div class="menu-item active" onclick="showSection('dashboard')">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </div>
        <div class="menu-item" onclick="showSection('inquiries')">
            <i class="fas fa-envelope"></i>
            <span>Inquiries</span>
        </div>
        <div class="menu-item" onclick="showSection('content')">
            <i class="fas fa-file-alt"></i>
            <span>Content</span>
        </div>
        <div class="menu-item" onclick="showSection('accounts')">
            <i class="fas fa-user-plus"></i>
            <span>Add Admin</span>
        </div>
        <div class="menu-divider"></div>
        <div class="menu-item" onclick="logout()">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="top-bar">
        <div class="page-title">
            <h1>Dashboard</h1>
            <p>Welcome back, <span id="adminName"><?php echo htmlspecialchars($adminName); ?></span></p>
        </div>
        <div class="user-info">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            <div class="admin-profile">
                <div class="admin-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="admin-details">
                    <h4 id="adminUserName"><?php echo htmlspecialchars($adminName); ?></h4>
                    <p>Administrator</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DASHBOARD SECTION -->
    <div id="dashboardSection">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: #fff7ed;">
                        <i class="fas fa-envelope" style="color: #ff6b35; font-size: 24px;"></i>
                    </div>
                    <span class="stat-change up">+12%</span>
                </div>
                <div class="stat-value" id="totalInquiries">0</div>
                <div class="stat-label">Total Inquiries</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: #ecfdf5;">
                        <i class="fas fa-check-circle" style="color: #10b981; font-size: 24px;"></i>
                    </div>
                    <span class="stat-change up">+8%</span>
                </div>
                <div class="stat-value" id="repliedCount">0</div>
                <div class="stat-label">Replied</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: #fffbeb;">
                        <i class="fas fa-clock" style="color: #f59e0b; font-size: 24px;"></i>
                    </div>
                    <span class="stat-change up">+5%</span>
                </div>
                <div class="stat-value" id="pendingCount">0</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: #f5f3ff;">
                        <i class="fas fa-users" style="color: #8b5cf6; font-size: 24px;"></i>
                    </div>
                    <span class="stat-change up">+3%</span>
                </div>
                <div class="stat-value" id="adminCount">0</div>
                <div class="stat-label">Total Admins</div>
            </div>
        </div>

        <div class="two-column">
            <div class="chart-card">
                <div class="card-header">
                    <h3>Inquiries Overview</h3>
                </div>
                <canvas id="inquiriesChart" height="220"></canvas>
            </div>
            <div class="recent-card">
                <div class="card-header">
                    <h3>Recent Activities</h3>
                    <a href="#" style="font-size:12px; color:#ff6b35;" onclick="showSection('inquiries'); return false;">View All</a>
                </div>
                <div class="activity-list" id="recentActivities">
                    <div style="text-align:center; padding:20px;">Loading...</div>
                </div>
            </div>
        </div>

        <div class="quick-actions">
            <div class="card-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="actions-grid">
                <div class="action-card" onclick="showSection('inquiries')">
                    <i class="fas fa-reply-all"></i>
                    <span>Reply to Inquiries</span>
                </div>
                <div class="action-card" onclick="showSection('content')">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Content</span>
                </div>
                <div class="action-card" onclick="showSection('accounts')">
                    <i class="fas fa-user-plus"></i>
                    <span>Add New Admin</span>
                </div>
                <div class="action-card" onclick="exportInquiriesCSV()">
                    <i class="fas fa-download"></i>
                    <span>Export Data</span>
                </div>
            </div>
        </div>
    </div>

    <!-- INQUIRIES SECTION -->
    <div id="inquiriesSection" style="display: none;">
        <div class="inquiries-container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>📋 Inquiry Management</h2>
                <button class="export-btn" onclick="exportInquiriesCSV()"><i class="fas fa-download"></i> Export CSV</button>
            </div>
            <div class="table-wrapper" style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr><th>ID</th><th>Date</th><th>Name</th><th>Email</th><th>Company</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="inquiriesTableBody">
                        <tr><td colspan="7" style="text-align:center; padding:40px;">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CONTENT MANAGEMENT SECTION -->
    <div id="contentSection" style="display: none;">
        <div class="content-grid">
            <div class="content-type-card" onclick="showContentType('articles')">
                <i class="fas fa-newspaper"></i>
                <h3>Articles / Blog</h3>
                <p>Manage blog posts and articles</p>
            </div>
            <div class="content-type-card" onclick="showContentType('gallery')">
                <i class="fas fa-images"></i>
                <h3>Gallery</h3>
                <p>Manage event photos and gallery</p>
            </div>
            <div class="content-type-card" onclick="showContentType('events')">
                <i class="fas fa-calendar-alt"></i>
                <h3>Events</h3>
                <p>Manage upcoming events</p>
            </div>
        </div>

        <!-- Articles Section -->
        <div id="articlesContent" style="display: none;">
            <div class="content-list">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3><i class="fas fa-newspaper"></i> Blog Articles</h3>
                    <button class="add-btn" onclick="openAddArticleModal()"><i class="fas fa-plus"></i> Add Article</button>
                </div>
                <table class="data-table">
                    <thead>
                        <tr><th>ID</th><th>Title</th><th>Category</th><th>Date</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="articlesTableBody">
                        <tr><td colspan="6" style="text-align:center; padding:40px;">Loading articles...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gallery Section -->
        <div id="galleryContent" style="display: none;">
            <div class="content-list">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3><i class="fas fa-images"></i> Gallery Images</h3>
                    <button class="add-btn" onclick="openAddGalleryModal()"><i class="fas fa-plus"></i> Add Image</button>
                </div>
                <div class="gallery-grid" id="galleryGrid">
                    <div style="text-align:center; padding:40px; grid-column: span 4;">Loading gallery...</div>
                </div>
            </div>
        </div>

        <!-- Events Section -->
        <div id="eventsContent" style="display: none;">
            <div class="content-list">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3><i class="fas fa-calendar-alt"></i> Upcoming Events</h3>
                    <button class="add-btn" onclick="openAddEventModal()"><i class="fas fa-plus"></i> Add Event</button>
                </div>
                <table class="data-table">
                    <thead>
                        <tr><th>ID</th><th>Event Name</th><th>Date</th><th>Location</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="eventsTableBody">
                        <tr><td colspan="6" style="text-align:center; padding:40px;">Loading events...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ACCOUNTS SECTION -->
    <div id="accountsSection" style="display: none;">
        <div class="accounts-container">
            <h2 style="margin-bottom: 20px;">👤 Add New Admin</h2>
            <form id="addAdminForm">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" id="adminFullName" required>
                </div>
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" id="adminEmail" required>
                </div>
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" id="adminUsername" required>
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" id="adminPassword" required>
                </div>
                <button type="submit" class="submit-btn">Create Admin Account</button>
            </form>
            <div id="adminFormMessage"></div>
        </div>
    </div>
</div>

<!-- Add Article Modal -->
<div id="addArticleModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeAddArticleModal()">&times;</span>
        <h3>Add New Article</h3>
        <form id="addArticleForm">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" id="articleTitle" required>
            </div>
            <div class="form-group">
                <label>Category *</label>
                <select id="articleCategory">
                    <option value="AI">AI</option>
                    <option value="Technology">Technology</option>
                    <option value="Business">Business</option>
                    <option value="Cloud">Cloud</option>
                </select>
            </div>
            <div class="form-group">
                <label>Content *</label>
                <textarea id="articleContent" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" id="articleImage" placeholder="https://images.unsplash.com/...">
            </div>
            <button type="submit" class="submit-btn">Publish Article</button>
        </form>
    </div>
</div>

<!-- Add Gallery Modal -->
<div id="addGalleryModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeAddGalleryModal()">&times;</span>
        <h3>Add Gallery Image</h3>
        <form id="addGalleryForm">
            <div class="form-group">
                <label>Image Title *</label>
                <input type="text" id="galleryTitle" required>
            </div>
            <div class="form-group">
                <label>Image URL *</label>
                <input type="text" id="galleryImageUrl" placeholder="https://images.unsplash.com/..." required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="galleryDescription" rows="3"></textarea>
            </div>
            <button type="submit" class="submit-btn">Add to Gallery</button>
        </form>
    </div>
</div>

<!-- Add Event Modal -->
<div id="addEventModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeAddEventModal()">&times;</span>
        <h3>Add New Event</h3>
        <form id="addEventForm">
            <div class="form-group">
                <label>Event Name *</label>
                <input type="text" id="eventName" required>
            </div>
            <div class="form-group">
                <label>Event Date *</label>
                <input type="date" id="eventDate" required>
            </div>
            <div class="form-group">
                <label>Location *</label>
                <input type="text" id="eventLocation" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="eventDescription" rows="3"></textarea>
            </div>
            <button type="submit" class="submit-btn">Add Event</button>
        </form>
    </div>
</div>

<!-- REPLY MODAL -->
<div id="replyModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeReplyModal()">&times;</span>
        <h3>Reply to Customer</h3>
        <p><strong>To:</strong> <span id="replyEmail"></span></p>
        <textarea id="replyMessage" rows="5" placeholder="Type your reply here..."></textarea>
        <button class="send-btn" onclick="sendReply()">Send Reply</button>
    </div>
</div>

<script>
    let chartInstance = null;
    let allInquiries = [];
    let currentReplyId = null;
    let currentContentType = 'articles';
    let allArticles = [];
    let allGallery = [];
    let allEvents = [];

    // ========== PAGE LOAD ==========
    window.onload = function() {
        initChart();
        loadInquiries();
        loadAdminCount();
        loadContentData();

        // Admin name is now set via PHP, so no need to set from sessionStorage
    }

    // ========== CHART ==========
    function initChart() {
        const ctx = document.getElementById('inquiriesChart').getContext('2d');
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Inquiries',
                    data: [0, 0, 0, 0, 0, 0],
                    borderColor: '#ff6b35',
                    backgroundColor: 'rgba(255, 107, 53, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ff6b35',
                    pointBorderColor: '#fff',
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } }
            }
        });
    }

    // ========== SECTION NAVIGATION ==========
    function showSection(section) {
        document.getElementById('dashboardSection').style.display = 'none';
        document.getElementById('inquiriesSection').style.display = 'none';
        document.getElementById('contentSection').style.display = 'none';
        document.getElementById('accountsSection').style.display = 'none';

        document.getElementById(section + 'Section').style.display = 'block';

        document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
        const menuMap = { 'dashboard': 0, 'inquiries': 1, 'content': 2, 'accounts': 3 };
        const menuItems = document.querySelectorAll('.menu-item');
        if (menuItems[menuMap[section]]) menuItems[menuMap[section]].classList.add('active');

        if (section === 'inquiries') loadInquiries();
        if (section === 'content') showContentType('articles');
    }

    // ========== INQUIRIES ==========
    async function loadInquiries() {
        try {
            const response = await fetch('api/get_inquiries.php');
            const result = await response.json();

            if (result.success && result.data) {
                allInquiries = result.data;
                updateStats();
                renderInquiriesTable();
                updateRecentActivities();
                updateChartData();
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }

    function updateStats() {
        const total = allInquiries.length;
        const pending = allInquiries.filter(i => i.status === 'pending').length;
        const replied = allInquiries.filter(i => i.status === 'replied').length;

        document.getElementById('totalInquiries').innerText = total;
        document.getElementById('pendingCount').innerText = pending;
        document.getElementById('repliedCount').innerText = replied;
    }

    function renderInquiriesTable() {
        const tbody = document.getElementById('inquiriesTableBody');
        if (allInquiries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:40px;">No inquiries yet</td></tr>';
            return;
        }

        tbody.innerHTML = '';
        allInquiries.forEach(inq => {
            const row = tbody.insertRow();
            row.insertCell(0).innerHTML = inq.id;
            row.insertCell(1).innerHTML = inq.submitted_at ? new Date(inq.submitted_at).toLocaleString() : '-';
            row.insertCell(2).innerHTML = inq.full_name || '-';
            row.insertCell(3).innerHTML = inq.email || '-';
            row.insertCell(4).innerHTML = inq.company || '-';
            row.insertCell(5).innerHTML = `<span class="status-badge status-${inq.status || 'pending'}">${inq.status || 'pending'}</span>`;
            row.insertCell(6).innerHTML = `
                <button class="reply-btn" onclick="openReplyModal(${inq.id}, '${inq.email}')">Reply</button>
                <button class="delete-btn" onclick="deleteInquiry(${inq.id})">Delete</button>
            `;
        });
    }

    function updateRecentActivities() {
        const container = document.getElementById('recentActivities');
        const recent = [...allInquiries].sort((a,b) => new Date(b.submitted_at) - new Date(a.submitted_at)).slice(0,4);

        if (recent.length === 0) {
            container.innerHTML = '<div style="text-align:center; padding:20px;">No recent activities</div>';
            return;
        }

        container.innerHTML = recent.map(inq => `
            <div class="activity-item">
                <div class="activity-icon"><i class="fas fa-envelope"></i></div>
                <div class="activity-details">
                    <h4>New inquiry from ${inq.full_name}</h4>
                    <p>${inq.email}</p>
                </div>
                <div class="activity-time">${timeAgo(new Date(inq.submitted_at))}</div>
            </div>
        `).join('');
    }

    function updateChartData() {
        if (!chartInstance) return;
        const monthCounts = [0, 0, 0, 0, 0, 0];
        allInquiries.forEach(inq => {
            if (inq.submitted_at) {
                const month = new Date(inq.submitted_at).getMonth();
                if (month >= 0 && month < 6) monthCounts[month]++;
            }
        });
        chartInstance.data.datasets[0].data = monthCounts;
        chartInstance.update();
    }

    function timeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        if (seconds < 60) return `${seconds} sec ago`;
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes} min ago`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours} hour ago`;
        const days = Math.floor(hours / 24);
        return `${days} days ago`;
    }

    async function loadAdminCount() {
        try {
            const response = await fetch('api/get_users.php');
            const result = await response.json();
            if (result.success) document.getElementById('adminCount').innerText = result.count;
        } catch (error) {}
    }

    function openReplyModal(id, email) {
        currentReplyId = id;
        document.getElementById('replyEmail').innerHTML = email;
        document.getElementById('replyModal').style.display = 'flex';
    }

    function closeReplyModal() {
        document.getElementById('replyModal').style.display = 'none';
        document.getElementById('replyMessage').value = '';
    }

    async function sendReply() {
        const message = document.getElementById('replyMessage').value;
        if (!message) { alert("Please enter a reply message"); return; }

        try {
            const response = await fetch('api/reply_inquiry.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: currentReplyId, message: message })
            });
            const result = await response.json();
            if (result.success) {
                alert('✅ Reply sent successfully!');
                closeReplyModal();
                loadInquiries();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Network error: ' + error.message);
        }
    }

    async function deleteInquiry(id) {
        if (confirm('Are you sure you want to delete this inquiry?')) {
            try {
                await fetch('api/delete_inquiry.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                loadInquiries();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
    }

    function exportInquiriesCSV() {
        if (allInquiries.length === 0) { alert("No data to export"); return; }

        let csv = "ID,Date,Name,Email,Phone,Company,Country,Job Title,Message,Status\n";
        allInquiries.forEach(inq => {
            csv += `"${inq.id}","${inq.submitted_at}","${inq.full_name || ''}","${inq.email || ''}","${inq.phone || ''}","${inq.company || ''}","${inq.country || ''}","${inq.job_title || ''}","${(inq.job_details || '').replace(/"/g, '""')}","${inq.status || 'pending'}"\n`;
        });

        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `inquiries_${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // ========== CONTENT MANAGEMENT ==========
    function showContentType(type) {
        currentContentType = type;
        document.getElementById('articlesContent').style.display = 'none';
        document.getElementById('galleryContent').style.display = 'none';
        document.getElementById('eventsContent').style.display = 'none';

        if (type === 'articles') {
            document.getElementById('articlesContent').style.display = 'block';
            loadArticles();
        } else if (type === 'gallery') {
            document.getElementById('galleryContent').style.display = 'block';
            loadGallery();
        } else if (type === 'events') {
            document.getElementById('eventsContent').style.display = 'block';
            loadEvents();
        }
    }

    async function loadContentData() {
        await loadArticles();
        await loadGallery();
        await loadEvents();
    }

    async function loadArticles() {
        try {
            const response = await fetch('api/get_articles.php');
            const result = await response.json();
            if (result.success && result.data) {
                allArticles = result.data;
                renderArticlesTable();
            }
        } catch (error) {
            document.getElementById('articlesTableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding:40px;">Error loading articles</td></tr>';
        }
    }

    function renderArticlesTable() {
        const tbody = document.getElementById('articlesTableBody');
        if (allArticles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:40px;">No articles yet. Click "Add Article" to create one.</td></tr>';
            return;
        }
        tbody.innerHTML = '';
        allArticles.forEach(article => {
            const row = tbody.insertRow();
            row.insertCell(0).innerHTML = article.id;
            row.insertCell(1).innerHTML = article.title || '-';
            row.insertCell(2).innerHTML = article.category || '-';
            row.insertCell(3).innerHTML = article.created_at ? new Date(article.created_at).toLocaleDateString() : '-';
            row.insertCell(4).innerHTML = '<span class="status-badge status-replied">Published</span>';
            row.insertCell(5).innerHTML = `
                <button class="delete-content-btn" onclick="deleteArticle(${article.id})">Delete</button>
            `;
        });
    }

    function openAddArticleModal() {
        document.getElementById('addArticleModal').style.display = 'flex';
    }

    function closeAddArticleModal() {
        document.getElementById('addArticleModal').style.display = 'none';
        document.getElementById('addArticleForm').reset();
    }

    document.getElementById('addArticleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const title = document.getElementById('articleTitle').value;
        const category = document.getElementById('articleCategory').value;
        const content = document.getElementById('articleContent').value;
        const image = document.getElementById('articleImage').value;

        try {
            const response = await fetch('api/add_article.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title, category, content, image })
            });
            const result = await response.json();
            if (result.success) {
                alert('✅ Article published!');
                closeAddArticleModal();
                loadArticles();
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });

    async function deleteArticle(id) {
        if (confirm('Delete this article?')) {
            await fetch('api/delete_article.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            loadArticles();
        }
    }

    async function loadGallery() {
        try {
            const response = await fetch('api/get_gallery.php');
            const result = await response.json();
            if (result.success && result.data) {
                allGallery = result.data;
                renderGalleryGrid();
            }
        } catch (error) {
            document.getElementById('galleryGrid').innerHTML = '<div style="text-align:center; padding:40px;">Error loading gallery</div>';
        }
    }

    function renderGalleryGrid() {
        const grid = document.getElementById('galleryGrid');
        if (allGallery.length === 0) {
            grid.innerHTML = '<div style="text-align:center; padding:40px; grid-column: span 4;">No images in gallery. Click "Add Image" to upload.</div>';
            return;
        }
        grid.innerHTML = '';
        allGallery.forEach(img => {
            const div = document.createElement('div');
            div.style.cssText = 'background: #f8fafc; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;';
            div.innerHTML = `
                <img src="${img.image_url}" alt="${img.title}" style="width: 100%; height: 180px; object-fit: cover;">
                <div style="padding: 12px;">
                    <h4 style="font-size: 14px; margin-bottom: 5px;">${img.title}</h4>
                    <button class="delete-content-btn" onclick="deleteGalleryItem(${img.id})" style="width: 100%; margin-top: 10px;">Delete</button>
                </div>
            `;
            grid.appendChild(div);
        });
    }

    function openAddGalleryModal() {
        document.getElementById('addGalleryModal').style.display = 'flex';
    }

    function closeAddGalleryModal() {
        document.getElementById('addGalleryModal').style.display = 'none';
        document.getElementById('addGalleryForm').reset();
    }

    document.getElementById('addGalleryForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const title = document.getElementById('galleryTitle').value;
        const image_url = document.getElementById('galleryImageUrl').value;
        const description = document.getElementById('galleryDescription').value;

        try {
            const response = await fetch('api/add_gallery.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title, image_url, description })
            });
            if (result.success) {
                alert('✅ Image added!');
                closeAddGalleryModal();
                loadGallery();
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });

    async function deleteGalleryItem(id) {
        if (confirm('Delete this image?')) {
            await fetch('api/delete_gallery.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            loadGallery();
        }
    }

    async function loadEvents() {
        try {
            const response = await fetch('api/get_events.php');
            const result = await response.json();
            if (result.success && result.data) {
                allEvents = result.data;
                renderEventsTable();
            }
        } catch (error) {
            document.getElementById('eventsTableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding:40px;">Error loading events</td></tr>';
        }
    }

    function renderEventsTable() {
        const tbody = document.getElementById('eventsTableBody');
        if (allEvents.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:40px;">No events yet. Click "Add Event" to create one.</td></tr>';
            return;
        }
        tbody.innerHTML = '';
        allEvents.forEach(event => {
            const today = new Date();
            const eventDate = new Date(event.event_date);
            const status = eventDate > today ? 'Upcoming' : 'Past';
            const row = tbody.insertRow();
            row.insertCell(0).innerHTML = event.id;
            row.insertCell(1).innerHTML = event.name || '-';
            row.insertCell(2).innerHTML = event.event_date || '-';
            row.insertCell(3).innerHTML = event.location || '-';
            row.insertCell(4).innerHTML = `<span class="status-badge ${status === 'Upcoming' ? 'status-pending' : 'status-replied'}">${status}</span>`;
            row.insertCell(5).innerHTML = `<button class="delete-content-btn" onclick="deleteEvent(${event.id})">Delete</button>`;
        });
    }

    function openAddEventModal() {
        document.getElementById('addEventModal').style.display = 'flex';
    }

    function closeAddEventModal() {
        document.getElementById('addEventModal').style.display = 'none';
        document.getElementById('addEventForm').reset();
    }

    document.getElementById('addEventForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const name = document.getElementById('eventName').value;
        const event_date = document.getElementById('eventDate').value;
        const location = document.getElementById('eventLocation').value;
        const description = document.getElementById('eventDescription').value;

        try {
            const response = await fetch('api/add_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, event_date, location, description })
            });
            if (result.success) {
                alert('✅ Event added!');
                closeAddEventModal();
                loadEvents();
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });

    async function deleteEvent(id) {
        if (confirm('Delete this event?')) {
            await fetch('api/delete_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            loadEvents();
        }
    }

    // ========== ADD ADMIN ==========
    document.getElementById('addAdminForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const fullName = document.getElementById('adminFullName').value;
        const email = document.getElementById('adminEmail').value;
        const username = document.getElementById('adminUsername').value;
        const password = document.getElementById('adminPassword').value;

        const messageDiv = document.getElementById('adminFormMessage');
        messageDiv.innerHTML = '<div style="background:#d1fae5; color:#10b981; padding:12px; border-radius:8px; margin-top:15px;">⏳ Creating admin account...</div>';

        try {
            const response = await fetch('api/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ fullName, email, username, password })
            });
            const result = await response.json();

            if (result.success) {
                messageDiv.innerHTML = '<div style="background:#d1fae5; color:#10b981; padding:12px; border-radius:8px; margin-top:15px;">✅ Admin created successfully! Email sent.</div>';
                document.getElementById('addAdminForm').reset();
                loadAdminCount();
                setTimeout(() => showSection('dashboard'), 2000);
            } else {
                messageDiv.innerHTML = '<div style="background:#fee2e2; color:#ef4444; padding:12px; border-radius:8px; margin-top:15px;">❌ ' + result.message + '</div>';
            }
        } catch (error) {
            messageDiv.innerHTML = '<div style="background:#fee2e2; color:#ef4444; padding:12px; border-radius:8px; margin-top:15px;">❌ Network error: ' + error.message + '</div>';
        }
    });

    // ========== LOGOUT – fixed to use PHP logout ==========
    function logout() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = 'api/logout.php';
        }
    }

    window.onclick = function(event) {
        if (event.target === document.getElementById('replyModal')) closeReplyModal();
        if (event.target === document.getElementById('addArticleModal')) closeAddArticleModal();
        if (event.target === document.getElementById('addGalleryModal')) closeAddGalleryModal();
        if (event.target === document.getElementById('addEventModal')) closeAddEventModal();
    }
</script>
</body>
</html>