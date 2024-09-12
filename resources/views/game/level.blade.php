<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatAttack</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/level.css') }}">
</head>
<body>
    <canvas id="gameCanvas"></canvas>
    <div id="time">Temps: 00:00:00</div>
    <div id="lives">❤️❤️❤️</div>
    <div id="gameOver">
        <h2 class="undertale-text">Game Over</h2>
        <p class="undertale-text" id="finalTime">Temps final: 00:00:00</p>
        <button id="restart" class="gameOver-button">Recommencer</button>
        <button class="gameOver-button" onclick="location.reload()">Regenerer le niveau</button>
        <button id="quit" class="gameOver-button">Quitter</button>
    </div>
    <div id="victory">
        <h2 class="undertale-text">Victoire!</h2>
        <p class="undertale-text" id="victoryTime">Temps final: 00:00:00</p>
        <button id="gameOver-button" onclick="location.reload()" class="victory-button">Niveau suivant</button>
        <button id="restartVictory" class="victory-button">Recommencer</button>
        <button id="quitVictory" class="victory-button">Quitter</button>
    </div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const timeElement = document.getElementById('time');
        const livesElement = document.getElementById('lives');
        const gameOverElement = document.getElementById('gameOver');
        const victoryElement = document.getElementById('victory');
        const finalTimeElement = document.getElementById('finalTime');
        const victoryTimeElement = document.getElementById('victoryTime');

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

        const levelWidth = 10000;
        const groundHeight = 50;
        let camera = {
            x: 0,
            y: 0
        };

        let terrain = [];
        let enemies = [];
        let initialEnemies = [];
        const obstacleTypes = ['platform', 'hole', 'platform_with_spike', 'moving_platform', 'high_platform'];

        let startTime;
        let elapsedTime = 0;
        let lives = 3;
        let gameEnded = false;

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
            let x = 0;
            let lastObstacleWasHole = false;

            const initialPlatformWidth = 300;
            terrain.push({
                type: 'platform',
                x: 0,
                y: canvas.height - groundHeight,
                width: initialPlatformWidth,
                height: groundHeight,
                image: startLine
            });
            x += initialPlatformWidth;

            while (x < levelWidth - 300) {
                let type;
                do {
                    type = obstacleTypes[Math.floor(Math.random() * obstacleTypes.length)];
                } while (type === 'hole' && lastObstacleWasHole);

                const width = Math.random() * 200 + 100;

                switch (type) {
                    case 'platform':
                        terrain.push({
                            type,
                            x,
                            y: canvas.height - groundHeight,
                            width,
                            height: groundHeight,
                            image: platformImage
                        });
                        lastObstacleWasHole = false;
                        break;
                    case 'hole':
                        terrain.push({
                            type,
                            x,
                            width,
                            image: holeImage
                        });
                        lastObstacleWasHole = true;
                        break;
                    case 'platform_with_spike':
                        terrain.push({
                            type: 'platform',
                            x,
                            y: canvas.height - groundHeight,
                            width,
                            height: groundHeight,
                            image: platformImage,
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
                            image: moovingPlatformImage,
                            speed: 2,
                            direction: 1,
                            minX: x,
                            maxX: x + 200,
                            hasSpike: true
                        });
                        terrain.push({
                            type: 'hole',
                            x,
                            width: 200,
                            image: holeImage
                        });
                        lastObstacleWasHole = true;
                        break;
                    case 'high_platform':
                        const highPlatformHeight = Math.random() * 200 + 100;
                        terrain.push({
                            type: 'platform',
                            x,
                            y: canvas.height - groundHeight - highPlatformHeight,
                            width,
                            height: 20,
                            image: highPlatformImage
                        });
                        terrain.push({
                            type: 'hole',
                            x,
                            width,
                            image: holeImage
                        });
                        lastObstacleWasHole = true;
                        break;
                }

                // Add enemy with 20% chance
                if (Math.random() < 0.2) {
                    const enemy = {
                        x: x + width / 2,
                        y: canvas.height - groundHeight - 60,
                        width: 60,
                        height: 60,
                        speed: Math.random() * 2 + 1,
                        direction: Math.random() < 0.5 ? -1 : 1,
                        minX: x,
                        maxX: x + width
                    };
                    enemies.push(enemy);
                    initialEnemies.push({
                        ...enemy
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
                image: finishLine
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
            gameEnded = false;
            enemies = initialEnemies.map(enemy => ({
                ...enemy
            }));
            updateTime();
            updateLives();
            gameOverElement.style.display = 'none';
            victoryElement.style.display = 'none';
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

            // Draw the holeImage texture for the entire ground
            ctx.drawImage(holeImage, 0, canvas.height - groundHeight, levelWidth, groundHeight);

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
                enemy.x += enemy.speed * enemy.direction;
                if (enemy.x <= enemy.minX || enemy.x + enemy.width >= enemy.maxX) {
                    enemy.direction *= -1;
                }

                // Check if cat is jumping on the enemy
                if (cat.velocityY > 0 &&
                    cat.x < enemy.x + enemy.width &&
                    cat.x + cat.width > enemy.x &&
                    cat.y + cat.height > enemy.y &&
                    cat.y + cat.height < enemy.y + enemy.height / 2) {
                    // 0pxove the enemy
                    enemies.splice(index, 1);
                    // Make the cat bounce
                    cat.velocityY = -cat.jumpForce / 2;
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
                    cat.y + cat.height > enemy.y &&
                    cat.velocityY <= 0 // Only lose life if not jumping on the enemy
                ) {
                    loseLife();
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
            finalTimeElement.textContent = `Temps final: ${finalTime}`;
            gameOverElement.style.display = 'block';
        }

        function victory() {
            gameEnded = true;
            const finalTime = formatTime(elapsedTime);



            victoryTimeElement.textContent = `Temps final: ${finalTime}`;
            victoryElement.style.display = 'block';
        }

        const keys = {};

        document.addEventListener('keydown', (event) => {
            keys[event.key] = true;
            handleMovement();
            if (event.key === 'Escape') {
                window.location.href = '{{ url('/') }}';
            }
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



            if (keys['q'] || keys['Q']) {
                cat.velocityX = -cat.speed;
            }
            if (keys['d'] || keys['D']) {
                cat.velocityX = cat.speed;
            }
            if ((keys['z'] || keys['Z']) && !cat.isJumping) {
                cat.velocityY = -cat.jumpForce;
                cat.isJumping = true;
            }

            if (keys[' '] && !cat.isJumping) {
                cat.velocityY = -cat.jumpForce;
                cat.isJumping = true;
            }

            if (keys['r'] || keys['R']) {
                location.reload();
            }
            if (keys['i'] || keys['I']) {
                cat.velocityY = -cat.speed;
                cat.isJumping = false;
            }
            if (keys['l'] || keys['L']) {
                cat.velocityX = cat.speed;
            }
        }

        document.getElementById('restart').addEventListener('click', () => {
            resetGame();
            camera.x = cat.x - canvas.width / 4;
        });
        document.getElementById('quit').addEventListener('click', () => {
            window.location.href = '{{ url('/') }}';
        });
        document.getElementById('restartVictory').addEventListener('click', () => {
            resetGame();
            camera.x = cat.x - canvas.width / 4;
        });
        document.getElementById('quitVictory').addEventListener('click', () => {
            window.location.href = '{{ url('/') }}';
        });

        generateTerrain();
        resetGame();
    </script>
</body>

</html>
