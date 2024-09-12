<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix de Skins - CatAttack</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">    
    <link rel="stylesheet" href="{{ asset('css/skins.css') }}">    
    
</head>

<body>
    <div class="container"> 
        <a href="{{ url('/') }}" class="play-button home-button">Retour a l'accueil</a>
        <h1>Choisissez votre Skin</h1>
        <div class="skins-grid">
            <div class="skin-option" onclick="selectSkin(this, 'chat-normal')">
                <img src="https://i0.wp.com/matooetpatoo.fr/wp-content/uploads/2022/07/chat-thai-blanc-noir.jpg?resize=1024%2C1024&ssl=1" alt="Chat Normal">
                <p>Chat Normal</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-ninja')">
                <img src="https://www.creativefabrica.com/wp-content/uploads/2022/12/25/Ninja-Cat-Portrait-Steampunk-Style-54365317-1.png" alt="Chat Ninja">
                <p>Chat Ninja</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-spatial')">
                <img src="https://www.sciencesetavenir.fr/assets/img/2016/03/31/cover-r4x3w1200-57dfbf2666447-space-chat.jpg" alt="Chat Spatial">
                <p>Chat Spatial</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-qui-pleure')">
                <img src="https://play-lh.googleusercontent.com/8ySrSsFPK9pA5vO22g3wPWe-ykWf6LffI_fLQud5OoKrXNljmqJNVaB5MInsQp_twk8=w600-h300-pc0xffffff-pd" alt="Chat qui pleure">
                <p>Chat qui pleure</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-points')">
                <img src="https://ih1.redbubble.net/image.1684651633.5213/bg,f8f8f8-flat,750x,075,f-pad,750x1000,f8f8f8.jpg" alt="Chat ...">
                <p>Chat ...</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-qui-rigole')">
                <img src="https://ih1.redbubble.net/image.5411073292.4625/raf,360x360,075,t,fafafa:ca443f4786.jpg" alt="Chat qui rigole">
                <p>Chat qui rigole</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-super-chad')">
                <img src="https://media.tenor.com/eRobnSV9mugAAAAe/giga-cat.png" alt="Chat Super Chad">
                <p>Chat Super Chad</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-chad')">
                <img src="https://i.pinimg.com/736x/8b/c1/03/8bc103afbea75b591370177c9b18e52d.jpg" alt="Chat Chad">
                <p>Chat Chad</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-cat')">
                <img src="https://m.media-amazon.com/images/I/41HXUK8edZL.png" alt="Chat Cat">
                <p>nyan cat</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-backroom')">
                <img src="https://ih1.redbubble.net/image.5186630478.3007/bg,f8f8f8-flat,750x,075,f-pad,750x1000,f8f8f8.u2.jpg" alt="Chat Backroom">
                <p>Chat Backroom</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-fait-la-fete')">
                <img src="https://img.static-rmg.be/a/view/q75/w940/h528/1949024/screen-shot-2017-06-19-at-11-55-53-png.png" alt="Chat fait la fête">
                <p>Chat selfie</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-se-dore-la-pilule')">
                <img src="https://www.fondationassistanceauxanimaux.org/actu/wp-content/uploads/2023/08/chat-1-1030x928.png" alt="Chat se dore la pilule">
                <p>Chat va ?</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-mange')">
                <img src="https://media.tenor.com/0okJBma33jEAAAAe/cat-meme.png" alt="Chat mange">
                <p>Chat mange</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chipi-chipi-chapa')">
                <img src="https://ih1.redbubble.net/image.5382356817.4130/st,medium,507x507-pad,600x600,f8f8f8.webp" alt="CHIPI CHIPI CHAPA">
                <p>CHIPI CHIPI CHAPA</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'yipii')">
                <img src="https://m.media-amazon.com/images/I/61qt0GEHf+L._AC_UF1000,1000_QL80_.jpg" alt="YIPII">
                <p>YIPII</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-grumpy')">
                <img src="https://media.sudouest.fr/8858304/1200x-1/so-57ebcb7366a4bd6726a93901-ph0.jpg" alt="Chat grumpy">
                <p>Chat vert</p>
            </div>
        </div>
        <button class="button" onclick="saveSkinChoice()">Confirmer</button>
    </div>

    <script>
        let selectedSkin = '';

        function    selectSkin(element, skinName) {
            document.querySelectorAll('.skin-option').forEach(option => {
                option.classList.remove('selected');
            });
            element.classList.add('selected');
            selectedSkin = skinName;
        }

        function saveSkinChoice() {
            if (selectedSkin) {
                localStorage.setItem('selectedSkin', selectedSkin);
                window.location.href = '{{ url('/') }}';
            }
        }

        window.onload = function() {
            const savedSkin = localStorage.getItem('selectedSkin');
            if (savedSkin) {
                const skinElement = document.querySelector(`.skin-option[onclick="selectSkin(this, '${savedSkin}')"]`);
                if (skinElement) {
                    selectSkin(skinElement, savedSkin);
                }
            }
        }
    </script>
</body>

</html>
