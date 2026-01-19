<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation" class="flex items-center justify-center">
    <ul class="flex items-center -space-x-px h-8 text-sm">
        <!-- Previous -->
        <li>
            <?php if ($pager->hasPrevious()) : ?>
                <a href="<?= $pager->getFirst() ?>" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700" aria-label="First">
                    <span class="sr-only">First</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4m6-8L7 5l4 4"/>
                    </svg>
                </a>
            <?php else : ?>
                <span class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-300 bg-white border border-e-0 border-gray-300 rounded-s-lg cursor-not-allowed opacity-50">
                    <span class="sr-only">First</span>
                     <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4m6-8L7 5l4 4"/>
                    </svg>
                </span>
            <?php endif ?>
        </li>
        <li>
             <?php if ($pager->hasPrevious()) : ?>
                <a href="<?= $pager->getPrevious() ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700" aria-label="Previous">
                    <span class="sr-only">Previous</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                </a>
            <?php else : ?>
                 <span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-300 bg-white border border-gray-300 cursor-not-allowed opacity-50">
                    <span class="sr-only">Previous</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                </span>
            <?php endif ?>
        </li>

        <!-- Links -->
        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="flex items-center justify-center px-3 h-8 leading-tight <?= $link['active'] ? 'text-primary-600 border border-primary-300 bg-primary-50 hover:bg-primary-100 hover:text-primary-700 font-bold z-10' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <!-- Next -->
        <li>
            <?php if ($pager->hasNext()) : ?>
                <a href="<?= $pager->getNext() ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700" aria-label="Next">
                    <span class="sr-only">Next</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </a>
            <?php else : ?>
                <span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-300 bg-white border border-gray-300 cursor-not-allowed opacity-50">
                    <span class="sr-only">Next</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </span>
            <?php endif ?>
        </li>
        <li>
            <?php if ($pager->hasNext()) : ?>
                <a href="<?= $pager->getLast() ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700" aria-label="Last">
                    <span class="sr-only">Last</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
                    </svg>
                </a>
            <?php else : ?>
                <span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-300 bg-white border border-gray-300 rounded-e-lg cursor-not-allowed opacity-50">
                    <span class="sr-only">Last</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
                    </svg>
                </span>
            <?php endif ?>
        </li>
    </ul>
</nav>
