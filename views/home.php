<?php include BASE_PATH . '/views/components/head.php'; ?>
<?php include BASE_PATH . '/views/components/header.php'; ?>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    main {
        margin-top: 90px; /* altura del header */
        margin-bottom: 80px; /* espacio para el footer */
    }

    .hero {
        position: relative;
        width: 100%;
        height: 400px;
        overflow: hidden;
    }

    .carousel {
        display: flex;
        width: 300%;
        animation: slide 15s infinite;
    }

    .carousel img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    @keyframes slide {
        0%   { transform: translateX(0); }
        33%  { transform: translateX(-100%); }
        66%  { transform: translateX(-200%); }
        100% { transform: translateX(0); }
    }

    .welcome {
        text-align: center;
        padding: 40px 20px;
        background-color: #f4f4f4;
    }

    .welcome h1 {
        color: #002147;
        font-size: 2em;
    }

    .welcome p {
        color: #333;
        font-size: 1.1em;
        max-width: 700px;
        margin: auto;
    }
</style>

<main>
    <div class="hero">
        <div class="carousel">
            <img src="<?= BASE_URL ?>/public/img/carrusel1.jpg" alt="Mentoría Académica 1">
            <img src="<?= BASE_URL ?>/public/img/carrusel2.jpg" alt="Mentoría Académica 2">
            <img src="<?= BASE_URL ?>/public/img/carrusel3.jpg" alt="Mentoría Académica 3">
        </div>
    </div>

    <div class="welcome">
        <h1>Bienvenido al Sistema de Mentoría Académica</h1>
        <p>
            Esta plataforma conecta a los estudiantes con mentores especializados para brindar refuerzo académico,
            apoyo en áreas clave, y seguimiento continuo en su rendimiento. Descubre oportunidades de aprendizaje,
            clases programadas y recursos para mejorar tu experiencia universitaria.
        </p>
    </div>
</main>

<?php include BASE_PATH . '/views/components/footer.php'; ?>
