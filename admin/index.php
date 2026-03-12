<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/session.php';

// Start session and enforce authentication
start_secure_session();
check_login();

function safe($value)
{
    return htmlspecialchars($value ?? 'N/A');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Dashboard | Under Construction</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            background: linear-gradient(-45deg, #1e3c72, #2a5298, #0f2027, #203a43);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subtitle {
            opacity: 0.8;
            margin-bottom: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            width: 90%;
            max-width: 1000px;
        }

        .card {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            animation: fadeUp 1s ease forwards;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.03);
            background: rgba(255, 255, 255, 0.15);
        }

        .card h3 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.7;
        }

        .card p {
            font-size: 18px;
            font-weight: 500;
            margin-top: 8px;
        }

        footer {
            position: absolute;
            bottom: 20px;
            font-size: 14px;
            opacity: 0.6;
        }

        .clock {
            margin-top: 15px;
            font-size: 14px;
            opacity: 0.7;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <h1>🚧 System Under Construction</h1>
    <div class="subtitle">Welcome, <?php echo safe($_SESSION['full_name']); ?> 👋</div>

    <div class="grid">

        <div class="card">
            <h3>User ID</h3>
            <p><?php echo safe($_SESSION['user_id']); ?></p>
        </div>
        <div class="card">
            <h3>Username</h3>
            <p><?php echo safe($_SESSION['username']); ?></p>
        </div>
        <div class="card">
            <h3>Email</h3>
            <p><?php echo safe($_SESSION['email']); ?></p>
        </div>
        <div class="card">
            <h3>Role ID</h3>
            <p><?php echo safe($_SESSION['role_id']); ?></p>
        </div>
        <div class="card">
            <h3>Department ID</h3>
            <p><?php echo safe($_SESSION['department_id']); ?></p>
        </div>
        <div class="card">
            <h3>Class ID</h3>
            <p><?php echo safe($_SESSION['class_id']); ?></p>
        </div>
        <div class="card">
            <h3>Level ID</h3>
            <p><?php echo safe($_SESSION['level_id']); ?></p>
        </div>
        <div class="card">
            <h3>Login Time</h3>
            <p><?php echo date("Y-m-d H:i:s", $_SESSION['login_time']); ?></p>
        </div>
        <div class="card">
            <h3>Last Activity</h3>
            <p><?php echo date("Y-m-d H:i:s", $_SESSION['last_activity']); ?></p>
        </div>

    </div>

    <div class="clock" id="clock"></div>

    <footer>
        ⚡ Your Application is Being Crafted With Precision
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById("clock").innerHTML =
                "Current Server Time: " + now.toLocaleString();
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

</body>

</html>