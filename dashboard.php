<?php
// Fillimi i sesionit për të aksesuar të dhënat e përdoruesit
session_start();
include 'connection.php';

// Kontrolli nëse përdoruesi është i kyçur. Nëse jo, ridrejto në login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Marrja e të dhënave të përdoruesit nga sesioni
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Përdorues";
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : "Email nuk u gjet";

// Përcaktimi i pamjes (view) nga URL, default është 'home'
$view = isset($_GET['view']) ? $_GET['view'] : 'home';

// Fetch products for listing (përdoret në home dhe list)
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Të dhënat për raporte (Përmbledhje sipas kategorisë)
$report_query = "SELECT category, COUNT(*) as total_items, SUM(monthly_price) as total_value FROM products GROUP BY category";
$report_result = mysqli_query($conn, $report_query);

// Fetch users for profile section
$user_query = "SELECT id, name, email, password FROM users ORDER BY id ASC";
$user_list_result = mysqli_query($conn, $user_query);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Paneli i Menaxhimit</title>
    <link rel="stylesheet" href="dashboard.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <div class="user-info">
            <p>Përdoruesi i kyçur:</p>
            <strong><?php echo htmlspecialchars($user_name); ?></strong>
            <small><?php echo htmlspecialchars($user_email); ?></small>
        </div>
        <ul class="menu-list">
            <li><a href="dashboard.php?view=home" class="<?php echo $view == 'home' ? 'active' : ''; ?>">Ballina</a></li>
            <li><a href="dashboard.php?view=add" class="<?php echo $view == 'add' ? 'active' : ''; ?>">Regjistro Produkt</a></li>
            <li><a href="dashboard.php?view=list" class="<?php echo $view == 'list' ? 'active' : ''; ?>">Lista e Produkteve</a></li>
            <li><a href="dashboard.php?view=reports" class="<?php echo $view == 'reports' ? 'active' : ''; ?>">Raportet</a></li>
            <li><a href="dashboard.php?view=profile" class="<?php echo $view == 'profile' ? 'active' : ''; ?>">Përdoruesit</a></li>
            <li><a href="dashboard.php?view=my_profile" class="<?php echo $view == 'my_profile' ? 'active' : ''; ?>">Profili Im</a></li>
        </ul>
        <a href="logout.php" class="logout-btn">Çkyçu (Logout)</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="header">
            <h1>
                <?php 
                    if($view == 'add') echo "Regjistro Produkt të Ri";
                    elseif($view == 'list') echo "Lista e Produkteve";
                    elseif($view == 'reports') echo "Raportet Statistikore";
                    elseif($view == 'profile') echo "Menaxhimi i Përdoruesve";
                    elseif($view == 'my_profile') echo "Profili Im";
                    else echo "Mirë se erdhe, " . htmlspecialchars($user_name) . "!";
                ?>
            </h1>
            <p>
                <?php 
                    if($view == 'home') echo "Ky është paneli i menaxhimit të sistemit tuaj.";
                    else echo "Menaxhoni të dhënat tuaja me lehtësi.";
                ?>
            </p>
        </div>

        <div class="content-body">
            <div id="js-msg" class="success-msg"></div>

            <?php if(isset($_GET['success'])): ?>
                <div class="success-msg" style="display:block;">Veprimi u kreu me sukses!</div>
            <?php endif; ?>

            <!-- VIEW: HOME (Shfaq të gjitha) -->
            <?php if($view == 'home' || $view == 'add'): ?>
                <div class="section-container">
                    <h3>Forma e Regjistrimit</h3>
                    <form action="process_product.php" method="POST" class="product-form">
                        <div class="form-group">
                            <label>Emri i produktit:</label>
                            <input type="text" name="product_name" placeholder="Shënoni emrin e produktit" required>
                        </div>
                        <div class="form-group">
                            <label>Zgjidh kategorinë:</label>
                            <select name="category" required>
                                <option value="">Zgjidhni një opsion...</option>
                                <option value="biznes_vogel">Biznese të vogla</option>
                                <option value="biznes_mesem">Biznese të mesme</option>
                                <option value="biznes_madh">Biznese të mëdha</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Çmimi për muaj (€):</label>
                            <input type="number" name="monthly_price" step="0.01" min="0" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label>Data e regjistrimit:</label>
                            <input type="date" name="registration_date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group full-width">
                            <label>Feature e produktit:</label>
                            <textarea name="product_features" rows="4"></textarea>
                        </div>
                        <div class="form-group checkbox-group">
                            <input type="checkbox" name="is_available" checked>
                            <label>Disponueshmëria (Aktiv)</label>
                        </div>
                        <button type="submit" class="submit-btn">Regjistro Produktin</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- VIEW: REPORTS (Data Grid) -->
            <?php if($view == 'reports'): ?>
                <div class="section-container">
                    <h3>Përmbledhja e Produkteve</h3>
                    <div class="data-grid">
                        <?php while($report = mysqli_fetch_assoc($report_result)): ?>
                            <div class="grid-item" style="text-align: center;">
                                <h4><?php echo str_replace('_', ' ', htmlspecialchars($report['category'])); ?></h4>
                                <div class="value" style="font-size: 24px; font-weight: bold; color: #fff;"><?php echo $report['total_items']; ?> Produkte</div>
                                <div class="sub-value" style="font-size: 12px; color: #3498db; margin-top: 5px;">Vlera totale: <?php echo number_format($report['total_value'], 2); ?> €</div>
                            </div>
                        <?php endwhile; ?>
                        
                        <!-- Një grid item shtesë për totalin e përgjithshëm -->
                        <div class="grid-item" style="border-color: #3498db; text-align: center;">
                            <h4>Totali i Përgjithshëm</h4>
                            <?php 
                                mysqli_data_seek($report_result, 0);
                                $grand_total_items = 0;
                                $grand_total_value = 0;
                                while($r = mysqli_fetch_assoc($report_result)) {
                                    $grand_total_items += $r['total_items'];
                                    $grand_total_value += $r['total_value'];
                                }
                            ?>
                            <div class="value" style="font-size: 24px; font-weight: bold; color: #fff;"><?php echo $grand_total_items; ?> Produkte</div>
                            <div class="sub-value" style="font-size: 12px; color: #3498db; margin-top: 5px;">Vlera totale: <?php echo number_format($grand_total_value, 2); ?> €</div>
                        </div>
                    </div>

                    <h3 style="margin-top: 40px;">Statistikat e Detajuara</h3>
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Kategoria</th>
                                <th>Numri i Produkteve</th>
                                <th>Vlera Totale (€)</th>
                                <th>Përqindja (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($report_result, 0);
                            while($row = mysqli_fetch_assoc($report_result)): 
                                $percentage = ($grand_total_items > 0) ? ($row['total_items'] / $grand_total_items) * 100 : 0;
                            ?>
                                <tr>
                                    <td><strong><?php echo str_replace('_', ' ', htmlspecialchars($row['category'])); ?></strong></td>
                                    <td style="text-align: center;"><?php echo $row['total_items']; ?></td>
                                    <td style="text-align: center;"><?php echo number_format($row['total_value'], 2); ?> €</td>
                                    <td style="text-align: center;">
                                        <div style="background: rgba(255,255,255,0.1); border-radius: 10px; height: 10px; width: 100px; display: inline-block; margin-right: 10px;">
                                            <div style="background: #3498db; height: 100%; width: <?php echo $percentage; ?>%; border-radius: 10px;"></div>
                                        </div>
                                        <?php echo number_format($percentage, 1); ?>%
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background: rgba(52, 152, 219, 0.2); font-weight: bold;">
                                <td>TOTALI</td>
                                <td style="text-align: center;"><?php echo $grand_total_items; ?></td>
                                <td style="text-align: center;"><?php echo number_format($grand_total_value, 2); ?> €</td>
                                <td style="text-align: center;">100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>

            <!-- VIEW: PROFILE (Users List) -->
            <?php if($view == 'profile'): ?>
                <div class="section-container">
                    <h3>Përdoruesit e Regjistruar në Sistem</h3>
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Emri dhe Mbiemri</th>
                                <th>E-mail</th>
                                <th>Fjalëkalimi</th>
                                <th>Veprimet</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($user_list_result) > 0): ?>
                                <?php while($u = mysqli_fetch_assoc($user_list_result)): ?>
                                    <tr id="user-row-<?php echo $u['id']; ?>">
                                        <td><?php echo $u['id']; ?></td>
                                        <td class="u-name"><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
                                        <td class="u-email"><?php echo htmlspecialchars($u['email']); ?></td>
                                        <td class="pass-cell" title="<?php echo $u['password']; ?>"><?php echo $u['password']; ?></td>
                                        <td>
                                            <button onclick="editUser(<?php echo $u['id']; ?>)" class="action-btn edit-btn" id="user-btn-edit-<?php echo $u['id']; ?>">Edito</button>
                                            <button onclick="saveUser(<?php echo $u['id']; ?>)" class="action-btn save-btn" id="user-btn-save-<?php echo $u['id']; ?>">Ruaj</button>
                                            <button onclick="cancelEdit(<?php echo $u['id']; ?>)" class="action-btn cancel-btn" id="user-btn-cancel-<?php echo $u['id']; ?>">Anulo</button>
                                            <button onclick="confirmDelete(<?php echo $u['id']; ?>, 'user')" class="action-btn delete-btn">Fshij</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align:center;">Nuk ka përdorues të regjistruar.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <!-- VIEW: MY PROFILE (Current User Details) -->
            <?php if($view == 'my_profile'): ?>
                <div class="section-container">
                    <h3>Të dhënat e Profilat Tim</h3>
                    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                        <div class="grid-item" style="flex: 1; min-width: 300px; align-items: center; text-align: center;">
                            <div style="width: 100px; height: 100px; background: rgba(52, 152, 219, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 20px;">
                                <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                            </div>
                            <h4><?php echo htmlspecialchars($user_name); ?></h4>
                            <p style="color: rgba(255,255,255,0.6);"><?php echo htmlspecialchars($user_email); ?></p>
                            <div style="margin-top: 20px; padding: 10px; background: rgba(46, 204, 113, 0.2); border-radius: 10px; width: 100%;">
                                <small>Statusi i llogarisë:</small><br>
                                <strong style="color: #2ecc71;">Aktiv (Administrator)</strong>
                            </div>
                        </div>

                        <div class="grid-item" style="flex: 2; min-width: 300px;">
                            <h4>Përditëso të dhënat</h4>
                            <form action="update_user_inline.php" method="POST" class="product-form" style="margin-top: 20px;">
                                <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']; ?>">
                                <input type="hidden" name="redirect" value="dashboard.php?view=my_profile">
                                
                                <div class="form-group">
                                    <label>Emri dhe Mbiemri:</label>
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>E-mail Adresa:</label>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Fjalëkalimi i ri (Lëreni bosh nëse nuk dëshironi ta ndryshoni):</label>
                                    <input type="password" name="new_password" placeholder="********">
                                </div>
                                <button type="submit" class="submit-btn">Ruaj Ndryshimet</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- VIEW: LIST (DataGrid Products) -->
            <?php if($view == 'home' || $view == 'list'): ?>
                <div class="section-container" style="margin-top: 40px;">
                    <h3>Lista e Produkteve</h3>
                    
                    <div class="search-container">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="productSearch" placeholder="Kërko produktet sipas emrit ose kategorisë..." onkeyup="filterProducts()">
                    </div>

                    <div class="data-grid" id="productGrid">
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <div class="grid-item product-card" 
                                     data-name="<?php echo strtolower(htmlspecialchars($row['product_name'])); ?>"
                                     data-category="<?php echo strtolower(htmlspecialchars($row['category'])); ?>">
                                    
                                    <div class="product-card-header">
                                        <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                                        <span class="category-badge"><?php echo str_replace('_', ' ', htmlspecialchars($row['category'])); ?></span>
                                    </div>

                                    <div class="price-display">
                                        <?php echo number_format($row['monthly_price'], 2); ?> €<span> / muaj</span>
                                    </div>

                                    <div class="product-features-preview">
                                        <?php echo htmlspecialchars($row['product_features'] ? $row['product_features'] : 'Nuk ka përshkrim për këtë produkt.'); ?>
                                    </div>

                                    <div class="product-meta">
                                        <div class="meta-info">
                                            <span class="status-dot <?php echo $row['is_available'] ? 'status-active' : 'status-inactive'; ?>"></span>
                                            <?php echo $row['is_available'] ? 'Aktiv' : 'Jo Aktiv'; ?>
                                            <br>
                                            <small>ID: <?php echo $row['id']; ?> | Regj: <?php echo $row['registration_date']; ?></small>
                                        </div>
                                        <div class="card-actions">
                                            <button onclick="editProduct(<?php echo $row['id']; ?>)" class="action-btn edit-btn">Edito</button>
                                            <button onclick="confirmDelete(<?php echo $row['id']; ?>, 'product')" class="action-btn delete-btn">Fshij</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: rgba(255,255,255,0.05); border-radius: 15px;">
                                Nuk ka produkte të regjistruara.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- External Script -->
    <script src="dashboard_script.js"></script>
</body>
</html>