<?php
include 'config/DBconfig.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: Auth/login.php");
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT gold,xp,name FROM users WHERE uid = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($gold,$xp,$name);
    $stmt->fetch();
?>

<?php
$sql = "SELECT * FROM habits WHERE user_id = $user_id";
$stmt = $conn->query($sql);
$items = $stmt->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momentum Course Dashboard</title>
    <!-- Google Font: Poppins & Nunito (Rounder for games) -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Markdown Parser -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

    <!-- Sidebar (Game Menu) -->
    <nav class="sidebar" id="sidebar">
        <div class="logo">
            <div class="logo-icon"><i class="fas fa-gamepad"></i></div>
            QuestFlow
        </div>
        
        <ul class="nav-links">
            <li class="nav-item">
                <i class="fas fa-th-large"></i> Dashboard
            </li>
            <li class="nav-item active">
                <i class="fas fa-scroll"></i> Quest Log
            </li>
            <li class="nav-item">
                <i class="far fa-calendar-alt"></i> Schedule
            </li>
            <li class="nav-item">
                <i class="fas fa-trophy"></i> Achievements
            </li>
            <li class="nav-item">
                <i class="fas fa-chart-pie"></i> Stats
            </li>
        </ul>

        <div class="sidebar-footer">
            <span class="pass-badge">Hero Pass Active</span>
            <p style="font-size: 0.8rem; opacity: 0.8;">Season 4 ends in 12 days</p>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- HUD Header -->
        <header class="header">
            <div class="header-left">
                <!-- Hamburger Menu Removed for mobile bottom nav -->
                <i class="fas fa-bars menu-btn" onclick="toggleSidebar()"></i>
                <div class="header-greeting">
                    <h1>Hi, <span>Alex!</span></h1>
                </div>
            </div>

            <div class="player-status">
                <!-- Life Bar (New) -->
                 <?php 
                 echo"
                    <script> .life-fill {
            width: ".$xp."%;
           
        } </script>
                 ";
                 ?>
                <div class="stat-box">
                    <i class="fas fa-heart life-icon"></i>
                    <span><?php echo $xp; ?>/100</span>
                    <div class="life-bar-mini">
                        <div class="life-fill"></div>
                    </div>
                </div>

                <!-- Gem/Currency Counter -->
                <div class="stat-box">
                    <i class="fas fa-gem gold-icon"></i> <?php echo $gold; ?>
                </div>
                
                <!-- XP & Avatar -->
                <div class="profile-card">
                    <div class="stats-col">
                        <span class="level-label"><?php echo $name; ?></span>

                    </div>
                    <div class="avatar-container">
                        <img src="assets/person.png" alt="Profile" class="profile-img">
                        <div class="status-dot"></div>
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-container">
            <!-- Calendar Strip -->
            <div class="month-nav">
                <div class="nav-arrow"><i class="fas fa-chevron-left"></i></div>
                <div class="month-list" id="monthList">
                    <!-- JS Populated -->
                </div>
                <div class="nav-arrow"><i class="fas fa-chevron-right"></i></div>
            </div>

            <div class="date-strip" id="dateStrip">
                <!-- JS Populated -->
            </div>

            <!-- Quest Board -->
            <div class="section-header">
                <h2><i class="fas fa-fire"></i> Daily Quests</h2>
                <button class="ai-brief-btn" onclick="window.location.href='Habit.php'">
                    <i class="fas fa-sparkles"></i> Add Habits
                </button>
            </div>

            <div class="course-list">
                <!-- Quest 1 (Epic) -->
                 <?php foreach ($items as $item): ?>
                <div class="quest-card <?php if(htmlspecialchars($item['difficulty']) == 'easy'){ echo 'common'; } ?> <?php if(htmlspecialchars($item['difficulty']) == 'medium'){ echo 'rare'; }  ?>  <?php if(htmlspecialchars($item['difficulty']) == 'hard'){ echo 'epic'; } ?>">
                    <div class="quest-header">
                        <div class="icon-box">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="quest-info">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <div class="quest-meta">
                                <span><i class="fas fa-shield-alt"></i> <?php echo htmlspecialchars($item['difficulty']); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="quest-rewards">
                        <div class="loot-tag"><i class="fas fa-star"></i> +250 XP</div>
                        <div class="loot-tag"><i class="fas fa-gem"></i> 50 Gems</div>
                    </div>

                    <div class="action-row">
                        <button class="btn-complete" onclick="completeQuest(this)">Complete</button>
                        <button class="btn-ai-help" onclick="generateStudyGuide('User Experience Design System')">
                            <i class="fas fa-magic"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (count($items) === 0): ?>
            <h2></i>The shop is currently empty. Check back later!</h2>
                <button class="ai-brief-btn" onclick="window.location.href='Habit.php'">
                    <i class="fas fa-sparkles"></i> Add Habits
                </button>
        <?php endif; ?>
                <!-- Quest 2 (Rare) -->
            </div>
        </div>
    </main>

    <!-- Overlay & Modal -->
    <div class="overlay" id="overlay"></div>
    <div class="ai-modal" id="aiModal">
        <div class="modal-header">
            <span class="modal-title" id="modalTitle"><i class="fas fa-robot"></i> Oracle</span>
            <i class="fas fa-times" style="cursor:pointer; font-size:1.5rem;" onclick="closeModal()"></i>
        </div>
        <div class="modal-content" id="modalContent"></div>
    </div>

    <!-- Import Map for Gemini SDK -->
    <script type="importmap">
      {
        "imports": {
          "@google/generative-ai": "https://esm.run/@google/generative-ai"
        }
      }
    </script>

    <script type="module">
        import { GoogleGenerativeAI } from "@google/generative-ai";

        const apiKey = ""; 
        const genAI = new GoogleGenerativeAI(apiKey);
        const model = genAI.getGenerativeModel({ model: "gemini-2.5-flash-preview-09-2025" });

        // AI Functions (Same logic, updated text to be "gamey")
        window.generateStudyGuide = async (courseName) => {
            openModal(`Quest Guide: ${courseName}`);
            showLoading();

            try {
                const prompt = `Act as a RPG Game Guide. For the quest "${courseName}", provide:
                1. "Mission Objectives": 3 key concepts to master.
                2. "Training Drill": A short 5-min exercise.
                Format as HTML with bold terms.`;

                const result = await model.generateContent(prompt);
                const response = await result.response;
                const text = response.text();
                document.getElementById('modalContent').innerHTML = text;
            } catch (error) {
                console.error("Error:", error);
                document.getElementById('modalContent').innerHTML = "<p>Oracle connection failed. Try again.</p>";
            }
        };

        window.generateDailyBriefing = async () => {
            openModal("Daily Briefing");
            showLoading();
            const titles = Array.from(document.querySelectorAll('.quest-info h3')).map(el => el.textContent).join(', ');

            try {
                const prompt = `My quests today are: ${titles}. 
                Act as a Guild Master. 
                1. Summarize the day's adventure theme. 
                2. Give 3 strategy tips. 
                3. End with a battle cry.
                Format as HTML.`;

                const result = await model.generateContent(prompt);
                const response = await result.response;
                const text = response.text();
                document.getElementById('modalContent').innerHTML = text;
            } catch (error) {
                document.getElementById('modalContent').innerHTML = "<p>Guild Master is away. Try again.</p>";
            }
        };

        // UI Helpers
        window.openModal = (title) => {
            document.getElementById('modalTitle').innerHTML = `<i class="fas fa-scroll"></i> ${title}`;
            document.getElementById('aiModal').classList.add('show');
            document.getElementById('overlay').classList.add('show');
        }
        window.closeModal = () => {
            document.getElementById('aiModal').classList.remove('show');
            const overlay = document.getElementById('overlay');
            if(!overlay.classList.contains('sidebar-open')) overlay.classList.remove('show');
        }
        window.showLoading = () => {
            document.getElementById('modalContent').innerHTML = `<div class="loading-dots"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>`;
        }
        
        // Simple Interaction for "Complete" button
        window.completeQuest = (btn) => {
            btn.innerHTML = `<i class="fas fa-check"></i> Done`;
            btn.style.background = "#b2bec3";
            btn.style.boxShadow = "none";
            btn.parentElement.parentElement.style.opacity = "0.6";
            // In a real app, update XP here
        }

        // Overlay & Sidebar Logic
        window.toggleSidebar = () => {
            const sb = document.getElementById('sidebar');
            const ov = document.getElementById('overlay');
            sb.classList.toggle('active');
            if(sb.classList.contains('active')) {
                ov.classList.add('show');
                ov.classList.add('sidebar-open');
            } else {
                ov.classList.remove('show');
                ov.classList.remove('sidebar-open');
            }
        }
        document.getElementById('overlay').addEventListener('click', () => {
            const ov = document.getElementById('overlay');
            if(ov.classList.contains('sidebar-open')) toggleSidebar();
            else closeModal();
        });
    </script>

    <script>
        // Calendar Logic
        document.addEventListener('DOMContentLoaded', () => {
            const monthList = document.getElementById('monthList');
            const dateStrip = document.getElementById('dateStrip');
            const now = new Date();
            
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const daysShort = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

            months.forEach((m, i) => {
                const s = document.createElement('span');
                s.style.margin = "0 10px";
                s.style.cursor = "pointer";
                s.textContent = m;
                if(i === now.getMonth()) {
                    s.style.color = "var(--primary)";
                    s.style.fontSize = "1.4rem";
                } else {
                    s.style.color = "#b2bec3";
                    s.style.fontSize = "0.9rem";
                }
                monthList.appendChild(s);
            });

            for (let i = -4; i <= 10; i++) {
                const date = new Date();
                date.setDate(now.getDate() + i);
                
                const card = document.createElement('div');
                card.classList.add('date-card');
                if (i === 0) card.classList.add('active');

                card.innerHTML = `
                    <span class="date-num">${date.getDate()}</span>
                    <span class="date-day">${daysShort[date.getDay()]}</span>
                `;
                
                card.addEventListener('click', () => {
                    document.querySelectorAll('.date-card').forEach(c => c.classList.remove('active'));
                    card.classList.add('active');
                    card.scrollIntoView({ behavior: 'smooth', inline: 'center' });
                });
                dateStrip.appendChild(card);
            }
            setTimeout(() => {
                const active = dateStrip.querySelector('.active');
                if(active) active.scrollIntoView({ behavior: 'smooth', inline: 'center' });
            }, 100);
        });
    </script>
</body>
</html>

<div class="shop-grid">
        
        

        

        