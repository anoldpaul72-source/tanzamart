<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TanzaMart</title>

    <link rel="manifest" href="app_config.json">
    <meta name="theme-color" content="#00bcd4">

    <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
          navigator.serviceWorker.register('sw.js')
            .then(reg => console.log('TanzaMart PWA Active!'))
            .catch(err => console.log('PWA Error: ', err));
        });
      }
    </script>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#f5f5f5;
        }

        header{
            background:#111;
            color:white;
            padding:15px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            position:sticky;
            top:0;
            z-index:1000;
        }

        .logo{
            font-size:28px;
            font-weight:bold;
            color:#00bcd4;
        }

        nav a{
            color:white;
            text-decoration:none;
            margin-left:20px;
            font-size:16px;
            transition:0.3s;
        }

        nav a:hover{
            color:#00bcd4;
        }

        .hero{
            height:500px;
            background:url('https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=1400&auto=format&fit=crop') center/cover;
            display:flex;
            justify-content:center;
            align-items:center;
            text-align:center;
            color:white;
            padding:20px;
        }

        .hero-content{
            background:rgba(0,0,0,0.6);
            padding:40px;
            border-radius:10px;
        }

        .hero h1{
            font-size:50px;
            margin-bottom:15px;
        }

        .hero p{
            font-size:20px;
            margin-bottom:20px;
        }

        .btn{
            display:inline-block;
            padding:12px 25px;
            background:#00bcd4;
            color:white;
            text-decoration:none;
            border-radius:5px;
            transition:0.3s;
        }

        .btn:hover{
            background:#0097a7;
        }

        .section-title{
            text-align:center;
            margin:50px 0 20px;
            font-size:32px;
            color:#333;
        }

        .features{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
            padding:20px 40px 60px;
        }

        .feature-box{
            background:white;
            padding:30px;
            border-radius:10px;
            text-align:center;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
            transition:0.3s;
        }

        .feature-box:hover{
            transform:translateY(-5px);
        }

        .feature-box h3{
            margin:15px 0;
            color:#111;
        }

        .feature-box p{
            color:#555;
        }

        .products-preview{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
            padding:20px 40px 60px;
        }

        .product-card{
            background:white;
            border-radius:10px;
            overflow:hidden;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
            transition:0.3s;
        }

        .product-card:hover{
            transform:scale(1.03);
        }

        /* MAREKEBISHO YA PICHA: Dynamic height, object-fit contain & clean background */
        .product-card img{
            width:100%;
            height:220px;
            object-fit:contain;
            background:#fafafa;
        }

        .product-info{
            padding:15px;
            text-align:center;
        }

        .product-info h3{
            margin-bottom:10px;
        }

        .product-info p{
            color:#00bcd4;
            font-weight:bold;
            margin-bottom:15px;
        }

        /* Contact Section Styles */
        .contact-section {
            background: white;
            padding: 50px 40px;
            margin: 40px auto;
            max-width: 1100px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .contact-card {
            background: #fdfdfd;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            border-top: 4px solid #00bcd4;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .contact-card h4 {
            font-size: 20px;
            color: #111;
            margin-bottom: 10px;
        }

        .contact-card p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }

        .contact-card a {
            color: #00bcd4;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .contact-card a:hover {
            color: #0097a7;
            text-decoration: underline;
        }

        footer{
            background:#111;
            color:white;
            text-align:center;
            padding:30px 20px;
            margin-top:40px;
        }

        .footer-links {
            margin-top: 15px;
        }

        .footer-links a {
            color: #aaa;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: #00bcd4;
        }

        @media(max-width:768px){
            .hero h1{
                font-size:35px;
            }
            nav a{
                margin-left:10px;
                font-size:14px;
            }
            .contact-section {
                margin: 20px 15px;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">TanzaMart</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="track.php">Track Order</a>
        <a href="cart.php">Cart</a>

        <?php
        if(isset($_SESSION['email']) && $_SESSION['email'] == 'ashy@gmail.com'){
            echo '<a href="dashboard.php">Dashboard</a>';
        }

        if(isset($_SESSION['user'])){
            echo '<a href="logout.php">Logout</a>';
        }else{
            echo '<a href="login.php">Login</a>';
            echo '<a href="register.php">Register</a>';
        }
        ?>
    </nav>
</header>

<section class="hero">
    <div class="hero-content">
        <h1>Welcome to TanzaMart</h1>
        <p>Shop Smart, Fast & Affordable</p>
        <a href="products.php" class="btn">Shop Now</a>
    </div>
</section>

<h2 class="section-title">Why Choose Us?</h2>

<section class="features">
    <div class="feature-box">
        <h3>Fast Delivery</h3>
        <p>Get your products delivered quickly and safely.</p>
    </div>

    <div class="feature-box">
        <h3>Affordable Prices</h3>
        <p>Best market prices for quality products.</p>
    </div>

    <div class="feature-box">
        <h3>Secure Orders</h3>
        <p>Your orders and information are safe with us.</p>
    </div>
</section>

<h2 class="section-title">Popular Products</h2>

<section class="products-preview">
    <div class="product-card">
        <img src="Images/iphone.jpg" alt="iPhone">
        <div class="product-info">
            <h3>iPhone</h3>
            <p>Tsh 3,800,000</p>
            <a href="products.php" class="btn">View Product</a>
        </div>
    </div>

    <div class="product-card">
        <img src="Images/samsung.jpg" alt="Samsung">
        <div class="product-info">
            <h3>Samsung</h3>
            <p>Tsh 3,800,000</p>
            <a href="products.php" class="btn">View Product</a>
        </div>
    </div>

    <div class="product-card">
        <img src="Images/pixel.jpg" alt="Sony">
        <div class="product-info">
            <h3>Google Pixel</h3>
            <p>Tsh 2,650,000</p>
            <a href="products.php" class="btn">View Product</a>
        </div>
    </div>
     <div class="product-card">
        <img src="Images/sneaker.jpg" alt="Sony">
        <div class="product-info">
            <h3>Sneaker</h3>
            <p>Tsh 60,000</p>
            <a href="products.php" class="btn">View Product</a>
        </div>
    </div>
     <div class="product-card">
        <img src="Images/Heels2.jpg" alt="Sony">
        <div class="product-info">
            <h3>Heels</h3>
            <p>Tsh 35,000</p>
            <a href="products.php" class="btn">View Product</a>
        </div>
    </div>
    <div class="product-card">
        <img src="Images/Prime.jpg" alt="Sony">
        <div class="product-info">
            <h3>Ream Papers A4</h3>
            <p>Tsh 12,000</p>
            <a href="products.php" class="btn">View Product</a>
        </div>
    </div>
    <div class="product-card">
        <img src="Images/HP ProBook1.jpg" alt="Sony">
        <div class="product-info">
            <h3>Laptop</h3>
            <p>Tsh 550,000</p>
            <a href="products.php" class="btn">View Product</a>
        </div>
    </div>
</section>

<!-- CONTACT INFORMATION SECTION -->
<section class="contact-section">
    <h2 class="section-title" style="margin-top: 0;">Need Help? Contact Us</h2>
    <p style="text-align: center; color: #666; margin-bottom: 10px;">If you face any challenges, have questions, or need support, reach out to us through any of the channels below:</p>
    
    <div class="contact-grid">
        <div class="contact-card">
            <h4>📞 Phone Support</h4>
            <p>Call or text us directly:</p>
            <p style="margin-top: 5px;">
                <a href="tel:+255621530804">+255 621 530 804</a><br>
                <a href="tel:+255657276380">+255 657 276 380</a><br>
                <a href="tel:+255616838785">+255 616 838 785</a><br>
                <a href="tel:+255626979764">+255 626 979 764</a><br>
                <a href="tel:+255773068054">+255 773 068 054</a>
            </p>
        </div>

        <div class="contact-card">
            <h4>✉️ Email Support</h4>
            <p>Send us an official email:</p>
            <p style="margin-top: 12px;">
                <a href="mailto:sylvesterarnold72@gmail.com">sylvesterarnold72@gmail.com</a>
            </p>
        </div>

        <div class="contact-card">
            <h4>📍 Our Location</h4>
            <p>Find our headquarters at:</p>
            <p style="margin-top: 12px; font-weight: bold; color: #333;">
                Dar es Salaam, Tanzania
            </p>
        </div>
    </div>
</section>

<footer>
    <p>© 2026 TanzaMart. All Rights Reserved.</p>
    <div class="footer-links">
        <a href="index.php">Home</a> | 
        <a href="products.php">Products</a> | 
        <a href="track.php" style="color: #00bcd4; font-weight: bold;">Track Order 📦</a> | 
        <a href="cart.php">Cart</a>
    </div>
</footer>

</body>
</html>