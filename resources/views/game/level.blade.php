<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatAttack</title>
    <style>
        body, html {
            background-color: #1a1a2e;
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }
        #gameCanvas {
            display: block;
            width: 100%;
            height: 100%;
        }
        #score {
            position: absolute;
            top: 10px;
            right: 10px;
            color: white;
            font-size: 24px;
        }
        #lives {
            position: absolute;
            top: 10px;
            left: 10px;
            color: red;
            font-size: 24px;
        }
        #gameOver {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 40px;
            text-align: center;
            display: none;
            width: 80%;
            max-width: 600px;
        }
        #gameOver button {
            margin: 15px;
            padding: 10px 20px;
        }

        @font-face {
            font-family: 'Undertale';
            src: url('https://fonts.cdnfonts.com/css/8bit-wonder') format('woff2');
        }
        #gameOver {
            font-family: 'Undertale', sans-serif;
            background-color: rgba(0, 0, 0, 0.9);
            border: 6px solid white;
            padding: 50px;
            border-radius: 15px;
        }
        .undertale-text {
            font-size: 48px;
            color: #ffff00;
            text-shadow: 3px 3px #ff0000;
            margin-bottom: 30px;
        }
        .gameOver-button {
            font-family: 'Undertale', sans-serif;
            font-size: 24px;
            background-color: #ffff00;
            color: #000000;
            border: 4px solid #ff0000;
            padding: 15px 30px;
            margin: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .gameOver-button:hover {
            background-color: #ff0000;
            color: #ffff00;
            border-color: #ffff00;
        }
    </style>
</head>
<body>
    <canvas id="canvas"></canvas>
    <div id="scoreElement">Temps: 00:00:00</div>
    <div id="lives">❤️❤️❤️</div>
    <div id="gameOver">
        <h2 class="undertale-text">Game Over</h2>
        <p class="undertale-text" id="finalScore">Temps final: 00:00:00</p>
        <button id="restart" class="gameOver-button">Recommencer</button>
        <button class="gameOver-button" onclick="location.reload()">Regénérer le niveau</button>
        <button id="quit" class="gameOver-button">Quitter</button>
    </div>

    <script>
        //const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        //const scoreElement = document.getElementById('score');
        const livesElement = document.getElementById('lives');
        const gameOverElement = document.getElementById('gameOver');
        const finalScoreElement = document.getElementById('finalScore');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        const cat = {
            x: 50,
            y: 200,
            width: 40,
            height: 40,
            speed: 5,
            jumpForce: 15,
            gravity: 0.5,
            isJumping: false,
            velocityY: 0,
            velocityX: 0
        };

        const levelWidth = 10000;
        const groundHeight = 50;
        let camera = { x: 0, y: 0 };

        let terrain = [];
        let enemies = [];
        const obstacleTypes = ['platform', 'hole', 'platform_with_spike', 'moving_platform', 'high_platform'];

        let startTime;
        let elapsedTime = 0;
        let lives = 3;

        function generateTerrain() {
            let x = 0;
            let lastObstacleWasHole = false;

            const initialPlatformWidth = 300;
            terrain.push({ 
                type: 'platform', 
                x: 0, 
                y: canvas.height - groundHeight, 
                width: initialPlatformWidth, 
                height: groundHeight,
                color: '#00FF00'
            });
            x += initialPlatformWidth;

            while (x < levelWidth - 300) {
                let type;
                do {
                    type = obstacleTypes[Math.floor(Math.random() * obstacleTypes.length)];
                } while (type === 'hole' && lastObstacleWasHole);

                const width = Math.random() * 200 + 100;
                
                switch(type) {
                    case 'platform':
                        terrain.push({ type, x, y: canvas.height - groundHeight, width, height: groundHeight, color: '#0f3460' });
                        lastObstacleWasHole = false;
                        break;
                    case 'hole':
                        terrain.push({ type, x, width });
                        lastObstacleWasHole = true;
                        break;
                    case 'platform_with_spike':
                        terrain.push({ 
                            type: 'platform', 
                            x, 
                            y: canvas.height - groundHeight, 
                            width, 
                            height: groundHeight, 
                            color: '#0f3460',
                            hasSpike: true
                        });
                        lastObstacleWasHole = false;
                        break;
                    case 'moving_platform':
                        terrain.push({
                            type: 'moving_platform',
                            x,
                            y: canvas.height - groundHeight - 100,
                            width: 100,
                            height: 20,
                            color: '#FFA500',
                            speed: 2,
                            direction: 1,
                            minX: x,
                            maxX: x + 200
                        });
                        lastObstacleWasHole = false;
                        break;
                    case 'high_platform':
                        const highPlatformHeight = Math.random() * 200 + 100;
                        terrain.push({
                            type: 'platform',
                            x,
                            y: canvas.height - groundHeight - highPlatformHeight,
                            width,
                            height: 20,
                            color: '#4B0082'
                        });
                        lastObstacleWasHole = false;
                        break;
                }
                
                // Add enemy with 20% chance
                if (Math.random() < 0.2) {
                    enemies.push({
                        x: x + width / 2,
                        y: canvas.height - groundHeight - 30,
                        width: 30,
                        height: 30,
                        speed: Math.random() * 2 + 1,
                        direction: Math.random() < 0.5 ? -1 : 1,
                        minX: x,
                        maxX: x + width
                    });
                }
                
                x += width;
            }

            terrain.push({ 
                type: 'platform', 
                x: levelWidth - 300, 
                y: canvas.height - groundHeight, 
                width: 300, 
                height: groundHeight,
                color: '#FFFFFF'
            });
        }

        function resetGame() {
            cat.x = 50;
            cat.y = canvas.height - groundHeight - cat.height;
            cat.velocityY = 0;
            cat.velocityX = 0;
            cat.isJumping = false;
            camera.x = 0;
            startTime = Date.now();
            elapsedTime = 0;
            lives = 3;
            updateScore();
            updateLives();
            gameOverElement.style.display = 'none';
            terrain = [];
            enemies = [];
            generateTerrain();
            gameLoop();
        }

        function updateScore() {
            const currentTime = Date.now();
            elapsedTime = currentTime - startTime;
            const formattedTime = formatTime(elapsedTime);
            scoreElement.textContent = `Temps: ${formattedTime}`;
        }

        function formatTime(ms) {
            const seconds = Math.floor(ms / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            return `${hours.toString().padStart(2, '0')}:${(minutes % 60).toString().padStart(2, '0')}:${(seconds % 60).toString().padStart(2, '0')}`;
        }

        function updateLives() {
            livesElement.textContent = '❤️'.repeat(lives);
        }

        function gameLoop() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            camera.x = cat.x - canvas.width / 4;

            ctx.save();
            ctx.translate(-camera.x, 0);

            terrain.forEach(obstacle => {
                if (obstacle.type === 'platform' || obstacle.type === 'moving_platform') {
                    ctx.fillStyle = obstacle.color;
                    ctx.fillRect(obstacle.x, obstacle.y, obstacle.width, obstacle.height);
                    
                    if (obstacle.hasSpike) {
                        ctx.fillStyle = '#ff0000';
                        ctx.beginPath();
                        ctx.moveTo(obstacle.x + obstacle.width / 2 - 15, obstacle.y);
                        ctx.lineTo(obstacle.x + obstacle.width / 2, obstacle.y - 30);
                        ctx.lineTo(obstacle.x + obstacle.width / 2 + 15, obstacle.y);
                        ctx.closePath();
                        ctx.fill();
                    }

                    if (obstacle.type === 'moving_platform') {
                        obstacle.x += obstacle.speed * obstacle.direction;
                        if (obstacle.x <= obstacle.minX || obstacle.x + obstacle.width >= obstacle.maxX) {
                            obstacle.direction *= -1;
                        }
                    }
                }
            });

            enemies.forEach(enemy => {
                ctx.fillStyle = '#FF0000';
                ctx.fillRect(enemy.x, enemy.y, enemy.width, enemy.height);
                enemy.x += enemy.speed * enemy.direction;
                if (enemy.x <= enemy.minX || enemy.x + enemy.width >= enemy.maxX) {
                    enemy.direction *= -1;
                }
            });

            cat.velocityY += cat.gravity;
            cat.y += cat.velocityY;
            cat.x += cat.velocityX;

            let onGround = false;
            terrain.forEach(obstacle => {
                if (obstacle.type === 'platform' || obstacle.type === 'moving_platform') {
                    if (
                        cat.y + cat.height > obstacle.y &&
                        cat.y < obstacle.y + obstacle.height &&
                        cat.x + cat.width > obstacle.x &&
                        cat.x < obstacle.x + obstacle.width
                    ) {
                        cat.y = obstacle.y - cat.height;
                        cat.velocityY = 0;
                        cat.isJumping = false;
                        onGround = true;

                        if (obstacle.hasSpike && 
                            cat.x + cat.width > obstacle.x + obstacle.width / 2 - 15 &&
                            cat.x < obstacle.x + obstacle.width / 2 + 15) {
                            loseLife();
                        }

                        if (obstacle.type === 'moving_platform') {
                            cat.x += obstacle.speed * obstacle.direction;
                        }
                    }
                }
            });

            enemies.forEach(enemy => {
                if (
                    cat.x < enemy.x + enemy.width &&
                    cat.x + cat.width > enemy.x &&
                    cat.y < enemy.y + enemy.height &&
                    cat.y + cat.height > enemy.y
                ) {
                    loseLife();
                }
            });

            if (cat.y > canvas.height) {
                loseLife();
            }

            const catImage = new Image();
            catImage.src = 'https://s3-us-west-2.amazonaws.com/mb.images/vinafrog/listing/VFSIL0095.jpg';
            ctx.drawImage(catImage, cat.x, cat.y, cat.width, cat.height);

            ctx.restore();

            if (cat.x > levelWidth - 300 && cat.y === canvas.height - groundHeight - cat.height) {
                const finalTime = formatTime(elapsedTime);
                alert(`Niveau terminé! Temps final: ${finalTime}`);
                resetGame();
            }

            updateScore();
            requestAnimationFrame(gameLoop);
        }

        function loseLife() {
            lives--;
            updateLives();
            if (lives > 0) {
                cat.x = 50;
                cat.y = canvas.height - groundHeight - cat.height;
                cat.velocityY = 0;
                cat.velocityX = 0;
                cat.isJumping = false;
                camera.x = 0;
            } else {
                gameOver();
            }
        }

        function gameOver() {
            const finalTime = formatTime(elapsedTime);
            finalScoreElement.textContent = `Temps final: ${finalTime}`;
            gameOverElement.style.display = 'block';
        }

        const keys = {};

        document.addEventListener('keydown', (event) => {
            keys[event.key] = true;
            handleMovement();
        });

        document.addEventListener('keyup', (event) => {
            keys[event.key] = false;
            handleMovement();
        });

        function handleMovement() {
            cat.velocityX = 0;

            if (keys['ArrowLeft']) {
                cat.velocityX = -cat.speed;
            }
            if (keys['ArrowRight']) {
                cat.velocityX = cat.speed;
            }
            if (keys['ArrowUp'] && !cat.isJumping) {
                cat.velocityY = -cat.jumpForce;
                cat.isJumping = true;
            }
        }

        document.getElementById('restart').addEventListener('click', () => {
            resetGame();
            camera.x = cat.x - canvas.width / 4;
        });
        document.getElementById('quit').addEventListener('click', () => {
            window.location.href = '{{ url("/") }}';
        });

        generateTerrain();
        resetGame();
    </script>
</body>
</html>
