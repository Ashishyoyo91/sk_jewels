<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SK Jewellers - Invoice System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    /* Header Styles */
    .header {
      background: linear-gradient(135deg, #f8f3e6 0%, #f5e8cd 100%);
      border-bottom: 1px solid #e0d5b8;
      padding: 15px 0;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .logo-container {
      display: flex;
      align-items: center;
    }
    
    .logo-img {
      height: 50px;
      margin-right: 15px;
    }
    
    .logo-text {
      font-family: 'Georgia', serif;
    }
    
    .logo-main {
      font-size: 1.8rem;
      font-weight: 700;
      color: #d4af37; /* Gold color */
      margin: 0;
      line-height: 1;
    }
    
    .logo-sub {
      font-size: 0.9rem;
      color: #666;
      margin: 0;
      letter-spacing: 1px;
    }
    
    .nav-menu {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      height: 100%;
    }
    
    .nav-link {
      color: #333;
      font-weight: 500;
      margin-left: 25px;
      padding: 5px 0;
      position: relative;
      transition: color 0.3s;
    }
    
    .nav-link:hover {
      color: #d4af37;
    }
    
    .nav-link.active {
      color: #d4af37;
    }
    
    .nav-link.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background-color: #d4af37;
    }
    
    /* Footer Styles */
    .footer {
      background: #333;
      color: #fff;
      padding: 40px 0 20px;
      font-size: 0.9rem;
    }
    
    .footer-logo {
      height: 40px;
      margin-bottom: 15px;
    }
    
    .footer-title {
      color: #d4af37;
      font-size: 1.1rem;
      margin-bottom: 15px;
      font-weight: 600;
    }
    
    .footer-links {
      list-style: none;
      padding: 0;
    }
    
    .footer-links li {
      margin-bottom: 8px;
    }
    
    .footer-links a {
      color: #ccc;
      text-decoration: none;
      transition: color 0.3s;
    }
    
    .footer-links a:hover {
      color: #d4af37;
    }
    
    .social-icons {
      display: flex;
      gap: 15px;
      margin-top: 15px;
    }
    
    .social-icon {
      color: #fff;
      background: #444;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s;
    }
    
    .social-icon:hover {
      background: #d4af37;
      transform: translateY(-3px);
    }
    
    .copyright {
      border-top: 1px solid #444;
      padding-top: 20px;
      margin-top: 30px;
      text-align: center;
      color: #999;
    }
    
    /* Main content adjustment for fixed header */
    body {
      padding-top: 80px;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header fixed-top">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-4">
            <a href="./">
                <div class="logo-container">
                    <!-- Replace with your actual logo image -->
                    <img src="images/sk_logo.jpg" alt="SK Jewellers" class="logo-img">
                    <div class="logo-text">
                    <h1 class="logo-main">SK Jewellers</h1>
                    <p class="logo-sub">Since 1985</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-8">
          <nav class="nav-menu">
            <a href="index.php" class="nav-link active">Invoices</a>
            <a href="create.php" class="nav-link">Create Invoice</a>
            <a href="products.php" class="nav-link">Products</a>
            <a href="customers.php" class="nav-link">Customers</a>
            <a href="reports.php" class="nav-link">Reports</a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <main class="container">
   
 