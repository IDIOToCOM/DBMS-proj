<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #05a6a6 0%, #0575e6 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 1px 2px 20px black;
            overflow: hidden;
            width: 90%;
            max-width: 1000px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0;
        }
        
        .header {
            background: linear-gradient(90deg, #0693e3, #00d084);
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
            width: 100%;
            padding: 40px 20px;
            text-align: center;
            border: 1px solid white;
        }
        
        @keyframes gradient {
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
            font-family: 'Montserrat', sans-serif;
            color: white;
            font-size: 2.8rem;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .content {
            padding: 40px;
            color: #333;
            text-align: center;
            width: 100%;
        }
        
        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .features {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        
        .feature {
            width: 200px;
            margin: 20px;
            text-align: center;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #05a6a6;
        }
        
        .btn-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        
        .btn {
            background: linear-gradient(to right, #05a6a6, #0575e6);
            color: white;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(5, 166, 166, 0.4);
            display: inline-block;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(5, 166, 166, 0.4);
        }
        
        .footer {
            width: 100%;
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Student Enrollment Management System</h1>
        </div>
        
        <div class="content">
            <p>Welcome to our comprehensive student enrollment management platform. Streamline enrollment, track attendance, and monitor student progress all in one place.</p>
            
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">📝</div>
                    <h3>Easy Registration</h3>
                    <p>Simple and quick enrollment process</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">📊</div>
                    <h3>Track Progress</h3>
                    <p>Monitor academic performance</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">📅</div>
                    <h3>Attendance</h3>
                    <p>Automated attendance tracking</p>
                </div>
            </div>
            
            <div class="btn-container">
                <a href="index.php" class="btn">Get Started</a>
            </div>
        </div>
        
        <div class="footer">
            © 2025 Student Enrollment System | All Rights Reserved
        </div>
    </div>
</body>
</html>