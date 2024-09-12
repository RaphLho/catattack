<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatAttack - Niveau 1</title>
    <style>
        @font-face {
            font-family: 'Undertale';
            src: url('MonsterFriendFore.otf') format("opentype");
        }
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
            top: 40px;
            right: 10px;
            color: white;
            font-size: 24px;
        }
        #time {
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
        #gameOver, #victory {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 50px;
            text-align: center;
            display: none;
            width: 80%;
            max-width: 600px;
            border: 6px solid white;
            border-radius: 15px;
            font-family: 'Undertale', sans-serif;
        }
        .undertale-text {
            font-size: 48px;
            color: #ffff00;
            text-shadow: 3px 3px #ff0000;
            margin-bottom: 30px;
        }
        .gameOver-button, .victory-button {
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
        .gameOver-button:hover, .victory-button:hover {
            background-color: #ff0000;
            color: #ffff00;
            border-color: #ffff00;
        }
    </style>
</head>
<body>
    <canvas id="gameCanvas"></canvas>
    <div id="time">Temps: 00:00:00</div>
    <div id="score">Score: 0</div>
    <div id="lives">❤️❤️❤️</div>
    <div id="gameOver">
        <h2 class="undertale-text">Game Over</h2>
        <p class="undertale-text" id="finalTime">Temps final: 00:00:00</p>
        <p class="undertale-text" id="finalScore">Score: 0</p>
        <button id="restart" class="gameOver-button" onclick="location.reload()">Recommencer</button>
        <button id="quit" class="gameOver-button">Quitter</button>
    </div>
    <div id="victory">
        <h2 class="undertale-text">Victoire!</h2>
        <p class="undertale-text" id="victoryTime">Temps final: 00:00:00</p>
        <p class="undertale-text" id="victoryScore">Score: 0</p>
        <button id="nextLevel" class="victory-button">Niveau suivant</button>
        <button id="restartVictory" class="victory-button" onclick="location.reload()">Recommencer</button>   
        <button id="quitVictory" class="victory-button">Quitter</button>
    </div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const timeElement = document.getElementById('time');
        const scoreElement = document.getElementById('score')
        const livesElement = document.getElementById('lives');
        const gameOverElement = document.getElementById('gameOver');
        const victoryElement = document.getElementById('victory');
        const finalTimeElement = document.getElementById('finalTime');
        const finalScoreElement = document.getElementById('finalScore');
        const victoryTimeElement = document.getElementById('victoryTime');
        const victoryScoreElement = document.getElementById('victoryScore');


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
            speed: 10,
            jumpForce: 15,
            gravity: 0.5,
            isJumping: false,
            velocityY: 0,
            velocityX: 0
        };

        const levelWidth = 7500; // Increased level width
        const groundHeight = 50;
        let camera = { x: 0, y: 0 };

        let terrain = [];
        let enemies = [];
        let spikes = [];
        let startTime;
        let elapsedTime = 0;
        let lives = 3; // Reduced starting lives
        let gameEnded = false;
        let initialSpeed;

        
        const platformImage = new Image();
        platformImage.src =
            'https://static.vecteezy.com/system/resources/previews/003/678/912/non_2x/stone-tiles-texture-in-cartoon-style-free-vector.jpg';

        const moovingPlatformImage = new Image();
        moovingPlatformImage.src =
            'https://static.vecteezy.com/system/resources/previews/003/448/235/non_2x/light-brown-cartoon-wood-texture-pattern-wallpaper-background-free-vector.jpg';

        const spikeImage = new Image();
        spikeImage.src =
            'https://static.vecteezy.com/system/resources/previews/003/758/560/non_2x/spear-icon-fairy-tale-knight-armor-fairytale-soldier-sword-cartoon-medieval-weapon-illustration-vector.jpg';

        const holeImage = new Image();
        holeImage.src =
            'https://static.vecteezy.com/system/resources/previews/046/307/862/non_2x/water-ripple-surface-with-sunlight-reflections-in-cartoon-style-game-texture-top-view-beach-ocean-clean-and-deep-water-vector.jpg';


        const highPlatformImage = new Image();
        highPlatformImage.src =
            'https://static.vecteezy.com/system/resources/previews/013/987/849/non_2x/stone-wall-from-bricks-rock-game-background-in-cartoon-style-seamless-textured-surface-ui-game-asset-road-or-floor-material-illustration-vector.jpg';

        const finishLine = new Image();
        finishLine.src =
            'https://media.istockphoto.com/id/537073587/fr/vectoriel/%C3%A9chiquier.jpg?s=612x612&w=0&k=20&c=XqGNOIwnHXqrPg-Iz1rLsgRVQY8CdEvU85mPJSn8tUU=';

        const startLine = new Image();
        startLine.src =
            'https://static.vecteezy.com/system/resources/previews/040/520/651/non_2x/brown-wooden-texture-and-background-vector.jpg';

        const monsterImg = new Image();
        monsterImg.src = 'https://static.vecteezy.com/system/resources/previews/022/946/248/large_2x/cute-monster-character-colored-red-with-angry-expression-3d-illustration-generative-ai-free-png.png';

        function generateTerrain() {
            terrain = [
                { 
                    type: 'platform', 
                    x: 0, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 750, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 1500, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 2250, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 3000, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 3750, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 4500, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 5250, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 6000, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },
                { 
                    type: 'platform', 
                    x: 6750, 
                    y: canvas.height - groundHeight, 
                    width: 750, 
                    height: groundHeight, 
                    image: platformImage 
                },



                { type: 'platform', x: levelWidth - 300, y: canvas.height - groundHeight, width: 300, height: groundHeight,  image:finishLine  }
            ];

            enemies = [
                { type: 'runner', x: 700, y: canvas.height - groundHeight - 30, width: 30, height: 30, speed: 5, direction: 1, minX: 700, maxX: 900 },
                { type: 'jumper', x: 1500, y: canvas.height - groundHeight - 30, width: 30, height: 30, speed: 2, jumpForce: 15, gravity: 0.5, isJumping: false },
                { type: 'shooter', x: 2300, y: canvas.height - groundHeight - 30, width: 30, height: 30, shootInterval: 2000, lastShot: 0, bullets: [] },
                { type: 'flyer', x: 3100, y: canvas.height - groundHeight - 100, width: 30, height: 30, speed: 3, amplitude: 50, angle: 0 },
                { type: 'runner', x: 3900, y: canvas.height - groundHeight - 30, width: 30, height: 30, speed: 6, direction: 1, minX: 3900, maxX: 4100 },
                { type: 'jumper', x: 4700, y: canvas.height - groundHeight - 30, width: 30, height: 30, speed: 2, jumpForce: 18, gravity: 0.5, isJumping: false },
                { type: 'shooter', x: 5500, y: canvas.height - groundHeight - 30, width: 30, height: 30, shootInterval: 1500, lastShot: 0, bullets: [] },
                { type: 'flyer', x: 6300, y: canvas.height - groundHeight - 150, width: 30, height: 30, speed: 4, amplitude: 80, angle: 0 }
            ];

            spikes = [
                { x: 1000, y: canvas.height - groundHeight - 20, width: 20, height: 20 },
                { x: 2000, y: canvas.height - groundHeight - 20, width: 20, height: 20 },
                { x: 3000, y: canvas.height - groundHeight - 20, width: 20, height: 20 },
                { x: 4000, y: canvas.height - groundHeight - 20, width: 20, height: 20 },
                { x: 5000, y: canvas.height - groundHeight - 20, width: 20, height: 20 },
                { x: 6000, y: canvas.height - groundHeight - 20, width: 20, height: 20 },
                { x: 7000, y: canvas.height - groundHeight - 20, width: 20, height: 20 }
            ];

            initialSpeed = cat.speed;
        }

        score = 0
        function resetGame() {
            score = 0
            cat.x = 50;
            cat.y = canvas.height - groundHeight - cat.height;
            cat.velocityY = 0;
            cat.velocityX = 0;
            cat.isJumping = false;
            camera.x = 0;
            startTime = Date.now();
            elapsedTime = 0;
            lives = 3;
            gameEnded = false;
            cat.speed = initialSpeed;
            updateTime();
            updateLives();
            scoreElement.textContent = 'Score: 0'
            gameOverElement.style.display = 'none';
            victoryElement.style.display = 'none';
            generateTerrain();
            gameLoop();
        }

        function updateTime() {
            if (!gameEnded) {
                const currentTime = Date.now();
                elapsedTime = currentTime - startTime;
                const formattedTime = formatTime(elapsedTime);
                timeElement.textContent = `Temps: ${formattedTime}`;
            }
        }
        
        function updateScore() {
            if (!gameEnded) {
                score += 50
                scoreElement.textContent = `Score: ${score}`;
            }
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
            if (gameEnded) return;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            camera.x = cat.x - canvas.width / 4;

            ctx.save();
            ctx.translate(-camera.x, 0);

            terrain.forEach(obstacle => {
                
                if (obstacle.image) {
                        ctx.drawImage(obstacle.image, obstacle.x, obstacle.y, obstacle.width, obstacle.height);
                    } else {
                        ctx.fillStyle = obstacle.color;
                        ctx.fillRect(obstacle.x, obstacle.y, obstacle.width, obstacle.height);
                    }
            });

            enemies.forEach((enemy, index) => {
                ctx.fillStyle = '#FF0000';
                ctx.fillRect(enemy.x, enemy.y, enemy.width, enemy.height);
                
                switch(enemy.type) {
                    case 'runner':
                        enemy.x += enemy.speed * enemy.direction;
                        if (enemy.x <= enemy.minX || enemy.x + enemy.width >= enemy.maxX) {
                            enemy.direction *= -1;
                        }
                        break;
                    case 'jumper':
                        if (enemy.y === canvas.height - groundHeight - enemy.height) {
                            enemy.isJumping = true;
                            enemy.velocityY = -enemy.jumpForce;
                        }
                        enemy.velocityY += enemy.gravity;
                        enemy.y += enemy.velocityY;
                        if (enemy.y > canvas.height - groundHeight - enemy.height) {
                            enemy.y = canvas.height - groundHeight - enemy.height;
                            enemy.isJumping = false;
                        }
                        break;
                    case 'shooter':
                        const now = Date.now();
                        if (now - enemy.lastShot > enemy.shootInterval) {
                            enemy.bullets.push({x: enemy.x, y: enemy.y + enemy.height / 2, speed: 5});
                            enemy.lastShot = now;
                        }
                        enemy.bullets.forEach((bullet, bulletIndex) => {
                            bullet.x -= bullet.speed;
                            ctx.fillStyle = '#FFFF00';
                            ctx.fillRect(bullet.x, bullet.y, 10, 5);
                            if (bullet.x < enemy.x - 300) {  // Les balles disparaissent après 300 pixels
                                enemy.bullets.splice(bulletIndex, 1);
                            }
                            if (
                                cat.x < bullet.x + 10 &&
                                cat.x + cat.width > bullet.x &&
                                cat.y < bullet.y + 5 &&
                                cat.y + cat.height > bullet.y
                            ) {
                                loseLife();
                                enemy.bullets.splice(bulletIndex, 1);
                            }
                        });
                        break;
                    case 'flyer':
                        enemy.angle += 0.05;
                        enemy.y = canvas.height - groundHeight - 100 + Math.sin(enemy.angle) * enemy.amplitude;
                        enemy.x += enemy.speed;
                        if (enemy.x > levelWidth) {
                            enemy.x = -enemy.width;
                        }
                        break;
                }

                if (
                    cat.x < enemy.x + enemy.width &&
                    cat.x + cat.width > enemy.x &&
                    cat.y < enemy.y + enemy.height &&
                    cat.y + cat.height > enemy.y
                ) {
                    if (cat.velocityY > 0 && cat.y < enemy.y) {
                        // Le chat saute sur l'ennemi
                        enemies.splice(index, 1);
                        cat.velocityY = -cat.jumpForce / 2; // Petit rebond après avoir tué l'ennemi
                        updateScore()
                    } else {
                        loseLife();
                    }
                }
            });

            spikes.forEach(spike => {
                ctx.fillStyle = '#FF00FF';
                ctx.beginPath();
                ctx.moveTo(spike.x, spike.y + spike.height);
                ctx.lineTo(spike.x + spike.width / 2, spike.y);
                ctx.lineTo(spike.x + spike.width, spike.y + spike.height);
                ctx.closePath();
                ctx.fill();

                if (
                    cat.x < spike.x + spike.width &&
                    cat.x + cat.width > spike.x &&
                    cat.y < spike.y + spike.height &&
                    cat.y + cat.height > spike.y
                ) {
                    loseLife();
                }
            });

            cat.velocityY += cat.gravity;
            cat.y += cat.velocityY;
            cat.x += cat.velocityX;

            let onGround = false;
            terrain.forEach(obstacle => {
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
                }
            });

            if (cat.y > canvas.height) {
                loseLife();
            }

            const catImage = new Image();
            const selectedSkin = localStorage.getItem('selectedSkin') || 'chat-normal';
            let catImageSrc = '';

            switch (selectedSkin) {
                case 'chat-ninja':
                    catImageSrc = 'https://www.creativefabrica.com/wp-content/uploads/2022/12/25/Ninja-Cat-Portrait-Steampunk-Style-54365317-1.png';
                    break;
                case 'chat-spatial':
                    catImageSrc = 'https://www.sciencesetavenir.fr/assets/img/2016/03/31/cover-r4x3w1200-57dfbf2666447-space-chat.jpg';
                    break;
                default:
                    catImageSrc = 'https://s3-us-west-2.amazonaws.com/mb.images/vinafrog/listing/VFSIL0095.jpg';
            }

            catImage.src = catImageSrc;
            ctx.drawImage(catImage, cat.x, cat.y, cat.width, cat.height);

            ctx.restore();

            if (cat.x > levelWidth - 300 && cat.y === canvas.height - groundHeight - cat.height) {
                victory();
            }

            updateTime();
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
            gameEnded = true;
            const finalTime = formatTime(elapsedTime);
            const finalScore = score + parseInt(finalTime.split(':')[2]);
            finalScoreElement.textContent = `Score: ${finalScore}`;
            finalTimeElement.textContent = `Temps final: ${finalTime}`;
            gameOverElement.style.display = 'block';
        }

        function victory() {
            gameEnded = true;
            const finalTime = formatTime(elapsedTime);
            const finalScore = 60-finalTime.split(':')[2] + score;
            fetch('/scores', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    score: finalScore,
                    level: 1,
                    name: localStorage.getItem('name')
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Score saved:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });

victoryScoreElement.textContent = `Score: ${finalScore}`;
victoryTimeElement.textContent = `Temps final: ${finalTime}`;
            victoryElement.style.display = 'block';
        }

        const keys = {};

        document.addEventListener('keydown', (event) => {
            keys[event.key] = true;
            handleMovement();
            if (event.key === 'Escape') {
                window.location.href = '{{ url("/") }}';
            }
        });

        document.addEventListener('keyup', (event) => {
            keys[event.key] = false;
            handleMovement();
        });

        function handleMovement() {
            cat.velocityX = 0;

            if (keys['ArrowLeft'] || keys['q'] || keys['Q']) {
                cat.velocityX = -cat.speed;
            }
            if (keys['ArrowRight'] || keys['d'] || keys['D']) {
                cat.velocityX = cat.speed;
            }
            if ((keys['ArrowUp'] || keys['z'] || keys['Z'] || keys[' ']) && !cat.isJumping) {
                cat.velocityY = -cat.jumpForce;
                cat.isJumping = true;
            }
            if (keys['r'] || keys['R']) {
                location.reload();
            }
        }

        document.getElementById('restart').addEventListener('click', resetGame);
        document.getElementById('quit').addEventListener('click', () => {
            window.location.href = '{{ url("/") }}';
        });
        document.getElementById('restartVictory').addEventListener('click', resetGame);
        document.getElementById('quitVictory').addEventListener('click', () => {
            window.location.href = '{{ url("/") }}';
        });
        document.getElementById('nextLevel').addEventListener('click', () => {
            window.location.href = '{{ url("/2") }}';
        });

        generateTerrain();
        resetGame();
    </script>
</body>
</html>
