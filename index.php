<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hebats Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #F4F6F8;
            --card-bg: #FFFFFF;
            --text-primary: #333;
            --text-secondary: #777;
            --accent-orange: #FFB84C;
            --accent-green: #27AE60;
            --accent-red: #E74C3C;
            --accent-blue: #3498DB;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: var(--text-primary);
        }

        /* --- TOP NAVIGATION BAR --- */
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--card-bg);
            padding: 15px 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .nav-left h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .nav-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }
        .stat-item {
            display: flex;
            align-items: center;
            font-weight: bold;
        }
        .gold-icon {
            color: #FFD700;
            margin-right: 8px;
            font-size: 1.2rem;
        }
        /* Health Bar Styles */
        .health-bar-container {
            width: 150px;
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .health-bar {
            height: 100%;
            background-color: var(--accent-red);
            width: 80%; /* Example: 80% Health */
            transition: width 0.3s ease;
        }
        .health-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 0.8rem;
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        .profile-icon {
            width: 40px;
            height: 40px;
            background-color: #ddd;
            border-radius: 50%;
            background-image: url('https://i.pravatar.cc/150?img=3'); /* Placeholder image */
            background-size: cover;
            cursor: pointer;
        }

        /* --- DASHBOARD GRID --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 300px 1fr 1fr;
            gap: 25px;
            align-items: start;
        }

        /* Generic Card Style */
        .card {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.1rem;
            margin: 0;
        }
        .view-details {
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
        }

        /* --- SIDEBAR --- */
        .sidebar-btns .btn {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 15px;
            text-align: center;
        }
        .btn-new { background-color: var(--accent-orange); color: white; }
        .btn-browse { background-color: white; border: 2px solid #eee; color: var(--text-primary); }

        /* Calendar */
        .calendar { text-align: center; }
        .calendar-header { display: flex; justify-content: space-between; margin-bottom: 15px; font-weight: bold; }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
        .day {
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .day.today {
            background-color: var(--accent-orange);
            color: white;
            font-weight: bold;
        }
        .day-name { color: var(--text-secondary); font-size: 0.8rem; font-weight: bold;}

        /* --- MAIN CONTENT COLUMN 1 --- */
        .weather-card {
            background: linear-gradient(to bottom right, #ffe29f, #ffa947);
            color: #5a3a1a;
        }
        .weather-info { display: flex; align-items: center; justify-content: space-between; }
        .weather-temp { font-size: 2.5rem; font-weight: bold; }
        .weather-details { display: flex; gap: 15px; margin-top: 15px; font-size: 0.9rem; }

        .should-do-card { display: flex; align-items: center; gap: 15px; padding: 15px; border-radius: 15px; background: white; margin-bottom: 15px; cursor: pointer;}
        .should-do-icon { font-size: 2rem; }

        /* --- MAIN CONTENT COLUMN 2 (TODO LIST) --- */
        .todo-list { list-style: none; padding: 0; margin: 0; }
        .todo-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .todo-left { display: flex; align-items: center; gap: 15px; }
        .todo-icon { font-size: 1.5rem; width: 30px; text-align: center; }
        .todo-info h4 { margin: 0 0 5px 0; }
        .todo-meta { font-size: 0.8rem; color: var(--text-secondary); display: flex; gap: 10px; }
        
        /* New Todo Buttons */
        .todo-actions { display: flex; gap: 10px; }
        .btn-action {
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-did { background-color: var(--accent-green); color: white; }
        .btn-didnt { background-color: var(--accent-red); color: white; }
        .btn-action:hover { opacity: 0.8; }

        /* --- OTHER WIDGETS (Simplified placeholders based on image) --- */
        .spotify-card { background-color: #1DB954; color: white; text-align: center; }
        .analytics-card { background-color: #547c5f; color: white; }
        .wrapped-card { background-color: #1a1a1a; color: white; text-align: center; }
        .chart-card { grid-column: span 2; }
    </style>
</head>
<body>

    <div class="top-nav">
        <div class="nav-left">
            <h1>Dashboard</h1>
        </div>
        <div class="nav-right">
            <div class="stat-item">
                <i class="fa-solid fa-coins gold-icon"></i>
                <span>1,250</span>
            </div>

            <div class="health-bar-container">
                <div class="health-bar" style="width: 80%;"></div>
                <span class="health-text">80 / 100 HP</span>
            </div>

            <div class="profile-icon"></div>
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="sidebar">
            <div class="card" style="background: transparent; box-shadow: none; padding: 0;">
                <h2 style="margin-bottom: 5px;">Happy Tuesday</h2>
                <p style="color: var(--text-secondary); margin-top: 0;">30 Dec 2023, 10:03 am</p>
            </div>

            <div class="sidebar-btns">
                <button class="btn btn-new">+ New Habits</button>
                <button class="btn btn-browse">Browse Popular Habits</button>
            </div>

            <div class="card calendar">
                <div class="calendar-header">
                    <span>Let's say...</span>
                    <span style="color: var(--accent-orange); cursor: pointer;">Dec 2023 ></span>
                </div>
                <div class="calendar-grid">
                    <span class="day-name">S</span><span class="day-name">M</span><span class="day-name">T</span><span class="day-name">W</span><span class="day-name">T</span><span class="day-name">F</span><span class="day-name">S</span>
                    <span class="day" style="color: #ccc;">26</span><span class="day" style="color: #ccc;">27</span><span class="day" style="color: #ccc;">28</span><span class="day" style="color: #ccc;">29</span><span class="day">1</span><span class="day">2</span>
                    <span class="day">3</span><span class="day">4</span><span class="day">5</span><span class="day">6</span><span class="day">7</span><span class="day">8</span><span class="day">9</span>
                    <span class="day">10</span><span class="day">11</span><span class="day">12</span><span class="day">13</span><span class="day">14</span><span class="day">15</span><span class="day">16</span>
                    <span class="day">17</span><span class="day">18</span><span class="day">19</span><span class="day">20</span><span class="day">21</span><span class="day">22</span><span class="day">23</span>
                    <span class="day">24</span><span class="day">25</span><span class="day">26</span><span class="day">27</span><span class="day">28</span><span class="day">29</span>
                    <span class="day today">30</span>
                </div>
            </div>
        </div>

        <div class="main-col-1">
            <div class="card weather-card">
                <div class="card-header">
                    <span class="card-title">Weather</span>
                    <span class="view-details">View Details</span>
                </div>
                <div class="weather-info">
                    <div style="font-size: 3rem;">⛅</div>
                    <div class="weather-temp">12°C</div>
                </div>
                <div class="weather-details">
                    <span>Wind: 2-4 km/h</span>
                    <span>Humidity: 42%</span>
                </div>
            </div>

            <h3 style="margin: 20px 0 10px;">Should Do!</h3>
            <div class="should-do-card">
                <div class="should-do-icon">💪</div>
                <div>
                    <strong>We go jimmm!!</strong><br>
                    <span style="font-size: 0.8rem; color: var(--text-secondary);">4.2k love this</span>
                </div>
            </div>
            <div class="should-do-card">
                <div class="should-do-icon">⏰</div>
                <div>
                    <strong>The 5am club</strong><br>
                    <span style="font-size: 0.8rem; color: var(--text-secondary);">5.4k love this</span>
                </div>
            </div>
        </div>

        <div class="main-col-2">
            
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Today's Todos</span>
                    <span class="view-details">View Details</span>
                </div>
                <ul class="todo-list">
                    <li class="todo-item">
                        <div class="todo-left">
                            <div class="todo-icon">📚</div>
                            <div class="todo-info">
                                <h4>Study</h4>
                                <div class="todo-meta">
                                    <span><i class="fa-regular fa-clock"></i> 10:00am</span>
                                    <span><i class="fa-solid fa-location-dot"></i> K-Cafe</span>
                                </div>
                            </div>
                        </div>
                        <div class="todo-actions">
                            <button class="btn-action btn-did">Did</button>
                            <button class="btn-action btn-didnt">Didn't Do</button>
                        </div>
                    </li>
                    <li class="todo-item">
                        <div class="todo-left">
                            <div class="todo-icon">🛒</div>
                            <div class="todo-info">
                                <h4>Groceries</h4>
                                <div class="todo-meta">
                                    <span><i class="fa-regular fa-clock"></i> 02:00pm</span>
                                    <span><i class="fa-solid fa-location-dot"></i> Market</span>
                                </div>
                            </div>
                        </div>
                        <div class="todo-actions">
                            <button class="btn-action btn-did">Did</button>
                            <button class="btn-action btn-didnt">Didn't Do</button>
                        </div>
                    </li>
                    <li class="todo-item">
                        <div class="todo-left">
                            <div class="todo-icon">🥦</div>
                            <div class="todo-info">
                                <h4>Eat Healthy Food</h4>
                                <div class="todo-meta">
                                    <span><i class="fa-regular fa-clock"></i> 08:30am</span>
                                    <span><i class="fa-solid fa-home"></i> Home</span>
                                </div>
                            </div>
                        </div>
                        <div class="todo-actions">
                            <button class="btn-action btn-did">Did</button>
                            <button class="btn-action btn-didnt">Didn't Do</button>
                        </div>
                    </li>
                </ul>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 25px;">
                <div class="card spotify-card">
                    <i class="fa-brands fa-spotify" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <h3>Connect your Spotify</h3>
                    <button style="background: black; color: white; border: none; padding: 8px 15px; border-radius: 20px; cursor: pointer;">Link Account</button>
                </div>
                <div class="card analytics-card">
                    <div style="font-size: 2rem;">😎</div>
                    <span>Positive Habits</span>
                    <h2>+58.2%</h2>
                </div>
            </div>

        </div>

    </div>

</body>
</html>