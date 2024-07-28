<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Fetching products from the database
$products = [];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchQuery = $search ? "WHERE name LIKE '%$search%'" : '';

$sql = "SELECT * FROM products $searchQuery";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Phone | Orange Jordan E shop</title>
    <link href="https://cdn.jsdelivr.net/npm/boosted@5.1.3/dist/css/orange-helvetica.min.css" rel="stylesheet" integrity="sha384-ARRzqgHDBP0PQzxQoJtvyNn7Q8QQYr0XT+RXUFEPkQqkTB6gi43ZiL035dKWdkZe" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/boosted@5.1.3/dist/css/boosted.min.css" rel="stylesheet" integrity="sha384-Di/KMIVcO9Z2MJO3EsrZebWTNrgJTrzEDwAplhM5XnCFQ1aDhRNWrp6CWvVcn00c" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="https://boosted.orange.com/docs/5.1/assets/brand/orange-logo.svg" width="50" height="50" role="img" alt="Boosted" loading="lazy">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Disabled</a>
                    </li>
                </ul>
                <form class="d-flex" id="searchForm" method="get" action="index.php">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="searchInput" name="search">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
                <a href="logout.php" class="btn btn-danger ms-2">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row" id="productContainer">
            <?php foreach ($products as $phone) : ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($phone['name']); ?></h5>
                            <p class="card-text">Rating: <?php echo htmlspecialchars($phone['rate']); ?></p>
                            <img src="<?php echo htmlspecialchars($phone['img_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($phone['name']); ?>">
                            <p class="card-text">Brand: <?php echo htmlspecialchars($phone['brand']); ?></p>
                            <p class="card-text">Price: <?php echo htmlspecialchars($phone['price']); ?></p>
                            <?php if (isset($phone['is_out_of_stock']) && $phone['is_out_of_stock'] == '1') : ?>
                                <p class="text-danger">Out of stock</p>
                            <?php else : ?>
                                <p class="text-success">In stock</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-Go0vJb6GhKNIBtwt9rCwOyrtCoxOvFsN6EUgpL8W/CXYM4GDXU8t4QWROceKpFTe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/boosted@5.1.3/dist/js/boosted.bundle.min.js" integrity="sha384-PGAphkEesS3SSVh5I8GlXtdClICqW6fFqWyv7NDb3LsDqDX0PyQBxDOX9D5UtrtX" crossorigin="anonymous"></script>
</body>
</html>
