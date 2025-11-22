<?php
// ---------- DB CONNECTION ----------
$host = "localhost";
$user = "root";
$pass = "";
$db   = "stock_master"; // DB name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ---------- HANDLE DELETE PRODUCT ----------
if (isset($_GET["delete_id"])) {
  $delete_id = (int)$_GET["delete_id"];
  if ($delete_id > 0) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
  }
  header("Location: products.php");
  exit;
}

// ---------- HANDLE NEW PRODUCT SUBMISSION ----------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_product"])) {
  $name         = trim($_POST["product_name"] ?? "");
  $product_type = trim($_POST["product_type"] ?? "");
  $internal_ref = trim($_POST["internal_ref"] ?? "");
  $sales_price  = $_POST["sales_price"] !== "" ? (float)$_POST["sales_price"] : 0;
  $cost         = $_POST["cost"] !== "" ? (float)$_POST["cost"] : 0;
  $category     = trim($_POST["category"] ?? "");
  $on_hand      = 0.00; // default

  if ($name !== "") {
    $stmt = $conn->prepare(
      "INSERT INTO products (name, product_type, internal_ref, sales_price, cost, category, on_hand)
       VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
      "sssddsd",
      $name,
      $product_type,
      $internal_ref,
      $sales_price,
      $cost,
      $category,
      $on_hand
    );
    $stmt->execute();
    $stmt->close();
  }

  header("Location: products.php");
  exit;
}

// ---------- FETCH PRODUCTS ----------
$products = [];
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>StockMaster - Products</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <style>
      body {
        font-family: "Inter", sans-serif;
      }
      ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
      }
      ::-webkit-scrollbar-track {
        background: transparent;
      }
      ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
      }
      .sidebar-transition {
        transition: transform 0.3s ease-in-out;
      }

      /* Modal Animation */
      .modal {
        transition: opacity 0.25s ease;
      }
      .modal-content {
        transition: transform 0.25s ease;
      }
      .modal.hidden {
        opacity: 0;
        pointer-events: none;
      }
      .modal.hidden .modal-content {
        transform: scale(0.95);
      }
    </style>
  </head>
  <body class="bg-gray-50 text-slate-800 h-screen flex overflow-hidden">
    <!-- Mobile Overlay -->
    <div
      id="mobile-overlay"
      onclick="toggleSidebar()"
      class="fixed inset-0 bg-black/20 z-20 hidden lg:hidden"
    ></div>

    <!-- Create Product Modal -->
    <div
      id="create-modal"
      class="fixed inset-0 z-50 flex items-center justify-center hidden modal"
    >
      <div
        class="absolute inset-0 bg-black/40 backdrop-blur-sm"
        onclick="toggleModal()"
      ></div>
      <div
        class="bg-white rounded-2xl shadow-xl w-full max-w-2xl relative z-10 m-4 modal-content flex flex-col max-h-[90vh]"
      >
        <!-- Modal Header -->
        <div
          class="p-6 border-b border-gray-100 flex justify-between items-center"
        >
          <h3 class="text-xl font-bold text-gray-900">Create New Product</h3>
          <button
            onclick="toggleModal()"
            class="text-gray-400 hover:text-gray-600"
          >
            <i data-lucide="x" class="w-6 h-6"></i>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto">
          <form class="space-y-6" method="POST" action="products.php">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Product Name</label
                >
                <input
                  type="text"
                  name="product_name"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all"
                  placeholder="e.g. Office Chair"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Product Type</label
                >
                <select
                  name="product_type"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                >
                  <option value="Storable Product">Storable Product</option>
                  <option value="Consumable">Consumable</option>
                  <option value="Service">Service</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Internal Reference</label
                >
                <input
                  type="text"
                  name="internal_ref"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                  placeholder="e.g. FUR-001"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Sales Price</label
                >
                <div class="relative">
                  <span class="absolute left-3 top-3 text-gray-500">$</span>
                  <input
                    type="number"
                    step="0.01"
                    name="sales_price"
                    class="w-full pl-8 p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                    placeholder="0.00"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Cost</label
                >
                <div class="relative">
                  <span class="absolute left-3 top-3 text-gray-500">$</span>
                  <input
                    type="number"
                    step="0.01"
                    name="cost"
                    class="w-full pl-8 p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                    placeholder="0.00"
                  />
                </div>
              </div>

              <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Category</label
                >
                <select
                  name="category"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                >
                  <option value="Furniture">All / Furniture</option>
                  <option value="Electronics">All / Electronics</option>
                  <option value="Office Supplies">All / Office Supplies</option>
                </select>
              </div>
            </div>

            <!-- Modal Footer -->
            <div
              class="pt-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex justify-end space-x-3 -mx-6 -mb-6 px-6 pb-6"
            >
              <button
                type="button"
                onclick="toggleModal()"
                class="px-6 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200 rounded-lg transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                name="create_product"
                class="px-6 py-2.5 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg shadow-lg shadow-indigo-200 transition-colors"
              >
                Save Product
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <aside
      id="sidebar"
      class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col h-full"
    >
      <!-- Logo -->
      <div class="p-6 flex items-center border-b border-gray-100">
        <div
          class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3"
        >
          <i data-lucide="package" class="text-white w-5 h-5"></i>
        </div>
        <h1 class="text-xl font-bold text-gray-900 tracking-tight">
          StockMaster
        </h1>
        <button
          onclick="toggleSidebar()"
          class="lg:hidden ml-auto text-gray-400"
        >
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>

      <!-- Nav Links -->
      <nav class="flex-1 overflow-y-auto p-4 space-y-1">
        <div
          class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 pl-3 mt-2"
        >
          Main Menu
        </div>

        <a
          href="dashboard.html"
          class="flex items-center w-full p-3 rounded-lg mb-1 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
        >
          <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
          Dashboard
        </a>

        <a
          href="products.php"
          data-requires-auth="true"
          class="flex items-center w-full p-3 rounded-lg mb-1 bg-indigo-50 text-indigo-600 font-medium transition-colors"
        >
          <i data-lucide="package" class="w-5 h-5 mr-3"></i>
          Products
        </a>

        <div
          class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 pl-3 mt-6"
        >
          Operations
        </div>

        <a
          href="receipts.html"
          data-requires-auth="true"
          class="flex items-center w-full p-3 rounded-lg mb-1 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
        >
          <i data-lucide="arrow-down-left" class="w-5 h-5 mr-3"></i>
          Receipts
        </a>
        <a
          href="delivery.html"
          data-requires-auth="true"
          class="flex items-center w-full p-3 rounded-lg mb-1 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
        >
          <i data-lucide="truck" class="w-5 h-5 mr-3"></i>
          Delivery Orders
        </a>
        <a
          href="inventoryadj.html"
          data-requires-auth="true"
          class="flex items-center w-full p-3 rounded-lg mb-1 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
        >
          <i data-lucide="clipboard-list" class="w-5 h-5 mr-3"></i>
          Inventory Adj.
        </a>
        <a
          href="movehistory.html"
          data-requires-auth="true"
          class="flex items-center w-full p-3 rounded-lg mb-1 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
        >
          <i data-lucide="arrow-left-right" class="w-5 h-5 mr-3"></i>
          Move History
        </a>

        <div
          class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 pl-3 mt-6"
        >
          Configuration
        </div>

        <a
          href="settings.html"
          data-requires-auth="true"
          class="flex items-center w-full p-3 rounded-lg mb-1 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
        >
          <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
          Settings
        </a>
      </nav>

      <!-- Bottom Profile Area -->
      <div class="p-4 border-t border-gray-100 relative">
        <!-- User Profile (Visible if Logged In) -->
        <div id="user-profile" class="hidden">
          <button
            onclick="toggleProfileMenu()"
            class="w-full flex items-center p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors text-left"
          >
            <div
              class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold border-2 border-white shadow-sm"
            >
              <span id="user-avatar-text">U</span>
            </div>
            <div class="ml-3 flex-1 overflow-hidden">
              <p class="text-xs text-gray-500 truncate">Logged in as</p>
              <p
                id="user-login-id"
                class="text-sm font-semibold text-gray-900 truncate"
              >
                User
              </p>
            </div>
            <i data-lucide="chevron-up" class="w-4 h-4 text-gray-400"></i>
          </button>
          <div
            id="profile-menu"
            class="hidden absolute bottom-20 left-4 right-4 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50"
          >
            <a
              href="#"
              class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 flex items-center"
            >
              <i data-lucide="user" class="w-4 h-4 mr-2"></i> My Profile
            </a>
            <div class="border-t border-gray-100"></div>
            <button
              onclick="logout()"
              class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center"
            >
              <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Log Out
            </button>
          </div>
        </div>

        <!-- Guest Profile -->
        <div id="guest-profile" class="hidden">
          <div class="p-3 bg-gray-50 rounded-xl text-center space-y-3">
            <p class="text-sm text-gray-500 mb-2">Welcome to StockMaster</p>
            <a
              href="login.php"
              class="block w-full py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors"
              >Log In</a
            >
            <a
              href="signup.php"
              class="block w-full py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
              >Sign Up</a
            >
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden w-full">
      <!-- Header -->
      <header
        class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 lg:px-8 z-10 shrink-0"
      >
        <div class="flex items-center">
          <button
            onclick="toggleSidebar()"
            class="mr-4 lg:hidden p-2 -ml-2 text-gray-500 hover:bg-gray-100 rounded-lg"
          >
            <i data-lucide="menu" class="w-6 h-6"></i>
          </button>
          <h2 class="text-lg font-semibold text-gray-800 hidden sm:block">
            Products
          </h2>
        </div>

        <div class="flex items-center space-x-4">
          <button
            onclick="toggleModal()"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center"
          >
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Create Product
          </button>
        </div>
      </header>

      <!-- Main Area -->
      <main class="flex-1 overflow-y-auto p-4 lg:p-8">
        <div class="max-w-7xl mx-auto">
          <!-- Filters & Search -->
          <div
            class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6"
          >
            <div
              class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4"
            >
              <div class="flex items-center space-x-2 flex-1">
                <div class="relative flex-1 max-w-md">
                  <input
                    type="text"
                    id="searchInput"
                    onkeyup="searchTable()"
                    placeholder="Search products..."
                    class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
                  />
                  <i
                    data-lucide="search"
                    class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"
                  ></i>
                </div>
                <button
                  class="p-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-100"
                >
                  <i data-lucide="filter" class="w-4 h-4"></i>
                </button>
              </div>

              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500 mr-2">Group By:</span>
                <select
                  class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-indigo-500"
                >
                  <option>Category</option>
                  <option>Type</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Product Table -->
          <div
            class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden"
          >
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm text-gray-600">
                <thead
                  class="bg-gray-50 text-gray-500 font-medium uppercase text-xs border-b border-gray-100"
                >
                  <tr>
                    <th class="p-4 w-16">
                      <input type="checkbox" class="rounded border-gray-300" />
                    </th>
                    <th class="p-4">Product Name</th>
                    <th class="p-4">Internal Ref</th>
                    <th class="p-4">Category</th>
                    <th class="p-4 text-right">Price</th>
                    <th class="p-4 text-right">Cost</th>
                    <th class="p-4 text-right">On Hand</th>
                    <th class="p-4 w-20 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="productTableBody" class="divide-y divide-gray-100">
                  <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                      <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="p-4">
                          <input type="checkbox" class="rounded border-gray-300" />
                        </td>
                        <td class="p-4">
                          <div class="flex items-center">
                            <div
                              class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 mr-3"
                            >
                              <i data-lucide="image" class="w-5 h-5"></i>
                            </div>
                            <div>
                              <div class="font-medium text-gray-900">
                                <?php echo htmlspecialchars($product["name"]); ?>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="p-4 text-gray-500">
                          <?php echo htmlspecialchars($product["internal_ref"]); ?>
                        </td>
                        <td class="p-4">
                          <span class="px-2 py-1 rounded-md bg-gray-100 text-xs">
                            <?php echo htmlspecialchars($product["category"]); ?>
                          </span>
                        </td>
                        <td class="p-4 text-right font-medium text-gray-900">
                          $<?php echo number_format((float)$product["sales_price"], 2); ?>
                        </td>
                        <td class="p-4 text-right text-gray-500">
                          $<?php echo number_format((float)$product["cost"], 2); ?>
                        </td>
                        <td class="p-4 text-right">
                          <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo ((float)$product["on_hand"] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>"
                          >
                            <?php echo number_format((float)$product["on_hand"], 2); ?> Units
                          </span>
                        </td>
                        <td class="p-4 text-right">
                          <button
                            type="button"
                            onclick="confirmDelete(<?php echo (int)$product['id']; ?>)"
                            class="text-gray-400 hover:text-red-600 transition-colors"
                            title="Delete product"
                          >
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                      <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">
                          No products found. Click "Create Product" to add one.
                        </td>
                      </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div
              class="p-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between"
            >
              <span class="text-xs text-gray-500"
                >Showing <?php echo count($products); ?> product(s)</span
              >
              <div class="flex space-x-2">
                <button
                  class="p-1 rounded-md hover:bg-gray-200 disabled:opacity-50"
                  disabled
                >
                  <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button class="p-1 rounded-md hover:bg-gray-200">
                  <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Scripts -->
    <script>
      lucide.createIcons();

      // Read login state from localStorage
      let isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

      // If user is not logged in, block direct access to this page
      if (!isLoggedIn) {
        window.location.href = "access-denied.html";
      }

      function updateUserUI() {
        const loginId = localStorage.getItem("loginId") || "User";
        const loginIdElement = document.getElementById("user-login-id");
        const avatarTextElement = document.getElementById("user-avatar-text");

        if (loginIdElement) {
          loginIdElement.innerText = loginId;
        }
        if (avatarTextElement && loginId.length > 0) {
          avatarTextElement.innerText = loginId.charAt(0).toUpperCase();
        }
      }

      function checkLoginState() {
        const userProfile = document.getElementById("user-profile");
        const guestProfile = document.getElementById("guest-profile");

        if (isLoggedIn) {
          userProfile.classList.remove("hidden");
          guestProfile.classList.add("hidden");
          updateUserUI();
        } else {
          userProfile.classList.add("hidden");
          guestProfile.classList.remove("hidden");
        }
      }

      function setupProtectedLinks() {
        const protectedLinks = document.querySelectorAll(
          "[data-requires-auth='true']"
        );
        protectedLinks.forEach((link) => {
          link.addEventListener("click", function (e) {
            if (!isLoggedIn) {
              e.preventDefault();
              window.location.href = "access-denied.html";
            }
          });
        });
      }

      function toggleProfileMenu() {
        document.getElementById("profile-menu").classList.toggle("hidden");
      }

      function logout() {
        localStorage.setItem("isLoggedIn", "false");
        localStorage.removeItem("loginId");
        isLoggedIn = false;
        window.location.href = "login.php";
      }

      // Sidebar Logic
      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("mobile-overlay");

        if (sidebar.classList.contains("-translate-x-full")) {
          sidebar.classList.remove("-translate-x-full");
          overlay.classList.remove("hidden");
        } else {
          sidebar.classList.add("-translate-x-full");
          overlay.classList.add("hidden");
        }
      }

      // Modal Logic
      function toggleModal() {
        const modal = document.getElementById("create-modal");
        if (modal.classList.contains("hidden")) {
          modal.classList.remove("hidden");
        } else {
          modal.classList.add("hidden");
        }
      }

      // Search Logic
      function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.getElementById("productTableBody");
        const tr = table.getElementsByTagName("tr");

        for (let i = 0; i < tr.length; i++) {
          const tdName = tr[i].getElementsByTagName("td")[1];
          const tdRef = tr[i].getElementsByTagName("td")[2];
          if (tdName || tdRef) {
            const txtValueName = tdName.textContent || tdName.innerText;
            const txtValueRef = tdRef.textContent || tdRef.innerText;
            if (
              txtValueName.toUpperCase().indexOf(filter) > -1 ||
              txtValueRef.toUpperCase().indexOf(filter) > -1
            ) {
              tr[i].style.display = "";
            } else {
              tr[i].style.display = "none";
            }
          }
        }
      }

      // Delete confirmation
      function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this product?")) {
          window.location.href = "products.php?delete_id=" + encodeURIComponent(id);
        }
      }

      // Init
      setupProtectedLinks();
      checkLoginState();
    </script>
  </body>
</html>
<?php
$conn->close();
?>
