<?php
// ---------- DB CONNECTION ----------
$host = "localhost";
$user = "root";
$pass = "";
$db   = "stock_master";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ---------- HANDLE NEW RECEIPT SUBMISSION ----------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_receipt"])) {
  $reference_code  = trim($_POST["reference_code"] ?? "");
  $vendor          = trim($_POST["vendor"] ?? "");
  $scheduled_date  = $_POST["scheduled_date"] ?? "";
  $source_document = trim($_POST["source_document"] ?? "");
  $status          = trim($_POST["status"] ?? "Ready");

  if ($reference_code !== "" && $vendor !== "" && $scheduled_date !== "") {
    $stmt = $conn->prepare(
      "INSERT INTO receipts (reference_code, vendor, scheduled_date, source_document, status)
       VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
      "sssss",
      $reference_code,
      $vendor,
      $scheduled_date,
      $source_document,
      $status
    );
    $stmt->execute();
    $stmt->close();
  }

  header("Location: receipts.php");
  exit;
}

// ---------- FETCH RECEIPTS ----------
$receipts = [];
$result = $conn->query("SELECT * FROM receipts ORDER BY id DESC");
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $receipts[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>StockMaster - Receipts</title>

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

    <!-- Create Receipt Modal -->
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
        <div
          class="p-6 border-b border-gray-100 flex justify-between items-center"
        >
          <h3 class="text-xl font-bold text-gray-900">Create Receipt</h3>
          <button
            onclick="toggleModal()"
            class="text-gray-400 hover:text-gray-600"
          >
            <i data-lucide="x" class="w-6 h-6"></i>
          </button>
        </div>

        <div class="p-6 overflow-y-auto">
          <form class="space-y-6" method="POST" action="receipts.php">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Reference</label
                >
                <input
                  type="text"
                  name="reference_code"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                  placeholder="e.g. WH/IN/00017"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Vendor</label
                >
                <input
                  type="text"
                  name="vendor"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                  placeholder="e.g. Azure Interior"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Scheduled Date</label
                >
                <input
                  type="date"
                  name="scheduled_date"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Source Document</label
                >
                <input
                  type="text"
                  name="source_document"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                  placeholder="e.g. PO00020"
                />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2"
                  >Status</label
                >
                <select
                  name="status"
                  class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                >
                  <option value="Ready">Ready</option>
                  <option value="Waiting">Waiting</option>
                  <option value="Done">Done</option>
                </select>
              </div>
            </div>

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
                name="create_receipt"
                class="px-6 py-2.5 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg shadow-lg shadow-indigo-200 transition-colors"
              >
                Save Receipt
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
          class="flex items-center w-full p-3 rounded-lg mb-1 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
        >
          <i data-lucide="package" class="w-5 h-5 mr-3"></i>
          Products
        </a>

        <div
          class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 pl-3 mt-6"
        >
          Operations
        </div>

        <!-- Active Receipts Link -->
        <a
          href="receipts.php"
          data-requires-auth="true"
          class="flex items-center w-full p-3 rounded-lg mb-1 bg-indigo-50 text-indigo-600 font-medium transition-colors"
        >
          <i data-lucide="arrow-down-left" class="w-5 h-5 mr-3"></i>
          Receipts
        </a>
        <a
          href="delivery.php"
          data-requires-auth="true"
          class="flex items-center w-full p-3 rounded-lg mb-1 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
        >
          <i data-lucide="truck" class="w-5 h-5 mr-3"></i>
          Delivery Orders
        </a>
        <a
          href="inventoryadj.php"
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

      <!-- Bottom profile section -->
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
          <div>
            <h2 class="text-lg font-semibold text-gray-800 hidden sm:block">
              Receipts
            </h2>
            <p class="text-xs text-gray-500 hidden sm:block">
              Incoming Stock Operations
            </p>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <button
            onclick="toggleModal()"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center"
          >
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Create Receipt
          </button>
        </div>
      </header>

      <!-- Main Area -->
      <main class="flex-1 overflow-y-auto p-4 lg:p-8">
        <div class="max-w-7xl mx-auto">
          <!-- Stats / Kanban Status (left static for now) -->
          <div
            class="flex flex-col sm:flex-row gap-4 mb-6 overflow-x-auto pb-2"
          >
            <div
              class="bg-white border-l-4 border-indigo-500 rounded-xl shadow-sm p-4 flex-1 min-w-[200px]"
            >
              <div class="text-sm text-gray-500 mb-1">To Process</div>
              <div class="text-2xl font-bold text-gray-900">4</div>
            </div>
            <div
              class="bg-white border-l-4 border-orange-500 rounded-xl shadow-sm p-4 flex-1 min-w-[200px]"
            >
              <div class="text-sm text-gray-500 mb-1">Late</div>
              <div class="text-2xl font-bold text-orange-600">1</div>
            </div>
            <div
              class="bg-white border-l-4 border-blue-500 rounded-xl shadow-sm p-4 flex-1 min-w-[200px]"
            >
              <div class="text-sm text-gray-500 mb-1">Waiting</div>
              <div class="text-2xl font-bold text-blue-600">2</div>
            </div>
            <div
              class="bg-white border-l-4 border-gray-300 rounded-xl shadow-sm p-4 flex-1 min-w-[200px]"
            >
              <div class="text-sm text-gray-500 mb-1">Back Orders</div>
              <div class="text-2xl font-bold text-gray-600">0</div>
            </div>
          </div>

          <!-- Filters & Search (UI only, no DB filter yet) -->
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
                    placeholder="Search reference, vendor..."
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
                <button
                  class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-sm font-medium"
                >
                  All
                </button>
                <button
                  class="px-3 py-1.5 rounded-lg bg-gray-50 text-gray-600 text-sm font-medium hover:bg-gray-100"
                >
                  Ready
                </button>
                <button
                  class="px-3 py-1.5 rounded-lg bg-gray-50 text-gray-600 text-sm font-medium hover:bg-gray-100"
                >
                  Waiting
                </button>
                <button
                  class="px-3 py-1.5 rounded-lg bg-gray-50 text-gray-600 text-sm font-medium hover:bg-gray-100"
                >
                  Done
                </button>
              </div>
            </div>
          </div>

          <!-- List View -->
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
                    <th class="p-4">Reference</th>
                    <th class="p-4">Vendor</th>
                    <th class="p-4">Scheduled Date</th>
                    <th class="p-4">Source Document</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <?php if (!empty($receipts)): ?>
                    <?php foreach ($receipts as $receipt): ?>
                      <?php
                        $ref   = htmlspecialchars($receipt["reference_code"]);
                        $vend  = htmlspecialchars($receipt["vendor"]);
                        $src   = htmlspecialchars($receipt["source_document"]);
                        $stat  = htmlspecialchars($receipt["status"]);
                        $date  = $receipt["scheduled_date"];
                        $dateFormatted = $date ? date("M j, Y", strtotime($date)) : "";

                        $statusClass = "bg-gray-200 text-gray-700";
                        if (strtolower($stat) === "ready") {
                          $statusClass = "bg-blue-100 text-blue-700";
                        } elseif (strtolower($stat) === "waiting") {
                          $statusClass = "bg-yellow-100 text-yellow-700";
                        } elseif (strtolower($stat) === "done") {
                          $statusClass = "bg-gray-200 text-gray-700";
                        }
                      ?>
                      <tr
                        class="hover:bg-gray-50 transition-colors group cursor-pointer"
                      >
                        <td class="p-4">
                          <input type="checkbox" class="rounded border-gray-300" />
                        </td>
                        <td class="p-4 font-medium text-indigo-600">
                          <?php echo $ref; ?>
                        </td>
                        <td class="p-4 text-gray-900">
                          <?php echo $vend; ?>
                        </td>
                        <td class="p-4">
                          <?php echo $dateFormatted; ?>
                        </td>
                        <td class="p-4 text-gray-500">
                          <?php echo $src; ?>
                        </td>
                        <td class="p-4">
                          <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusClass; ?>"
                          >
                            <?php echo $stat; ?>
                          </span>
                        </td>
                        <td class="p-4 text-right">
                          <button
                            class="text-indigo-600 hover:text-indigo-800 font-medium text-xs px-3 py-1 border border-indigo-200 rounded-md bg-indigo-50 hover:bg-indigo-100"
                          >
                            Validate
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="p-4 text-center text-gray-500">
                        No receipts found. Click "Create Receipt" to add one.
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
                >Showing <?php echo count($receipts); ?> operation(s)</span
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

    <script>
      lucide.createIcons();

      // Read login state
      let isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

      // Block direct access if not logged in
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

      function toggleModal() {
        const modal = document.getElementById("create-modal");
        if (modal.classList.contains("hidden")) {
          modal.classList.remove("hidden");
        } else {
          modal.classList.add("hidden");
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
