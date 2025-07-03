<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>You and Me</title>
</head>
<body>
   <div id="container">
        <div id="header">
            <div id="logo">Logo</div>
            <div id="top_info"></div>
            <div id="navbar">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="postadd.html">Post an Add</a></li>
                    <li><a href="favourites.html">Reels</a></li>
                </ul> 
                <button onclick="window.location.href='login.html';">Signup or Logon</button>         

                <div class="search-container">
                    <input type="text" placeholder="Search...">
                    <button onclick="searchFunction()">Search</button>
                    <button onclick="showChart()">Show Chart</button>
                </div>
            </div>
        </div>

        <div id="content_area">
            <div id="main_content">
                <div id="middle_col">
                    <h1>Product Categories</h1>
                    <label for="category">Choose a category:</label>
                    <select id="category" onchange="filterProducts()">
                        <option value="all">All</option>
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="home">Home & Furniture</option>
                    </select>
                    <label for="price">Max Price:</label>
                    <input type="number" id="price" oninput="filterProducts()" placeholder="Enter max price">
                </div>         
                <div id="left_col">
                    <h2>Featured Ad</h2>
                    <div class="featured-content">
                        <img src="featured-ad.jpg" alt="Featured Ad">
                        <p>Exclusive Deal! Get 50% off on selected items.</p>
                        <button onclick="window.location.href='deal.html';">View Deal</button>
                    </div>
                </div>
                <div id="right_col">
                    <div class="paid-ad">Ad 1 - Sponsored Content</div>
                    <div class="paid-ad">Ad 2 - Special Offer</div>
                    <div class="paid-ad">Ad 3 - Limited Time Deal</div>
                </div>
            </div>
        </div>

        <footer id="footer">
            <p>All rights reserved to You and Me</p>
        </footer>
    </div>
</body>
</html>