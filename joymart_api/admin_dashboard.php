<?php
include "db.php";
// Fetch all products
$result = pg_query($conn, "SELECT * FROM products ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JoyMart Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --blush:       #f5e6e8;
            --blush-mid:   #eac8cd;
            --rose:        #c9848e;
            --rose-deep:   #a85d68;
            --mauve:       #7d5260;
            --cream:       #fdf8f5;
            --warm-white:  #fffbf9;
            --text-dark:   #2e1c23;
            --text-mid:    #6b4552;
            --text-light:  #b08890;
            --gold:        #c9a96e;
            --gold-light:  #e8d5b0;
            --shadow-soft: 0 4px 24px rgba(169, 93, 104, 0.10);
            --shadow-card: 0 8px 40px rgba(169, 93, 104, 0.13);
            --radius:      16px;
            --radius-sm:   10px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text-dark);
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse 60% 40% at 80% 10%, rgba(201,168,110,0.10) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 10% 80%, rgba(234,200,205,0.25) 0%, transparent 60%);
        }

        /* ── HEADER ── */
        header {
            background: linear-gradient(135deg, var(--mauve) 0%, var(--rose-deep) 60%, var(--rose) 100%);
            padding: 0 40px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 30px rgba(125,82,96,0.25);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 28px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-dot {
            width: 8px; height: 8px;
            background: var(--gold-light);
            border-radius: 50%;
            display: inline-block;
        }
        .header-tag {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.65);
        }

        /* ── LAYOUT ── */
        .page-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 24px 80px;
        }

        /* ── SECTION TITLES ── */
        .section-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 34px;
            font-weight: 300;
            color: var(--mauve);
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-heading::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, var(--blush-mid), transparent);
            margin-left: 8px;
        }
        .section-sub {
            font-size: 13px;
            color: var(--text-light);
            margin-bottom: 28px;
            letter-spacing: 0.5px;
        }

        /* ── ADD PRODUCT CARD ── */
        .card {
            background: var(--warm-white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(201,168,110,0.15);
            padding: 36px 40px;
            margin-bottom: 52px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 28px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        .form-group.full { grid-column: 1 / -1; }

        label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-light);
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--blush-mid);
            border-radius: var(--radius-sm);
            background: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            resize: vertical;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            border-color: var(--rose);
            box-shadow: 0 0 0 3px rgba(201,132,142,0.15);
        }
        textarea { min-height: 80px; }

        /* File input */
        .file-label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: 1.5px dashed var(--blush-mid);
            border-radius: var(--radius-sm);
            background: var(--cream);
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            font-size: 13px;
            color: var(--text-light);
        }
        .file-label:hover {
            border-color: var(--rose);
            background: var(--blush);
        }
        .file-icon {
            font-size: 20px;
        }
        input[type="file"] { display: none; }

        /* Image preview */
        .img-preview-wrap {
            margin-top: 10px;
            display: none;
            position: relative;
            width: 100px;
            height: 100px;
        }
        .img-preview-wrap.visible { display: block; }
        .img-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid var(--blush-mid);
            box-shadow: 0 4px 16px rgba(169,93,104,0.15);
            display: block;
            animation: pop-in 0.25s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes pop-in {
            from { transform: scale(0.7); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }
        .img-preview-remove {
            position: absolute;
            top: -7px; right: -7px;
            width: 22px; height: 22px;
            background: var(--rose-deep);
            color: #fff;
            border: none;
            border-radius: 50%;
            font-size: 13px;
            line-height: 1;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 6px rgba(169,93,104,0.3);
            transition: background 0.15s, transform 0.15s;
        }
        .img-preview-remove:hover {
            background: var(--mauve);
            transform: scale(1.1);
        }

        /* Submit button */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 36px;
            background: linear-gradient(135deg, var(--rose-deep), var(--mauve));
            color: #fff;
            border: none;
            border-radius: 50px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(125,82,96,0.30);
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            margin-top: 8px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(125,82,96,0.35);
        }
        .btn-primary:active { transform: translateY(0); }

        /* ── STATS BAR ── */
        .stats-bar {
            display: flex;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }
        .stat-chip {
            background: var(--warm-white);
            border: 1px solid var(--blush-mid);
            border-radius: 50px;
            padding: 10px 22px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-mid);
            box-shadow: var(--shadow-soft);
        }
        .stat-chip strong {
            color: var(--mauve);
            font-weight: 600;
        }
        .stat-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--gold);
        }

        /* ── TABLE ── */
        .table-wrap {
            background: var(--warm-white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(201,168,110,0.15);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: linear-gradient(90deg, var(--blush) 0%, var(--warm-white) 100%);
        }
        th {
            padding: 16px 20px;
            text-align: left;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--text-light);
            border-bottom: 1px solid var(--blush-mid);
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid rgba(234,200,205,0.45);
            transition: background 0.18s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(245,230,232,0.35); }

        td {
            padding: 15px 20px;
            font-size: 14px;
            color: var(--text-dark);
            vertical-align: middle;
        }

        /* ID badge */
        .id-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px; height: 30px;
            border-radius: 50%;
            background: var(--blush);
            border: 1px solid var(--blush-mid);
            font-size: 11px;
            font-weight: 600;
            color: var(--rose-deep);
        }

        /* Product name */
        .product-name {
            font-weight: 500;
            color: var(--mauve);
        }

        /* Description */
        .product-desc {
            font-size: 13px;
            color: var(--text-light);
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Price */
        .price-tag {
            font-family: 'Cormorant Garamond', serif;
            font-size: 17px;
            font-weight: 600;
            color: var(--rose-deep);
        }

        /* Stock badge */
        .stock-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .stock-badge.in-stock {
            background: rgba(100,180,120,0.12);
            color: #3a7d52;
            border: 1px solid rgba(100,180,120,0.25);
        }
        .stock-badge.low-stock {
            background: rgba(201,168,110,0.15);
            color: #8a6320;
            border: 1px solid rgba(201,168,110,0.3);
        }
        .stock-badge.out-stock {
            background: rgba(201,132,142,0.12);
            color: var(--rose-deep);
            border: 1px solid rgba(201,132,142,0.25);
        }

        /* Product image */
        .product-img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid var(--blush-mid);
            box-shadow: 0 2px 8px rgba(169,93,104,0.12);
        }

        /* Action buttons */
        .actions-cell { display: flex; gap: 8px; align-items: center; }

        .btn-delete,
        .btn-edit {
            padding: 7px 16px;
            border: none;
            border-radius: 20px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            letter-spacing: 0.3px;
        }
        .btn-delete {
            background: rgba(201,132,142,0.12);
            color: var(--rose-deep);
            border: 1px solid rgba(201,132,142,0.3);
        }
        .btn-delete:hover {
            background: var(--rose-deep);
            color: #fff;
            box-shadow: 0 4px 12px rgba(169,93,104,0.25);
            transform: translateY(-1px);
        }
        .btn-edit {
            background: rgba(125,82,96,0.08);
            color: var(--mauve);
            border: 1px solid rgba(125,82,96,0.2);
        }
        .btn-edit:hover {
            background: var(--mauve);
            color: #fff;
            box-shadow: 0 4px 12px rgba(125,82,96,0.25);
            transform: translateY(-1px);
        }

        /* Empty state */
        .empty-row td {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
            font-style: italic;
        }

        /* ── FOOTER ── */
        footer {
            text-align: center;
            padding: 32px;
            font-size: 12px;
            color: var(--text-light);
            letter-spacing: 1px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 720px) {
            .form-grid { grid-template-columns: 1fr; }
            .card { padding: 24px 18px; }
            header { padding: 0 20px; }
            .page-wrap { padding: 28px 12px 60px; }
            th, td { padding: 12px 12px; }
            .product-desc { max-width: 140px; }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header>
    <div class="logo">
        <span class="logo-dot"></span>
        JoyMart
    </div>
    <span class="header-tag">Admin Dashboard</span>
</header>

<div class="page-wrap">

    <!-- ADD PRODUCT SECTION -->
    <h2 class="section-heading">Add New Product</h2>
    <p class="section-sub">Fill in the details below to list a new item in your store ✨</p>

    <div class="card">
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" placeholder="e.g. Rose Gold Necklace" required>
                </div>

                <div class="form-group">
                    <label for="price">Price (KES)</label>
                    <input type="number" id="price" name="price" step="0.01" placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock Quantity</label>
                    <input type="number" id="stock" name="stock" placeholder="0" required>
                </div>

                <div class="form-group">
                    <label for="image">Product Image</label>
                    <label class="file-label" for="image" id="file-drop-label">
                        <span class="file-icon">🌸</span>
                        <span id="file-name-display">Click to choose an image…</span>
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" onchange="handleImagePreview(this)">
                    <div class="img-preview-wrap" id="preview-wrap">
                        <img class="img-preview" id="img-preview" src="" alt="Preview">
                        <button type="button" class="img-preview-remove" onclick="clearImagePreview()" title="Remove">✕</button>
                    </div>
                </div>

                <div class="form-group full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Describe your product in a few beautiful words…" required></textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                ✦ Add Product
            </button>
        </form>
    </div>

    <!-- ALL PRODUCTS SECTION -->
    <h2 class="section-heading">All Products</h2>
    <p class="section-sub">Manage your full product catalogue below</p>

    <!-- Stats bar -->
    <?php
    $total = pg_num_rows($result);
    // Reset pointer (pg_num_rows doesn't move it, but let's be safe)
    pg_result_seek($result, 0);
    ?>
    <div class="stats-bar">
        <div class="stat-chip">
            <span class="stat-dot"></span>
            Total Products: <strong><?php echo $total; ?></strong>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($total > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $image = !empty($row['image']) ? $row['image'] : "https://res.cloudinary.com/dqwwbww9a/image/upload/v1/default/placeholder.png";
                        $stock = (int)$row['stock'];
                        if ($stock === 0) {
                            $stockClass = 'out-stock'; $stockLabel = 'Out of stock';
                        } elseif ($stock <= 5) {
                            $stockClass = 'low-stock'; $stockLabel = $stock . ' left';
                        } else {
                            $stockClass = 'in-stock'; $stockLabel = $stock;
                        }
                        echo "<tr>";
                        echo "<td><span class='id-badge'>".$row['id']."</span></td>";
                        echo "<td><span class='product-name'>".htmlspecialchars($row['title'])."</span></td>";
                        echo "<td><span class='product-desc' title='".htmlspecialchars($row['description'])."'>".htmlspecialchars($row['description'])."</span></td>";
                        echo "<td><span class='price-tag'>".number_format((float)$row['price'], 2)."</span></td>";
                        echo "<td><span class='stock-badge $stockClass'>$stockLabel</span></td>";
                        echo "<td><img class='product-img' src='".htmlspecialchars($image)."' alt='product image' /></td>";
                        echo "<td>
                                <div class='actions-cell'>
                                    <form action='delete_product.php' method='post' style='display:inline'>
                                        <input type='hidden' name='id' value='".$row['id']."'>
                                        <button type='submit' class='btn-delete'>Delete</button>
                                    </form>
                                    <form action='edit_product.php' method='get' style='display:inline'>
                                        <input type='hidden' name='id' value='".$row['id']."'>
                                        <button type='submit' class='btn-edit'>Edit</button>
                                    </form>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr class='empty-row'><td colspan='7'>No products found yet — add your first one above 🌸</td></tr>";
                }
                pg_close($conn);
                ?>
            </tbody>
        </table>
    </div>

</div>

<footer>
    ✦ JoyMart Admin &nbsp;·&nbsp; Made with love
</footer>

<script>
    function handleImagePreview(input) {
        const file = input.files[0];
        const wrap = document.getElementById('preview-wrap');
        const preview = document.getElementById('img-preview');
        const label = document.getElementById('file-drop-label');
        const nameDisplay = document.getElementById('file-name-display');

        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                wrap.classList.add('visible');
                nameDisplay.textContent = file.name;
                label.style.borderColor = 'var(--rose)';
                label.style.background = 'var(--blush)';
            };
            reader.readAsDataURL(file);
        } else {
            clearImagePreview();
        }
    }

    function clearImagePreview() {
        const input = document.getElementById('image');
        const wrap = document.getElementById('preview-wrap');
        const preview = document.getElementById('img-preview');
        const label = document.getElementById('file-drop-label');
        const nameDisplay = document.getElementById('file-name-display');

        input.value = '';
        preview.src = '';
        wrap.classList.remove('visible');
        nameDisplay.textContent = 'Click to choose an image…';
        label.style.borderColor = '';
        label.style.background = '';
    }
</script>

</body>
</html>
