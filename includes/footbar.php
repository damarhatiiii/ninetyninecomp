<footer class="w-full bg-gray-50 border-t border-gray-200 text-gray-700">
    <div class="max-w-screen-xl mx-auto px-6 py-4 flex flex-col sm:flex-row justify-between items-center text-base font-medium">
        <!-- Logo + nama -->
        <div class="flex items-center space-x-3 mb-3 sm:mb-0">
            <img src="<?= BASE_PATH; ?>/assets/sssda.png" class="h-7" alt="Logo" />
            <span class="text-lg font-semibold text-gray-900">NinetyNineComp</span>
        </div>

        <!-- Link menu -->
        <ul class="flex gap-6 list-none text-gray-600">
            <li><a class="hover:text-blue-600 transition-colors" href="#">Tentang</a></li>
            <li><a class="hover:text-blue-600 transition-colors" href="#">Privasi</a></li>
            <li><a class="hover:text-blue-600 transition-colors" href="#">Kontak</a></li>
        </ul>
    </div>

    <div class="text-center text-sm text-gray-500 pb-5 border-t border-gray-200 mt-3">
        © <?php echo date("Y"); ?> 
        <span class="text-gray-900 font-semibold">NinetyNineComp</span>. All rights reserved.
    </div>
</footer>
