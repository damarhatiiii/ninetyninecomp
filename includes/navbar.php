<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        
<?php
// Deteksi halaman aktif berdasarkan nama file
$current_page = basename($_SERVER['PHP_SELF']);
?>

    <nav class="bg-white border-gray-200 dark:bg-gray-900 shadow">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="dashboard.php" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="assets/sssda.png" class="h-8" alt="NinetyNine Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">
            NinetyNineComp
        </span>
        </a>

        <button data-collapse-toggle="navbar-default" type="button"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden 
        hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 
        dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
            stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
        </svg>
        </button>

        <div class="hidden w-full md:block md:w-auto ml-auto" id="navbar-default">
        <ul
            class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg 
            bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 
            md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">

            <!-- Dashboard -->
            <li>
            <a href="dashboard.php"
                class="block py-2 px-3 rounded-sm md:p-0 
                <?= $current_page == 'dashboard.php'
                    ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 dark:text-blue-500'
                    : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700'; ?>">
                Dashboard
            </a>
            </li>

            <!-- Produk -->
            <li>
            <a href="produk.php"
                class="block py-2 px-3 rounded-sm md:p-0 
                <?= $current_page == 'produk.php'
                    ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 dark:text-blue-500'
                    : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700'; ?>">
                Produk
            </a>
            </li>

            <!-- Tambah -->
            <li>
            <a href="tambah.php"
                class="block py-2 px-3 rounded-sm md:p-0 
                <?= $current_page == 'tambah.php'
                    ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 dark:text-blue-500'
                    : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700'; ?>">
                Tambah
            </a>
            </li>

            <!-- Akun -->
            <li>
            <a href="karyawan.php"
                class="block py-2 px-3 rounded-sm md:p-0 
                <?= $current_page == 'karyawan.php'
                    ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 dark:text-blue-500'
                    : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700'; ?>">
                Akun
            </a>
            </li>

        </ul>
        </div>
    </div>
    </nav>
