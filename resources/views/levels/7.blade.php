<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatAttack - Niveau 7</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/level.css') }}">
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
        const finalScoreElement = document.getElementById('finalScore')
        const finalTimeElement = document.getElementById('finalTime');
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
            speed: 5,
            jumpForce: 15,
            gravity: 0.3,
            isJumping: false,
            velocityY: 0,
            velocityX: 0
        };

        const levelWidth = 3000; 
        const groundHeight = 50;
        let camera = { x: 0, y: 0 };

        let terrain = [];
        let enemies = [];
        let spikes = [];
        let startTime;
        let elapsedTime = 0;
        let lives = 3;
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
            'https://static.vecteezy.com/system/resources/previews/021/815/622/large_2x/triangle-shape-icon-sign-free-png.png';

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
                { type: 'platform', x: 0, y: canvas.height - groundHeight, width: 300, height: groundHeight, image:startLine },
                { type: 'hole', x: 300, y: canvas.height - 25, width:200, height:50, image:holeImage  },
                { type: 'platform', x: 500, y: canvas.height - 100, width: 250, height: 200, image: platformImage },
                { type: 'platform', x: 900, y: canvas.height - 300, width: 300, height: 200, image: highPlatformImage },
                { type: 'platform', x: 850, y: canvas.height - 50, width: 450, height: 200, image: platformImage },
                { type: 'hole', x: 750, y: canvas.height - 25, width: 100, height: 200, image: holeImage },
                { type: 'platform', x: 1300, y: canvas.height - 450, width: 200, height: 20, image: highPlatformImage },
                { type: 'platform', x: 1500, y: canvas.height - 50, width: 60, height: 200, image: platformImage },
                { type: 'platform', x: 1800, y: canvas.height - 50, width: 60, height: 200, image: platformImage },
                { type: 'hole', x: 1560, y: canvas.height - 25, width: 240, height: 200, image: holeImage },
                { type: 'hole', x: 1300, y: canvas.height - 25, width: 200, height: 200, image: holeImage },
                { type: 'moving_platform', x: 1600, y: canvas.height - 350, width: 240, height: 20, image: moovingPlatformImage,
                            speed: 2,
                            direction: 1,
                            minX: 1600,
                            maxX: 2000, },
                { type: 'platform', x: 2000, y: canvas.height - 50, width: 30, height: 200, image: platformImage },
                { type: 'hole', x: 1860, y: canvas.height - 25, width: 140, height: 20, image: highPlatformImage },
                { type: 'platform', x: 2000, y: canvas.height - 500, width: 100, height: 20, image: moovingPlatformImage },
                { type: 'platform', x: 2640, y: canvas.height - 500, width: 100, height: 2000, image: platformImage },
                { type: 'hole', x: 5600, y: canvas.height - 350, width: 150, height: 20, image: highPlatformImage },
                { type: 'platform', x: levelWidth - 300, y: canvas.height - groundHeight, width: 300, height: groundHeight, image: finishLine }
            ];

            enemies = [
                { type: 'jumper', x: 1800, y: canvas.height - 50 - 60, width: 60, height: 60, speed: 3, jumpForce: 18, gravity: 0.5, isJumping: false },
                { type: 'shooter', x: 1500, y: canvas.height - 47 - 60, width: 60, height: 60, shootInterval: 1800, lastShot: 0, bullets: [] },
                { type: 'shooter', x: 2750, y: canvas.height - 50 - 60, width: 60, height: 60, shootInterval: 1300, lastShot: 0, bullets: [] },
               
            ];

            spikes = [
                { x: 560, y: canvas.height - 95 - 40, width: 40, height: 40 },
                { x: 595, y: canvas.height - 95 - 40, width: 40, height: 40 },
                { x: 925, y: canvas.height - 295 - 40, width: 40, height: 40 },
                { x: 1020, y: canvas.height - 295 - 40, width: 40, height: 40 },
                { x: 1115, y: canvas.height - 295- 40, width: 40, height: 40 },
                { x: 1350, y: canvas.height - 445 - 40, width: 40, height: 40 },
                { x: 2050, y: canvas.height - 498 - 40, width: 40, height: 40 },

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
                if (obstacle.type === 'platform' || obstacle.type === 'moving_platform') {
                    if (obstacle.image) {
                        ctx.drawImage(obstacle.image, obstacle.x, obstacle.y, obstacle.width, obstacle.height);
                    } else {
                        ctx.fillStyle = obstacle.color;
                        ctx.fillRect(obstacle.x, obstacle.y, obstacle.width, obstacle.height);
                    }

                    if (obstacle.hasSpike) {
                        const spikeWidth = 40;
                        const spikeHeight = 40;
                        const spikeX = obstacle.x + obstacle.width / 2 - spikeWidth / 2;
                        const spikeY = obstacle.y - spikeHeight + 5; // Ajustez cette valeur pour que le spike touche le sol
                        ctx.drawImage(spikeImage, spikeX, spikeY, spikeWidth, spikeHeight);
                    }

                    if (obstacle.type === 'moving_platform') {
                        obstacle.x += obstacle.speed * obstacle.direction;
                        if (obstacle.x <= obstacle.minX || obstacle.x + obstacle.width >= obstacle.maxX) {
                            obstacle.direction *= -1;
                        }
                    }
                } else if (obstacle.type === 'hole') {
                    ctx.drawImage(holeImage, obstacle.x, canvas.height - groundHeight + 20, obstacle.width, groundHeight);
                }
            });

            enemies.forEach((enemy, index) => {
                ctx.drawImage(monsterImg, enemy.x, enemy.y, enemy.width, enemy.height);                
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
                            if (bullet.x < enemy.x - 750) {  // Les balles disparaissent après 300 pixels
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
                        cat.velocityY = -cat.jumpForce / 2; // Petit rebond après avoir tue l'ennemi
                        updateScore()
                    } else {
                        loseLife();
                    }
                }
            });

            spikes.forEach(spike => {
                ctx.drawImage(spikeImage, spike.x, spike.y, spike.width, spike.height);

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
                case 'chat-qui-pleure':
                    catImageSrc = 'https://play-lh.googleusercontent.com/8ySrSsFPK9pA5vO22g3wPWe-ykWf6LffI_fLQud5OoKrXNljmqJNVaB5MInsQp_twk8=w600-h300-pc0xffffff-pd';
                    break;
                case 'chat-points':
                    catImageSrc = 'https://ih1.redbubble.net/image.1684651633.5213/bg,f8f8f8-flat,750x,075,f-pad,750x1000,f8f8f8.jpg';
                    break;
                case 'chat-qui-rigole':
                    catImageSrc = 'https://ih1.redbubble.net/image.5411073292.4625/raf,360x360,075,t,fafafa:ca443f4786.jpg';
                    break;
                case 'chat-super-chad':
                    catImageSrc = 'https://media.tenor.com/eRobnSV9mugAAAAe/giga-cat.png';
                    break;
                case 'chat-chad':
                    catImageSrc = 'https://i.pinimg.com/736x/8b/c1/03/8bc103afbea75b591370177c9b18e52d.jpg';
                    break;
                case 'chat-cat':
                    catImageSrc = 'https://m.media-amazon.com/images/I/41HXUK8edZL.png';
                    break;
                case 'chat-backroom':
                    catImageSrc = 'https://ih1.redbubble.net/image.5186630478.3007/bg,f8f8f8-flat,750x,075,f-pad,750x1000,f8f8f8.u2.jpg';
                    break;
                case 'chat-fait-la-fete':
                    catImageSrc = 'https://img.static-rmg.be/a/view/q75/w940/h528/1949024/screen-shot-2017-06-19-at-11-55-53-png.png';
                    break;
                case 'chat-se-dore-la-pilule':
                    catImageSrc = 'https://www.fondationassistanceauxanimaux.org/actu/wp-content/uploads/2023/08/chat-1-1030x928.png';
                    break;
                case 'chat-mange':
                    catImageSrc = 'https://media.tenor.com/0okJBma33jEAAAAe/cat-meme.png';
                    break;
                case 'chipi-chipi-chapa':
                    catImageSrc = 'https://ih1.redbubble.net/image.5382356817.4130/st,medium,507x507-pad,600x600,f8f8f8.webp';
                    break;
                case 'yipii':
                    catImageSrc = 'https://m.media-amazon.com/images/I/61qt0GEHf+L._AC_UF1000,1000_QL80_.jpg';
                    break;
                case 'chat-grumpy':
                    catImageSrc = 'https://media.sudouest.fr/8858304/1200x-1/so-57ebcb7366a4bd6726a93901-ph0.jpg';
                    break;
                default:
                    catImageSrc = 'https://i0.wp.com/matooetpatoo.fr/wp-content/uploads/2022/07/chat-thai-blanc-noir.jpg?resize=1024%2C1024&ssl=1';
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
            const finalScore = parseInt(finalTime.split(':')[2]) + score
            finalScoreElement.textContent = `Score: ${finalScore}`;
            finalTimeElement.textContent = `Temps final: ${finalTime}`;
            gameOverElement.style.display = 'block';
        }

        function victory() {
            gameEnded = true;
            const finalTime = formatTime(elapsedTime);
            const finalScore = 60-finalTime.split(':')[2] + score;
            console.log(finalScore)
            fetch('/scores', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    score: finalScore,
                    level: 7,
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
            window.location.href = '{{ url("/8") }}';
        });

        generateTerrain();
        resetGame();
    </script>
</body>
</html>
