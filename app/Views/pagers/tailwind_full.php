<?php
$current = 1;
foreach ($pager->links() as $link) {
    if ($link['active']) {
        $current = $link['title'];
        break;
    }
}
?>
<nav aria-label="Page navigation" class="flex items-center justify-center">
    <ul class="flex items-center -space-x-px h-8 text-sm">
        <!-- Previous -->
        <li>
            <?php if ($pager->hasPrevious()) : ?>
                <a href="<?= $pager->getPrevious() ?>" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">
                    <span class="sr-only">Previous</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                </a>
            <?php else : ?>
                <span class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-300 bg-white border border-e-0 border-gray-300 rounded-s-lg cursor-not-allowed opacity-50">
                     <span class="sr-only">Previous</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                </span>
            <?php endif ?>
        </li>

        <!-- Current Page -->
        <li>
            <span class="flex items-center justify-center px-3 h-8 leading-tight text-primary-600 border border-gray-300 bg-primary-50 hover:bg-primary-100 hover:text-primary-700 z-10 font-bold">
                <?= $current ?>
            </span>
        </li>

        <!-- Next -->
        <li>
            <?php if ($pager->hasNext()) : ?>
                <a href="<?= $pager->getNext() ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">
                    <span class="sr-only">Next</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </a>
            <?php else : ?>
                <span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-300 bg-white border border-gray-300 rounded-e-lg cursor-not-allowed opacity-50">
                    <span class="sr-only">Next</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </span>
            <?php endif ?>
        </li>
    </ul>
</nav>
