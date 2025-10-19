<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome - Art Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fdfbfb, #ebedee);
            font-family: 'Segoe UI', sans-serif;
        }
        .hero {
            background-image: url('assets/image_back.jpeg');
            background-size: cover;
            background-position: center;
            padding: 100px 20px;
            text-align: center;
            color: white;
            position: relative;
        }
        .hero::after {
            content: "";
            background: rgba(0,0,0,0.6);
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero p {
            font-size: 1.3rem;
        }
        .nav-btns {
            margin-top: 30px;
        }
        .nav-btns a {
            margin: 10px;
        }
        .gallery {
            padding: 60px 20px;
        }
        .gallery img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
        }
        footer {
            text-align: center;
            padding: 20px;
            background: #222;
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>🎨 Welcome to the Playful Palette Art Academy</h1>
        <p>Unleash your creativity. Learn. Create. Inspire.</p>
        <div class="nav-btns">
            <a href="login.php" class="btn btn-primary btn-lg">Login</a>
            <a href="register.php" class="btn btn-success btn-lg">Sign Up</a>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery container text-center">
    <h2 class="mb-5">Explore Our World of Art</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <img src="assets/image1.jpeg" alt="Oil Painting">
        </div>
        <div class="col-md-4">
            <img src="assets/image2.jpeg" alt="Sketching">
        </div>
        <div class="col-md-4">
            <img src="assets/image3.jpeg" alt="Digital Art">
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    &copy; <?= date("Y") ?> Playful Palette Art Academy | Designed with 🖌️ & ❤️
</footer>

</body>
</html>
<?php include 'includes/footer.php'; ?>
