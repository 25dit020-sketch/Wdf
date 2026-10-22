<?php
// Practical 15: Final System Integration Master Hub
session_start();
require_once 'db.php';

// Quick DB diagnostic counts
$userCount = 0;
$studentCount = 0;
$eventCount = 0;

if ($dbConnected) {
    $uRes = mysqli_query($conn, "SELECT COUNT(*) as c FROM users");
    if ($uRes) $userCount = mysqli_fetch_assoc($uRes)['c'];

    $sRes = mysqli_query($conn, "SELECT COUNT(*) as c FROM students");
    if ($sRes) $studentCount = mysqli_fetch_assoc($sRes)['c'];

    $eRes = mysqli_query($conn, "SELECT COUNT(*) as c FROM events");
    if ($eRes) $eventCount = mysqli_fetch_assoc($eRes)['c'];
}

// 15 Practicals Data Structure
$practicals = [
    [
        "num" => "Practical 01",
        "title" => "Project Initiation, Wireframes & Sitemap",
        "desc" => "Project charter, 10-page sitemap, and interactive low-fidelity wireframes for StudentHub.",
        "tags" => ["HTML5", "Wireframes", "Sitemap", "UI Design"],
        "launch" => "../Practical-01-Project-Initiation/sitemap.html"
    ],
    [
        "num" => "Practical 02",
        "title" => "Semantic HTML5 Structure & Accessibility",
        "desc" => "11 fully semantic portal pages with ARIA roles, breadcrumb navigation, and web accessibility.",
        "tags" => ["Semantic HTML5", "Accessibility", "ARIA", "Breadcrumbs"],
        "launch" => "../Practical-02-Semantic-HTML5/index.html"
    ],
    [
        "num" => "Practical 03",
        "title" => "Responsive Layouts (Grid & Flexbox)",
        "desc" => "Modern responsive layouts using Flexbox navbars and CSS Grid cards across mobile breakpoints.",
        "tags" => ["CSS3", "Flexbox", "CSS Grid", "Media Queries"],
        "launch" => "../Practical-03-Responsive-UI/index.html"
    ],
    [
        "num" => "Practical 04",
        "title" => "DOM Manipulation & Client Interactivity",
        "desc" => "Dark/Light theme toggle with localStorage, accordions, dynamic sliders, and modal popups.",
        "tags" => ["JavaScript", "DOM", "localStorage", "Event Listeners"],
        "launch" => "../Practical-04-JavaScript-DOM/index.html"
    ],
    [
        "num" => "Practical 05",
        "title" => "Client-Side Form Validation & CAPTCHA",
        "desc" => "Regular expression validation, real-time password strength meter, and HTML5 canvas CAPTCHA.",
        "tags" => ["JavaScript", "RegEx", "Form Validation", "Canvas CAPTCHA"],
        "launch" => "../Practical-05-Form-Validation/index.html"
    ],
    [
        "num" => "Practical 06",
        "title" => "Fetch API & Asynchronous JSON Data",
        "desc" => "Asynchronously loading campus events, student directories, and FAQs with search and pagination.",
        "tags" => ["Fetch API", "Promises", "JSON", "Client Pagination"],
        "launch" => "../Practical-06-Fetch-JSON/index.html"
    ],
    [
        "num" => "Practical 07",
        "title" => "Server-Side Form Processing & File Storage",
        "desc" => "PHP input sanitization and persistence across flat files (CSV and JSON) with a records table viewer.",
        "tags" => ["PHP", "Sanitization", "CSV Storage", "JSON Storage"],
        "launch" => "../Practical-07-PHP-Form-Processing/index.php"
    ],
    [
        "num" => "Practical 08",
        "title" => "Relational Database Schema & MySQL Connectivity",
        "desc" => "Normalized 4-table relational schema with foreign key constraints, seed data, and diagnostics.",
        "tags" => ["MySQL", "Schema Design", "Foreign Keys", "mysqli"],
        "launch" => "../Practical-08-MySQL-Schema-Connection/test_db.php"
    ],
    [
        "num" => "Practical 09",
        "title" => "Secure Registration & Password Hashing",
        "desc" => "User registration with Bcrypt password hashing, duplicate email/roll checks, and transactions.",
        "tags" => ["PHP", "password_hash", "Bcrypt", "Transactions"],
        "launch" => "../Practical-09-Secure-Registration/register.php"
    ],
    [
        "num" => "Practical 10",
        "title" => "Authentication, Sessions & RBAC",
        "desc" => "Secure login with session regeneration, password_verify, and role routing (Admin vs Student).",
        "tags" => ["PHP Sessions", "RBAC", "password_verify", "Security"],
        "launch" => "../Practical-10-Secure-Auth-Sessions/login.php"
    ],
    [
        "num" => "Practical 11",
        "title" => "Student Record CRUD & CSV Export",
        "desc" => "Complete CRUD interface with live keyword search, department filtering, and on-demand CSV stream.",
        "tags" => ["CRUD", "Prepared Statements", "CSV Stream", "Search"],
        "launch" => "../Practical-11-Student-CRUD/index.php"
    ],
    [
        "num" => "Practical 12",
        "title" => "Campus Event CRUD & Poster Upload",
        "desc" => "Event publishing system with multi-part file uploads, size caps, and automatic disk image cleanup.",
        "tags" => ["File Upload", "MIME Validation", "CRUD", "Disk Cleanup"],
        "launch" => "../Practical-12-Event-CRUD-Upload/index.php"
    ],
    [
        "num" => "Practical 13",
        "title" => "RESTful JSON API & Single Page App (SPA)",
        "desc" => "Zero-reload asynchronous CRUD using PHP REST endpoint (GET, POST, PUT, DELETE) and Fetch API.",
        "tags" => ["REST API", "SPA", "JSON", "HTTP Verbs"],
        "launch" => "../Practical-13-PHP-JSON-API-Fetch/index.html"
    ],
    [
        "num" => "Practical 14",
        "title" => "Master Admin Dashboard & Status Toggle",
        "desc" => "Executive metrics overview, dynamic role menus, and real-time user activation/deactivation.",
        "tags" => ["Admin Dashboard", "Soft Deactivation", "KPIs", "RBAC"],
        "launch" => "../Practical-14-Admin-Dashboard-RBAC/dashboard.php"
    ],
    [
        "num" => "Practical 15",
        "title" => "Full System Integration & Deployment",
        "desc" => "Master deployment hub, test case verification suite, database dumps, and laboratory viva manual.",
        "tags" => ["Integration", "End-to-End Testing", "Deployment", "Documentation"],
        "launch" => "index.php"
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - Master Practical Hub (1 to 15) | ITUE203</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header>
    <h1>StudentHub &bull; Laboratory Practicals Master Hub</h1>
    <p>CHARUSAT FTE ITUE203 - Web Development Frameworks (Semester 3)</p>
  </header>

  <div class="container">
    
    <!-- Diagnostics Banner -->
    <div class="diagnostics-banner">
      <div>
        <h3>System Environment &amp; Diagnostic Status</h3>
        <div class="diag-item">
          <strong>PHP Version:</strong> <?php echo phpversion(); ?> &nbsp;|&nbsp;
          <strong>Server Software:</strong> <?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Local Server'); ?>
        </div>
        <div class="diag-item" style="margin-top: 4px;">
          <strong>Database Status:</strong> 
          <?php if ($dbConnected): ?>
            <span class="diag-badge badge-green">&#10004; MySQL Connected (Users: <?php echo $userCount; ?>, Students: <?php echo $studentCount; ?>, Events: <?php echo $eventCount; ?>)</span>
          <?php else: ?>
            <span class="diag-badge badge-red">&#10008; Disconnected: <?php echo htmlspecialchars($dbError); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div>
        <?php if (isset($_SESSION['user_id'])): ?>
          <span class="diag-badge badge-blue">Logged in as: <?php echo htmlspecialchars($_SESSION['user_name']); ?> (<?php echo strtoupper($_SESSION['user_role']); ?>)</span>
          <a href="../Practical-10-Secure-Auth-Sessions/logout.php" style="margin-left: 8px; font-size: 12px; color: #dc3545; font-weight: bold;">Logout</a>
        <?php else: ?>
          <a href="../Practical-10-Secure-Auth-Sessions/login.php" class="btn-launch" style="padding: 6px 12px; font-size: 12px;">Sign In to Portal</a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Quick Navigation Links to Reports -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
      <h2 style="margin:0; color:#003366;">Complete Practicals Index (1 to 15)</h2>
      <div style="font-size: 13px;">
        <a href="TESTING.md" target="_blank" style="color:#003366; font-weight:bold; margin-right:15px;">&#128221; View Testing Suite (TESTING.md)</a>
        <a href="DEPLOYMENT.md" target="_blank" style="color:#003366; font-weight:bold;">&#128640; Deployment Guide (DEPLOYMENT.md)</a>
      </div>
    </div>

    <!-- 15 Practicals Grid -->
    <div class="practicals-grid">
      <?php foreach ($practicals as $p): ?>
        <div class="practical-card">
          <div class="practical-num"><?php echo $p['num']; ?></div>
          <h3 class="practical-title"><?php echo $p['title']; ?></h3>
          <p class="practical-desc"><?php echo $p['desc']; ?></p>
          
          <div class="tech-tags">
            <?php foreach ($p['tags'] as $tag): ?>
              <span class="tech-tag"><?php echo $tag; ?></span>
            <?php endforeach; ?>
          </div>

          <a href="<?php echo $p['launch']; ?>" class="btn-launch" target="_blank">Launch Module &rarr;</a>
        </div>
      <?php endforeach; ?>
    </div>

  </div>

  <footer>
    StudentHub Portal &bull; Designed &amp; Developed for CHARUSAT B.Tech IT 3rd Semester Practicals &bull; All Rights Reserved.
  </footer>

</body>
</html>
