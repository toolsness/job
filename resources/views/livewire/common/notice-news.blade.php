<section id="notice-news"
    class="bg-white shadow-md border border-black border-solid max-w-2xl w-full relative rounded-lg overflow-hidden">
    <div class="flex overflow-hidden relative"
        :class="{
            'bg-[#e61919] hover:bg-[#e61919]': '{{ $tab }}'
            === 'notice',
            'bg-[#1b98ca] hover:bg-[#1b98ca]': '{{ $tab }}'
            !== 'notice'
        }">
        <a href="#notices-content" wire:click="setTab('notice')"
            class="text-center text-white font-bold py-2 transition-colors duration-300 text-sm relative z-10"
            :class="{
                'bg-[#41b6e6] hover:bg-[#1b98ca] flex-[3] shadow-md border-r border-black rounded-tr-2xl': '{{ $tab }}'
                === 'notice',
                'bg-[#1b98ca] border-b border-black flex-[2]': '{{ $tab }}'
                !== 'notice'
            }">
            Notice
        </a>
        <a href="#news-content" wire:click="setTab('news')"
            class="text-center text-white font-bold py-2 transition-colors duration-300 text-sm relative z-10"
            :class="{
                'bg-[#ff5050] hover:bg-[#e61919] flex-[3] shadow-md border-l border-black rounded-tl-2xl': '{{ $tab }}'
                === 'news',
                'bg-[#e61919] border-b border-black flex-[2]': '{{ $tab }}'
                !== 'news'
            }">
            News
        </a>
        <div class="absolute inset-y-0 w-1/2 shadow-md transition-transform duration-300 ease-in-out"
            :class="{
                'left-0 transform translate-x-full': '{{ $tab }}'
                === 'news',
                'right-0 transform -translate-x-full': '{{ $tab }}'
                === 'notice'
            }">
        </div>
    </div>
    <div class="p-6">
        @if ($tab === 'notice')
            <div id="notices-content">
                <ul class="text-sm text-gray-800 leading-relaxed space-y-3 text-left list-none">
                    @forelse($notices as $notice)
                        <li class="truncate cursor-pointer text-black hover:text-blue-800 hover:font-semibold"
                            wire:click="showFullContent('{{ base64_encode($notice->content) }}', '{{ $notice->updated_at }}', 'notice')">
                            <i class="fas fa-bell mr-2"></i>{{ $notice->content }}
                        </li>
                    @empty
                        <li>No notices available.</li>
                    @endforelse
                </ul>
            </div>
        @else
            <div id="news-content">
                <ul class="text-sm text-gray-800 leading-relaxed space-y-3 text-left list-none">
                    @forelse($news as $newsItem)
                        <li class="truncate cursor-pointer text-black hover:text-blue-800 hover:font-semibold"
                            wire:click="showFullContent('{{ base64_encode($newsItem->content) }}', '{{ $newsItem->updated_at }}', 'news')">
                            <i class="fas fa-newspaper mr-2"></i>{{ $newsItem->content }}
                        </li>
                    @empty
                        <li>No news available.</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>

    <x-popup wire:model="showContentModal">
        <div class="p-6">
            <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                <i class="fas {{ $contentType === 'notice' ? 'fa-bell' : 'fa-newspaper' }} text-blue-600"></i>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-grow">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    {{ ucfirst($contentType) }}
                </h3>
                <p class="text-sm text-gray-500">Last updated at: {{ $contentDate }}</p>
            </div>
            <div class="mt-2">
                <hr class="border-gray-300">
            </div>
            <div class="mt-4 bg-gray-50 p-4 rounded-lg max-h-96 overflow-y-auto">
                <p class="text-sm text-gray-700 whitespace-pre-line break-words">{{ trim($fullContent) }}</p>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" wire:click="closeModal" wire:loading.attr="disabled"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <span wire:loading.remove wire:target="closeModal">OK</span>
                    <span wire:loading wire:target="closeModal">
                        <i class="fa fa-spinner fa-spin"></i> Loading...
                    </span>
                </button>
            </div>
        </div>
    </x-popup>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let lastScrollPosition = 0;

            Livewire.on('content-changed', (event) => {
                setTimeout(() => {
                    window.scrollTo(0, lastScrollPosition);
                }, 100);
            });

            window.addEventListener('scroll', () => {
                lastScrollPosition = window.pageYOffset;
            });
        });
    </script>
</section>


