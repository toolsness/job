<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Messages</h1>
        <button wire:click="toggleNewMessageModal" wire:loading.attr="disabled"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            <span wire:loading.remove wire:target="toggleNewMessageModal">New Message</span>
            <span wire:loading wire:target="toggleNewMessageModal">
                <i class="fa fa-spinner fa-spin"></i> Loading...
            </span>
        </button>
    </div>

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search messages..."
            class="w-full px-3 py-2 border rounded-md">
    </div>

    <div class="flex mb-4">
        <button wire:click="switchMessageType('inbox')"
            class="mr-4 {{ $messageType === 'inbox' ? 'font-bold rounded bg-slate-300 py-2 px-4' : 'rounded py-2 px-4' }}">Inbox</button>
        <button wire:click="switchMessageType('sent')"
            class="{{ $messageType === 'sent' ? 'font-bold rounded bg-slate-300 py-2 px-4' : 'rounded py-2 px-4' }}">Sent</button>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <div class="w-full md:w-1/3 bg-white shadow-md rounded-lg p-4 border-2 border-black border-solid">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b">Message Threads</h2>
            @if ($threadPaginator->isNotEmpty())
                @foreach ($threadPaginator as $thread)
                    <div wire:click="selectThread({{ $thread->id }})"
                        class="cursor-pointer border-b border-black py-2 {{ $thread->latestMessage->read_at && $messageType === 'inbox' ? 'opacity-50' : '' }} {{ $selectedThread && $selectedThread->id == $thread->id ? 'bg-blue-100' : '' }}">
                        <div class="flex justify-between items-center px-1">
                            <span class="font-semibold text-gray-700">{{ $thread->title }}</span>
                            <span
                                class="text-sm text-gray-500">{{ $thread->latestMessage->sent_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate px-1">{{ $thread->latestMessage->content }}</p>
                        <span
                            class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                            @if ($thread->inquiry_type == 'general') bg-purple-200 text-purple-800
                            @elseif($thread->inquiry_type == 'application') bg-green-200 text-green-800
                            @elseif($thread->inquiry_type == 'interview') text-yellow-600 bg-black
                            @elseif($thread->inquiry_type == 'technical') bg-red-200 text-red-800
                            @else text-yellow-600 bg-black @endif">
                            {{ ucfirst($thread->inquiry_type) }}
                        </span>
                    </div>
                @endforeach
                {{ $threadPaginator->links() }}
            @else
                <p class="text-gray-600 text-center">No message threads found.</p>
            @endif
        </div>

        <div class="w-full md:w-2/3 bg-white shadow-md rounded-lg p-4 border-2 border-black border-solid">
            @if ($selectedThread && $selectedMessage)
            <div class="flex justify-between mb-4">
                <h2 class="font-semibold text-gray-800"><span class="text-xl underline">Subject:</span> <span
                        class="text-lg">{{ $selectedThread->title }}</span></h2>
                <div>
                    @if ($this->canReply())
                        <button wire:click="toggleReplyModal" wire:loading.attr="disabled"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded mr-2">
                            <span wire:loading.remove wire:target="toggleReplyModal">
                                <i class="fa-solid fa-reply"></i> Reply
                            </span>
                            <span wire:loading wire:target="toggleReplyModal">
                                <i class="fa fa-spinner fa-spin"></i> Loading...
                            </span>
                        </button>
                    @endif
                    <button wire:click="deleteThread" wire:loading.attr="disabled"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">
                        <span wire:loading.remove wire:target="deleteThread">
                            <i class="fa-regular fa-trash-can"></i> Delete
                        </span>
                        <span wire:loading wire:target="deleteThread">
                            <i class="fa fa-spinner fa-spin"></i> Loading...
                        </span>
                    </button>
                </div>
            </div>

                <div class="flex flex-col space-y-4">
                    @foreach ($selectedThread->messages as $message)
                        <div class="flex items-start space-x-4">
                            @if ($message->sender->user_type === 'CompanyRepresentative')
                                <img src="{{ $message->sender->CompanyRepresentative->Company->image ? Storage::url($message->sender->CompanyRepresentative->Company->image) : asset('placeholder.png') }}"
                                    alt="Profile Picture" class="bg-gray-100 rounded-full w-[40px] h-[40px]">
                            @elseif ($message->sender->user_type === 'CompanyAdmin')
                                <img src="{{ $message->sender->CompanyAdmin->Company->image ? Storage::url($message->sender->CompanyAdmin->Company->image) : asset('placeholder.png') }}"
                                    alt="Profile Picture" class="bg-gray-100 rounded-full w-[40px] h-[40px]">
                            @else
                            <img src="{{ $message->sender->image ? Storage::url($message->sender->image) : asset('placeholder.png') }}"
                    alt="Profile Picture" class="bg-gray-100 rounded-full w-[40px] h-[40px]">
                                {{-- <i class="fa-solid fa-circle-user fa-2x text-gray-500 flex-shrink-0"></i> --}}
                            @endif
                            <div class="flex flex-col w-full">
                                <span class="text-sm font-semibold">From: {{ $message->sender->name }}</span>
                                <span class="text-xs text-gray-600">To: {{ $message->receiver->name }}</span>
                                <p class="text-sm text-gray-600">
                                    {{ $message->sent_at->format('M d, Y H:i') }}
                                </p>
                                <p class="text-sm text-gray-600">Inquiry Type:
                                    {{ ucfirst($selectedThread->inquiry_type) }}</p>
                                @if ($messageType === 'sent' && $message->read_at)
                                    <p class="text-sm text-gray-500">Seen at:
                                        {{ $message->read_at->format('M d, Y H:i') }}</p>
                                @elseif ($messageType === 'sent' && !$message->read_at)
                                    <p class="text-sm text-gray-500">Read: Not seen yet!</p>
                                @endif
                                <div class="mt-4 w-full overflow-x-auto">
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap break-words max-w-full"
                                        style="word-break: break-all;">{{ $message->content }}</p>
                                </div>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <hr class="border-gray-300">
                        @endif
                    @endforeach
                </div>


                <x-popup wire:model="showReplyModal">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-8">{{ $selectedThread->title }}</h2>
                        <div class="pb-4 mb-4">
                            <div class="mb-2 border border-1 border-black border-solid p-4 rounded">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $selectedMessage->content }}
                                </p>
                            </div>
                        </div>
                        <h6 class="text-center mb-2">Please fill out the inquiry form and send it to us.</h6>
                        <form wire:submit.prevent="sendReply">
                            <div class="bg-white border border-1 border-black border-solid mb-8">
                                <div class="p-1 border-b border-1 border-black border-solid">
                                    <div class="md:w-1/3">
                                        <select wire:model="replyInquiryType" id="replyInquiryType"
                                            class="mt-1 block w-full py-1 px-3 border border-gray-100 font-semibold bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Select inquiry type</option>
                                            <option value="general">General</option>
                                            <option value="interview">Interview</option>
                                            <option value="application">Application</option>
                                            <option value="technical">Technical</option>
                                        </select>
                                        @error('replyInquiryType')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <textarea wire:model="replyContent" id="replyContent"
                                        class="w-full p-4 border border-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"
                                        placeholder="Write your reply here..."></textarea>
                                    @error('replyContent')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" wire:click="toggleReplyModal" wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mr-2">
                                    <span wire:loading.remove wire:target="toggleReplyModal">Cancel</span>
                                    <span wire:loading wire:target="toggleReplyModal">
                                        <i class="fa fa-spinner fa-spin"></i> Loading...
                                    </span>
                                </button>
                                <button type="submit" wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    <span wire:loading.remove wire:target="sendReply">Send</span>
                                    <span wire:loading wire:target="sendReply">
                                        <i class="fa fa-spinner fa-spin"></i> Loading...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </x-popup>
            @elseif($threadPaginator->isNotEmpty())
                <p class="text-gray-600">Select a message thread to view.</p>
            @endif
        </div>
    </div>

    <x-popup wire:model="showNewMessageModal">
        <div class="p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">New Message</h3>
            <form wire:submit.prevent="sendNewMessage" class="mt-2">
                <div class="mb-4">
                    <label for="newMessageInquiryType" class="block text-sm font-medium text-gray-700">Inquiry
                        Type</label>
                    <select wire:model.live="selectedInquiryType" id="newMessageInquiryType"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select inquiry type</option>
                        <option value="general">General</option>
                        <option value="application">Application</option>
                        <option value="interview">Interview</option>
                        <option value="technical">Technical</option>
                    </select>
                    @error('selectedInquiryType')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @if ($selectedInquiryType && $userType != 'BusinessOperator')
                    <div class="mb-4">
                        <label for="newMessageReceiver" class="block text-sm font-medium text-gray-700">To
                            (Optional)</label>
                        <select wire:model="newMessageReceiver" id="newMessageReceiver"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select a recipient</option>
                            @foreach ($businessOperators as $businessOperator)
                                <option value="{{ $businessOperator['email'] }}">
                                    {{ $businessOperator['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('newMessageReceiver')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                @elseif ($userType == 'BusinessOperator')
                    <div class="mb-4">
                        <label for="newMessageReceiver" class="block text-sm font-medium text-gray-700">To
                            (Email)</label>
                        <input wire:model="newMessageReceiver" type="email" id="newMessageReceiver"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                @endif
                <div class="mb-4">
                    <label for="newMessageTitle" class="block text-sm font-medium text-gray-700">Subject</label>
                    <input wire:model="newMessageTitle" type="text" id="newMessageTitle"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('newMessageTitle')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="newMessageContent" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea wire:model="newMessageContent" id="newMessageContent" rows="4"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    @error('newMessageContent')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-between">
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        <span wire:loading.remove wire:target="sendNewMessage">Send
                            Message</span>
                        <span wire:loading wire:target="sendNewMessage">
                            <i class="fa fa-spinner fa-spin"></i> Sending...
                        </span>
                    </button>
                    <button type="button" wire:click="toggleNewMessageModal" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        <span wire:loading.remove wire:target="toggleNewMessageModal">Cancel</span>
                        <span wire:loading wire:target="toggleNewMessageModal">
                            <i class="fa fa-spinner fa-spin"></i> Loading...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </x-popup>
    <div class="text-center mt-6 sm:mt-12 w-full max-w-[506px] mx-auto px-2 sm:px-0">
        <a href="{{ url('/') }}"
            class="px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-white rounded-md border border-black w-full sm:w-auto">
            Return to TOP
        </a>
    </div>
</div>
