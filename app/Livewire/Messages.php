<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MessageThread;
use App\Models\Message;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessOperator;

class Messages extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedThread = null;
    public $selectedMessage = null;
    public $replyContent = '';
    public $replyInquiryType = '';
    public $newMessageReceiver = '';
    public $newMessageTitle = '';
    public $newMessageContent = '';
    public $newMessageInquiryType = '';
    public $showReplyModal = false;
    public $showNewMessageModal = false;
    public $messageType = 'inbox'; // 'inbox' or 'sent'
    public $userType;
    public $businessOperators = [];
    public $selectedInquiryType = '';

    protected $queryString = ['search', 'messageType'];

    public function mount()
    {
        $this->userType = Auth::user()->user_type;
        $this->businessOperators = $this->getBusinessOperators();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $threadPaginator = $this->getThreads();

        return view('livewire.messages', [
            'threadPaginator' => $threadPaginator,
        ])->layout('layouts.app');
    }

    public function getThreads()
    {
        $query = MessageThread::whereHas('messages', function ($query) {
            $query->where($this->messageType === 'inbox' ? 'receiver_user_id' : 'sender_user_id', Auth::id());
        })->with(['latestMessage', 'latestMessage.sender', 'latestMessage.receiver']);

        if ($this->userType === 'BusinessOperator') {
            $query->orWhereHas('messages', function ($q) {
                $q->where('sender_user_type', 'BusinessOperator')
                  ->orWhere('receiver_user_type', 'BusinessOperator');
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('messages', function ($subQ) {
                      $subQ->where('content', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return $query->orderBy('updated_at', 'desc')->paginate(5);
    }

    public function selectThread($id)
    {
        $this->selectedThread = MessageThread::with(['messages' => function ($query) {
            $query->orderBy('sent_at', 'desc');
        }, 'messages.sender', 'messages.receiver'])->findOrFail($id);

        $this->selectedMessage = $this->selectedThread->messages->first();

        if ($this->messageType === 'inbox') {
            $this->selectedThread->messages()
                ->where('receiver_user_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            $this->dispatch('messageRead');
        }
    }

    public function toggleReplyModal()
    {
        $this->showReplyModal = !$this->showReplyModal;
        $this->replyContent = '';
        $this->replyInquiryType = $this->selectedThread->inquiry_type;

        $this->dispatch('popup-opened');
    }

    public function sendReply()
    {
        $validator = Validator::make(
            [
                'replyContent' => $this->replyContent,
                'replyInquiryType' => $this->replyInquiryType,
            ],
            [
                'replyContent' => 'required|min:10',
                'replyInquiryType' => 'required',
            ]
        );

        if ($validator->fails()) {
            $this->addError('replyContent', $validator->errors()->first('replyContent'));
            $this->addError('replyInquiryType', $validator->errors()->first('replyInquiryType'));
            return;
        }

        $newMessage = $this->selectedThread->messages()->create([
            'sender_user_id' => Auth::id(),
            'sender_user_type' => Auth::user()->user_type,
            'receiver_user_id' => $this->selectedMessage->sender_user_id,
            'receiver_user_type' => $this->selectedMessage->sender_user_type,
            'sent_at' => now(),
            'content' => $this->replyContent,
            'message_category' => 'Sent',
        ]);

        $this->selectedThread->update([
            'inquiry_type' => $this->replyInquiryType,
        ]);

        $this->showReplyModal = false;
        $this->replyContent = '';
        $this->replyInquiryType = '';
        $this->selectThread($this->selectedThread->id);

        flash()->success('Reply sent successfully!');
    }

    public function canReply()
    {
        if (!$this->selectedMessage) {
            return false;
        }

        $currentUserType = Auth::user()->user_type;
        $otherUserType = $this->messageType === 'inbox'
            ? $this->selectedMessage->sender_user_type
            : $this->selectedMessage->receiver_user_type;

        $companyUsers = ['CompanyAdmin', 'CompanyRepresentative'];
        $studentCandidateUsers = ['Student', 'Candidate'];

        if (in_array($currentUserType, $companyUsers) && in_array($otherUserType, $studentCandidateUsers)) {
            return false;
        }

        if (in_array($currentUserType, $studentCandidateUsers) && in_array($otherUserType, $companyUsers)) {
            return false;
        }

        return true;
    }

    public function toggleNewMessageModal()
    {
        $this->showNewMessageModal = !$this->showNewMessageModal;
        $this->resetNewMessageForm();

        $this->dispatch('popup-opened');
    }

    public function sendNewMessage()
    {
        $validator = Validator::make(
            [
                'newMessageTitle' => $this->newMessageTitle,
                'newMessageContent' => $this->newMessageContent,
                'selectedInquiryType' => $this->selectedInquiryType,
            ],
            [
                'newMessageTitle' => 'required|min:3',
                'newMessageContent' => 'required|min:10',
                'selectedInquiryType' => 'required',
            ]
        );

        if ($validator->fails()) {
            $this->addError('newMessageTitle', $validator->errors()->first('newMessageTitle'));
            $this->addError('newMessageContent', $validator->errors()->first('newMessageContent'));
            $this->addError('selectedInquiryType', $validator->errors()->first('selectedInquiryType'));
            return;
        }

        if (empty($this->newMessageReceiver)) {
            $receiver = BusinessOperator::first()->user;
        } else {
            $receiver = User::where('email', $this->newMessageReceiver)->firstOrFail();
        }

        $thread = MessageThread::create([
            'title' => $this->newMessageTitle,
            'inquiry_type' => $this->selectedInquiryType,
        ]);

        $thread->messages()->create([
            'sender_user_id' => Auth::id(),
            'sender_user_type' => Auth::user()->user_type,
            'receiver_user_id' => $receiver->id,
            'receiver_user_type' => $receiver->user_type,
            'sent_at' => now(),
            'content' => $this->newMessageContent,
            'message_category' => 'Sent',
        ]);

        $this->showNewMessageModal = false;
        $this->resetNewMessageForm();

        flash()->success('Message sent successfully!');
    }

    public function resetNewMessageForm()
    {
        $this->newMessageReceiver = '';
        $this->newMessageTitle = '';
        $this->newMessageContent = '';
        $this->selectedInquiryType = '';
    }

    public function deleteThread()
    {
        if ($this->selectedThread) {
            $this->selectedThread->delete();
            $this->selectedThread = null;
            $this->selectedMessage = null;

            flash()->success('Thread deleted successfully!');
        }
    }

    public function switchMessageType($type)
    {
        $this->messageType = $type;
        $this->selectedThread = null;
        $this->selectedMessage = null;
        $this->resetPage();
    }

    public function updatedSelectedInquiryType()
    {
        $this->businessOperators = $this->getBusinessOperators();
    }

    private function getBusinessOperators()
    {
        $query = BusinessOperator::query()->with('user');

        if ($this->selectedInquiryType) {
            $query->where('tag', $this->selectedInquiryType);
        }

        return $query->get()->map(function ($businessOperator) {
            return [
                'name' => $businessOperator->user->name,
                'email' => $businessOperator->user->email,
            ];
        })->toArray();
    }
}
