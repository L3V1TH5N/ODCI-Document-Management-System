<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth_check.php';
include 'assets/script/social_feed-script.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Feed - CVSU NAIC</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* All the CSS styles from your original file */
        :root {
            --post-bg: #ffffff;
            --post-border: #e1e8ed;
            --text-primary: #14171a;
            --text-secondary: #657786;
            --blue-primary: #1da1f2;
            --blue-hover: #0d8bd9;
            --green-primary: #17bf63;
            --red-primary: #e0245e;
            --orange-primary: #f45d22;
        }

        .feed-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--light);
            border-radius: 20px;
            overflow: hidden;
        }

        .feed-header {
            background: linear-gradient(135deg, var(--blue), var(--light-blue));
            color: white;
            padding: 20px;
            text-align: center;
        }

        .feed-tabs {
            display: flex;
            background: var(--post-bg);
            border-bottom: 1px solid var(--post-border);
            overflow-x: auto;
        }

        .feed-tab {
            padding: 15px 20px;
            border: none;
            background: transparent;
            cursor: pointer;
            white-space: nowrap;
            color: var(--text-secondary);
            font-weight: 500;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .feed-tab:hover {
            background: #f7f9fa;
            color: var(--text-primary);
        }

        .feed-tab.active {
            color: var(--blue-primary);
            border-bottom-color: var(--blue-primary);
        }

        .post-composer {
            background: var(--post-bg);
            border-bottom: 1px solid var(--post-border);
            padding: 20px;
        }

        .composer-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .composer-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .composer-textarea {
            width: 100%;
            border: none;
            resize: none;
            font-size: 16px;
            font-family: inherit;
            padding: 12px;
            border-radius: 12px;
            background: #f7f9fa;
            min-height: 80px;
            outline: none;
            transition: all 0.3s ease;
        }

        .composer-textarea:focus {
            background: white;
            box-shadow: 0 0 0 2px var(--blue-primary);
        }

        .composer-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .composer-tools {
            display: flex;
            gap: 10px;
        }

        .composer-tool {
            padding: 8px 12px;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .composer-tool:hover {
            background: #e8f4fd;
            color: var(--blue-primary);
        }

        .post-btn {
            background: var(--blue-primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .post-btn:hover {
            background: var(--blue-hover);
        }

        .post-btn:disabled {
            background: #aaa;
            cursor: not-allowed;
        }

        .visibility-selector {
            position: relative;
            display: inline-block;
        }

        .visibility-btn {
            padding: 6px 12px;
            border: 1px solid var(--post-border);
            background: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .visibility-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid var(--post-border);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            z-index: 100;
            min-width: 200px;
            display: none;
        }

        .visibility-dropdown.show {
            display: block;
        }

        .visibility-option {
            padding: 12px 16px;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #f1f3f4;
        }

        .visibility-option:last-child {
            border-bottom: none;
        }

        .visibility-option:hover {
            background: #f7f9fa;
        }

        .visibility-option i {
            color: var(--blue-primary);
        }

        .feed-content {
            background: var(--light);
        }

        .post {
            background: var(--post-bg);
            border-bottom: 1px solid var(--post-border);
            padding: 20px;
            transition: background 0.2s ease;
        }

        .post:hover {
            background: #fafafa;
        }

        .post-header {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 15px;
        }

        .post-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .post-author-info {
            flex: 1;
            min-width: 0;
        }

        .post-author-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
            font-size: 15px;
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .post-badge {
            background: var(--blue-primary);
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 500;
        }

        .post-badge.department {
            background: var(--green-primary);
        }

        .post-badge.high {
            background: var(--orange-primary);
        }

        .post-badge.urgent {
            background: var(--red-primary);
        }

        .post-actions-menu {
            position: relative;
        }

        .post-menu-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .post-menu-btn:hover {
            background: #e8f4fd;
            color: var(--blue-primary);
        }

        .post-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--post-border);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 50;
            min-width: 150px;
            display: none;
        }

        .post-menu.show {
            display: block;
        }

        .post-menu-item {
            padding: 10px 16px;
            cursor: pointer;
            transition: background 0.2s ease;
            border-bottom: 1px solid #f1f3f4;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .post-menu-item:last-child {
            border-bottom: none;
        }

        .post-menu-item:hover {
            background: #f7f9fa;
        }

        .post-menu-item.danger {
            color: var(--red-primary);
        }

        .post-content {
            margin-bottom: 15px;
            line-height: 1.6;
            color: var(--text-primary);
            font-size: 15px;
            white-space: pre-wrap;
        }

        .post-media {
            margin-bottom: 15px;
            border-radius: 12px;
            overflow: hidden;
        }

        .post-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .post-image:hover {
            transform: scale(1.02);
        }

        .post-file {
            background: #f7f9fa;
            border: 1px solid var(--post-border);
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .post-file:hover {
            background: #e8f4fd;
        }

        .post-file-icon {
            font-size: 20px;
            color: var(--blue-primary);
        }

        .post-file-info {
            flex: 1;
        }

        .post-file-name {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .post-file-size {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .post-link-preview {
            border: 1px solid var(--post-border);
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .post-link-preview:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .post-link-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .post-link-content {
            padding: 12px;
        }

        .post-link-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .post-link-description {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.4;
        }

        .post-engagement {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--post-border);
            margin-bottom: 8px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .post-stats {
            display: flex;
            gap: 15px;
        }

        .post-actions {
            display: flex;
            justify-content: space-around;
            padding: 4px 0;
        }

        .post-action {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: none;
            background: none;
            cursor: pointer;
            border-radius: 20px;
            transition: all 0.2s ease;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .post-action:hover {
            background: #f7f9fa;
        }

        .post-action.liked {
            color: var(--red-primary);
        }

        .post-action.commented {
            color: var(--blue-primary);
        }

        .comment-section {
            margin-top: 15px;
            border-top: 1px solid var(--post-border);
            padding-top: 15px;
        }

        .comment {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .comment:hover {
            background: #f7f9fa;
        }

        .comment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .comment-content {
            flex: 1;
            min-width: 0;
        }

        .comment-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .comment-author {
            font-weight: 600;
            font-size: 13px;
            color: var(--text-primary);
        }

        .comment-time {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .comment-text {
            font-size: 14px;
            line-height: 1.4;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .comment-actions {
            display: flex;
            gap: 15px;
            font-size: 12px;
        }

        .comment-action {
            color: var(--text-secondary);
            cursor: pointer;
            padding: 2px 0;
            transition: color 0.2s ease;
        }

        .comment-action:hover {
            color: var(--blue-primary);
        }

        .comment-form {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f1f3f4;
        }

        .comment-input {
            flex: 1;
            border: 1px solid var(--post-border);
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .comment-input:focus {
            border-color: var(--blue-primary);
        }

        .comment-submit {
            background: var(--blue-primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .comment-submit:hover {
            background: var(--blue-hover);
        }

        .load-more {
            text-align: center;
            padding: 20px;
        }

        .load-more-btn {
            background: transparent;
            border: 2px solid var(--blue-primary);
            color: var(--blue-primary);
            padding: 12px 24px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .load-more-btn:hover {
            background: var(--blue-primary);
            color: white;
        }

        .no-posts {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .no-posts i {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
            color: #ddd;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: var(--text-secondary);
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--blue-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .reaction-picker {
            position: absolute;
            bottom: 100%;
            left: 0;
            background: white;
            border: 1px solid var(--post-border);
            border-radius: 25px;
            padding: 8px;
            display: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10;
        }

        .reaction-picker.show {
            display: flex;
            animation: slideUp 0.2s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reaction-option {
            width: 32px;
            height: 32px;
            border: none;
            background: none;
            cursor: pointer;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: transform 0.2s ease;
        }

        .reaction-option:hover {
            transform: scale(1.3);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .feed-container {
                margin: 0;
                border-radius: 0;
            }

            .post {
                padding: 15px;
            }

            .post-header {
                gap: 10px;
            }

            .post-avatar, .composer-avatar {
                width: 40px;
                height: 40px;
            }

            .composer-textarea {
                min-height: 60px;
            }

            .post-actions {
                justify-content: space-between;
            }

            .post-action {
                padding: 8px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-dashboard'></i>
            <span class="text">ODCI Admin</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="users.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Users</span>
                </a>
            </li>
            <li>
                <a href="departments.php">
                    <i class='bx bxs-buildings'></i>
                    <span class="text">Departments</span>
                </a>
            </li>
            <li>
                <a href="files.php">
                    <i class='bx bxs-file'></i>
                    <span class="text">Files</span>
                </a>
            </li>
            <li>
                <a href="folders.php">
                    <i class='bx bxs-folder'></i>
                    <span class="text">Folders</span>
                </a>
            </li>
            <li class="active">
                <a href="social_feed.php">
                    <i class='bx bx-news'></i>
                    <span class="text">Social Feed</span>
                </a>
            </li>
            <li>
                <a href="reports.php">
                    <i class='bx bxs-report'></i>
                    <span class="text">Reports</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="settings.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">System Settings</span>
                </a>
            </li>
            <li>
                <a href="activity_logs.php">
                    <i class='bx bxs-time'></i>
                    <span class="text">Activity Logs</span>
                </a>
            </li>
            <li>
                <a href="../../logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <!-- Content -->
    <section id="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Social Feed</a>
            <form action="#" onsubmit="searchPosts(event)">
                <div class="form-input">
                    <input type="search" id="searchInput" placeholder="Search posts...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num" id="notificationCount">0</span>
            </a>
            <a href="#" class="profile">
                <img src="assets/img/default-avatar.png" alt="Profile" id="userAvatar">
            </a>
        </nav>

        <!-- Main Content -->
        <main>
            <div class="feed-container">
                <!-- Feed Header -->
                <div class="feed-header">
                    <h2><i class='bx bx-news'></i> Social Feed</h2>
                    <p>Connect, share, and collaborate with your colleagues</p>
                </div>

                <!-- Feed Tabs -->
                <div class="feed-tabs">
                    <button class="feed-tab active" data-filter="all">
                        <i class='bx bx-home'></i> All Posts
                    </button>
                    <button class="feed-tab" data-filter="my_posts">
                        <i class='bx bx-user'></i> My Posts
                    </button>
                    <button class="feed-tab" data-filter="department">
                        <i class='bx bx-buildings'></i> Department
                    </button>
                    <button class="feed-tab" data-filter="pinned">
                        <i class='bx bx-pin'></i> Pinned
                    </button>
                    <button class="feed-tab" data-filter="trending">
                        <i class='bx bx-trending-up'></i> Trending
                    </button>
                </div>

                <!-- Post Composer -->
                <div class="post-composer">
                    <form id="postForm" onsubmit="createPost(event)">
                        <div class="composer-header">
                            <img src="assets/img/default-avatar.png" alt="Your avatar" class="composer-avatar" id="composerAvatar">
                            <div>
                                <strong id="composerName">Loading...</strong>
                                <div class="visibility-selector">
                                    <button type="button" class="visibility-btn" id="visibilityBtn">
                                        <i class='bx bx-globe'></i> Everyone
                                        <i class='bx bx-chevron-down'></i>
                                    </button>
                                    <div class="visibility-dropdown" id="visibilityDropdown">
                                        <div class="visibility-option" data-visibility="public">
                                            <i class='bx bx-globe'></i>
                                            <div>
                                                <strong>Everyone</strong>
                                                <div style="font-size: 12px; color: var(--text-secondary);">Visible to all users</div>
                                            </div>
                                        </div>
                                        <div class="visibility-option" data-visibility="department">
                                            <i class='bx bx-buildings'></i>
                                            <div>
                                                <strong>Department</strong>
                                                <div style="font-size: 12px; color: var(--text-secondary);">Only your department</div>
                                            </div>
                                        </div>
                                        <div class="visibility-option" data-visibility="custom">
                                            <i class='bx bx-group'></i>
                                            <div>
                                                <strong>Specific Users</strong>
                                                <div style="font-size: 12px; color: var(--text-secondary);">Choose who can see</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <textarea 
                            class="composer-textarea" 
                            id="postContent" 
                            placeholder="What's happening in your department?"
                            required
                        ></textarea>
                        <div class="composer-actions">
                            <div class="composer-tools">
                                <button type="button" class="composer-tool" title="Add Image" onclick="document.getElementById('imageInput').click()">
                                    <i class='bx bx-image'></i>
                                </button>
                                <button type="button" class="composer-tool" title="Add File" onclick="document.getElementById('fileInput').click()">
                                    <i class='bx bx-paperclip'></i>
                                </button>
                                <button type="button" class="composer-tool" title="Add Link" onclick="toggleLinkInput()">
                                    <i class='bx bx-link'></i>
                                </button>
                                <button type="button" class="composer-tool" title="Set Priority" onclick="togglePrioritySelector()">
                                    <i class='bx bx-flag'></i>
                                </button>
                            </div>
                            <button type="submit" class="post-btn" id="postBtn" disabled>
                                <i class='bx bx-send'></i> Post
                            </button>
                        </div>
                        <input type="file" id="imageInput" accept="image/*" style="display: none;" onchange="handleImageUpload(this)">
                        <input type="file" id="fileInput" style="display: none;" onchange="handleFileUpload(this)">
                        <input type="hidden" id="postVisibility" value="public">
                        <input type="hidden" id="postPriority" value="normal">
                    </form>
                </div>

                <!-- Feed Content -->
                <div class="feed-content" id="feedContent">
                    <div class="loading">
                        <div class="spinner"></div>
                        <p>Loading posts...</p>
                    </div>
                </div>

                <!-- Load More -->
                <div class="load-more" id="loadMoreSection" style="display: none;">
                    <button class="load-more-btn" onclick="loadMorePosts()">
                        <i class='bx bx-refresh'></i> Load More Posts
                    </button>
                </div>
            </div>
        </main>
    </section>

    <script src="assets/js/script.js"></script>
    <script src="assets/js/social-feed.js"></script>
</body>
</html>
