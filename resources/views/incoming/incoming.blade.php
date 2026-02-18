@extends('layouts.app')

@section('header')

<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Incoming Documents
                </h1>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')

<!-- Error Messages -->
@if ($errors->any())
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if(auth()->user()->isAdmin())
    <div class="max-w-7xl mx-auto px-6 pb-2">
        <form method="GET" action="{{ route('incoming.index') }}" class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <div class="md:flex-1">
                    <select name="unit_id" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">All units</option>
                        @foreach($filterUnits as $unit)
                            <option value="{{ $unit->id }}" {{ (string) $selectedUnitId === (string) $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 md:justify-end md:ml-auto">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                        Apply
                    </button>
                    <a href="{{ route('incoming.index', ['unit_id' => '']) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
@endif

<!-- CENTER WRAPPER -->
<div class="flex justify-center w-full py-6">

    <!-- CARD -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 w-full max-w-7xl">

        <!-- Table Wrapper -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border-collapse">

                <!-- Table Head -->
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">#</th>
                        <th class="px-6 py-4 text-left">Document No.</th>
                        <th class="px-6 py-4 text-center">Document Title</th>
                        <th class="px-6 py-4 text-center">Sender Unit</th>
                        <th class="px-6 py-4 text-center">Type</th>
                        <th class="px-6 py-4 text-center">Receiving Unit</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Date</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody x-data="{ 
                    showConfirmReceive: false, 
                    showSuccessReceive: false,
                    showSuccessReject: false,
                    selectedDocId: null,
                    selectedDocNumber: '',
                    openRejectModal: false,
                    rejectionDocId: null,
                    rejectionDocNumber: '',
                    confirmReceive(docId, docNumber) {
                        this.selectedDocId = docId;
                        this.selectedDocNumber = docNumber;
                        this.showConfirmReceive = true;
                    },
                    openReject(docId, docNumber) {
                        this.rejectionDocId = docId;
                        this.rejectionDocNumber = docNumber;
                        this.openRejectModal = true;
                    },
                    submitReceive() {
                        this.showConfirmReceive = false;
                        this.showSuccessReceive = true;
                        setTimeout(() => {
                            const form = document.getElementById('receive-form-' + this.selectedDocId);
                            const formData = new FormData(form);
                            
                            fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                }
                            }).then(response => {
                                window.location.reload();
                            });
                        }, 1500);
                    },
                    submitReject(event) {
                        this.openRejectModal = false;
                        this.showSuccessReject = true;
                        setTimeout(() => {
                            event.target.submit();
                        }, 1500);
                    }
                }">
                    @forelse ($documents as $document)
                    <tr class="hover:bg-gray-50 transition border-b border-gray-100">

                        <td class="px-6 py-4 font-medium text-gray-800 text-left">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-900 text-left">
                            {{ $document->document_number }}
                        </td>

                        <!-- Title -->
                        <td class="px-6 py-4 text-center">
                            {{ $document->title }}
                        </td>

                        <!-- Sender Unit -->
                        <td class="px-6 py-4 text-center">
                            {{ $document->senderUnit->name ?? '-' }}
                        </td>

                        <!-- Type -->
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                {{ $document->document_type }}
                            </span>
                        </td>

                        <!-- Receiving Unit -->
                        <td class="px-6 py-4 text-center">
                            {{ $document->receivingUnit->name ?? '-' }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 text-center">
                            @if($document->status == 'incoming')
                                <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-semibold">
                                    Pending
                                </span>
                            @elseif($document->status == 'received')
                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                    Received
                                </span>
                            @elseif($document->status == 'rejected')
                                <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold">
                                    Rejected
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-50 text-gray-700 text-xs font-semibold">
                                    {{ ucfirst($document->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-gray-600 text-center">
                            {{ $document->created_at->format('M d, Y h:i A') }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; max-width: 200px; margin: 0 auto;">
                                
                                <!-- View Button -->
                                <a href="{{ route('documents.view', ['id' => $document->id]) }}"
                                   class="px-3 py-1.5 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition text-xs font-semibold text-center whitespace-nowrap">
                                    Details
                                </a>

                                <!-- Download Button -->
                                @if ($document->file_path)
                                    <a href="{{ route('documents.download', ['id' => $document->id]) }}"
                                       class="px-3 py-1.5 rounded-md bg-green-100 text-green-700 hover:bg-green-200 transition text-xs font-semibold text-center whitespace-nowrap">
                                        Download
                                    </a>
                                @else
                                    <div class="px-3 py-1.5 rounded-md bg-gray-50 text-gray-400 text-xs font-semibold text-center cursor-not-allowed whitespace-nowrap">
                                        No File
                                    </div>
                                @endif

                                <!-- Receive Button - ONLY if user can receive -->
                                @if($document->receiving_unit_id == auth()->user()->unit_id && $document->status == 'incoming')
                                    <!-- Hidden form for receiving -->
                                    <form id="receive-form-{{ $document->id }}" 
                                          action="{{ route('documents.receive', ['id' => $document->id]) }}" 
                                          method="POST" 
                                          class="hidden">
                                        @csrf
                                    </form>

                                    <button
                                        type="button"
                                        @click="confirmReceive({{ $document->id }}, '{{ $document->document_number }}')"
                                        class="px-3 py-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition text-xs font-semibold whitespace-nowrap">
                                        Receive
                                    </button>

                                    <!-- Reject Button -->
                                    <button
                                        type="button"
                                        @click="openReject({{ $document->id }}, '{{ $document->document_number }}')"
                                        class="px-3 py-1.5 rounded-md bg-red-600 text-white hover:bg-red-700 transition text-xs font-semibold whitespace-nowrap">
                                        Reject
                                    </button>
                                @endif

                            </div>
                        </td>      
                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-lg">📄</span>
                                    <span>No incoming documents found</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <!-- RECEIVE CONFIRMATION MODAL -->
                    <tr x-show="showConfirmReceive" x-cloak style="display: none;">
                        <td colspan="9">
                            <div class="fixed inset-0 z-50 flex items-center justify-center" 
                                 style="background-color: rgba(11, 31, 58, 0.75); backdrop-filter: blur(8px);"
                                 @click.self="showConfirmReceive = false"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100">
                                
                                <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4"
                                     @click.stop
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                                    
                                    <!-- Header with gradient -->
                                    <div class="px-6 py-4 rounded-t-2xl border-b" style="background: linear-gradient(to bottom right, #d1fae5, #a7f3d0); border-color: #86efac;">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #d1fae5;">
                                                    <svg class="w-5 h-5" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-base font-bold" style="color: #111827;">Receive Document</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Body -->
                                    <div class="px-6 py-4">
                                        <p class="text-sm" style="color: #374151;">
                                            Receive <span class="font-semibold px-1.5 py-0.5 rounded" style="color: #111827; background-color: #f3f4f6;" x-text="selectedDocNumber"></span>?
                                        </p>
                                    </div>

                                    <!-- Footer -->
                                    <div class="px-6 py-3 rounded-b-2xl flex justify-end gap-2.5" style="background-color: #f9fafb;">
                                        <button @click="showConfirmReceive = false"
                                                type="button"
                                                class="px-4 py-2 rounded-lg transition-all font-medium text-sm"
                                                style="background-color: white; border: 2px solid #d1d5db; color: #374151;"
                                                onmouseover="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#9ca3af';"
                                                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#d1d5db';">
                                            Cancel
                                        </button>
                                        <button @click="submitReceive()"
                                                type="button"
                                                class="px-4 py-2 rounded-lg transition-all font-medium text-sm shadow-md"
                                                style="background-color: #059669; color: white;"
                                                onmouseover="this.style.backgroundColor='#047857';"
                                                onmouseout="this.style.backgroundColor='#059669';">
                                            Confirm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- SUCCESS RECEIVE MODAL -->
                    <tr x-show="showSuccessReceive" x-cloak style="display: none;">
                        <td colspan="9">
                            <div class="fixed inset-0 z-50 flex items-center justify-center" 
                                 style="background-color: rgba(11, 31, 58, 0.6); backdrop-filter: blur(4px);"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100">
                                
                                <div class="rounded-3xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center" style="background-color: white;"
                                     x-transition:enter="transition ease-out duration-300 delay-75"
                                     x-transition:enter-start="opacity-0 scale-75"
                                     x-transition:enter-end="opacity-100 scale-100">
                                    
                                    <!-- Animated checkmark -->
                                    <div class="mb-6">
                                        <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center shadow-lg animate-bounce-in" 
                                             style="background: linear-gradient(to bottom right, #34d399, #10b981);">
                                            <svg class="w-10 h-10" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Success message -->
                                    <h3 class="text-2xl font-bold mb-2" style="color: #111827;">Received!</h3>
                                    <p class="text-sm" style="color: #6b7280;">
                                        Document has been marked as received
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- SUCCESS REJECT MODAL -->
                    <tr x-show="showSuccessReject" x-cloak style="display: none;">
                        <td colspan="9">
                            <div class="fixed inset-0 z-50 flex items-center justify-center" 
                                 style="background-color: rgba(11, 31, 58, 0.6); backdrop-filter: blur(4px);"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100">
                                
                                <div class="rounded-3xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center" style="background-color: white;"
                                     x-transition:enter="transition ease-out duration-300 delay-75"
                                     x-transition:enter-start="opacity-0 scale-75"
                                     x-transition:enter-end="opacity-100 scale-100">
                                    
                                    <!-- Animated X mark -->
                                    <div class="mb-6">
                                        <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center shadow-lg animate-bounce-in" 
                                             style="background: linear-gradient(to bottom right, #f87171, #ef4444);">
                                            <svg class="w-10 h-10" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Success message -->
                                    <h3 class="text-2xl font-bold mb-2" style="color: #111827;">Rejected</h3>
                                    <p class="text-sm" style="color: #6b7280;">
                                        Document has been rejected
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- REJECTION MODAL -->
                    <tr x-show="openRejectModal" x-cloak style="display: none;">
                        <td colspan="9">
                            <div class="fixed inset-0 z-[10000] flex items-center justify-center p-4"
                                 style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);"
                                 @click.self="openRejectModal = false"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100">
                                
                                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden"
                                     style="width: 500px; max-height: 90vh;"
                                     @click.stop
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-90"
                                     x-transition:enter-end="opacity-100 scale-100">
                                    
                                    <!-- Header -->
                                    <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-red-50 to-white">
                                        <div>
                                            <h2 class="text-lg font-semibold text-gray-800">Reject Document</h2>
                                            <p class="text-sm text-gray-600 mt-0.5" x-text="rejectionDocNumber"></p>
                                        </div>

                                        <button
                                            @click="openRejectModal = false"
                                            type="button"
                                            class="w-9 h-9 flex items-center justify-center rounded-full 
                                                   hover:bg-gray-200 text-gray-500 hover:text-gray-700 transition duration-200"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Form -->
                                    <form 
                                        :action="'/documents/' + rejectionDocId + '/reject'" 
                                        method="POST"
                                        class="px-6 py-4"
                                        @submit.prevent="submitReject($event)"
                                    >
                                        @csrf

                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Reason for Rejection <span class="text-red-500">*</span>
                                            </label>
                                            <textarea
                                                name="rejection_reason"
                                                required
                                                rows="5"
                                                placeholder="Please provide a detailed reason for rejecting this document..."
                                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                                                       focus:ring-2 focus:ring-red-500 focus:border-red-500 
                                                       outline-none text-sm transition duration-200
                                                       hover:border-gray-400 resize-none"
                                            ></textarea>
                                            <p class="text-xs text-gray-500 mt-2">This reason will be visible to the sender.</p>
                                        </div>

                                        <!-- Warning -->
                                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex gap-2">
                                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-semibold text-red-800">Are you sure?</p>
                                                    <p class="text-xs text-red-700 mt-0.5">This action cannot be undone. The document will be marked as rejected.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div class="flex justify-end gap-3 pt-4 border-t">
                                            <button
                                                type="button"
                                                @click="openRejectModal = false"
                                                class="px-6 py-2.5 rounded-lg border-2 border-gray-300
                                                       text-gray-700 hover:bg-gray-50 transition duration-200 
                                                       font-semibold text-sm"
                                            >
                                                Cancel
                                            </button>

                                            <button
                                                type="submit"
                                                class="px-6 py-2.5 rounded-lg bg-red-600 text-white font-semibold text-sm
                                                       hover:bg-red-700 shadow-lg hover:shadow-xl transition duration-200
                                                       transform hover:-translate-y-0.5"
                                            >
                                                Reject Document
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- FLOATING UPLOAD BUTTON + MODAL -->
<div x-data="{ 
    open: false, 
    documentNumber: '',
    loading: false,
    error: '',
    showSuccessUpload: false,
    async openModal() {
        console.log('Modal opening...');
        this.open = true;
        this.loading = true;
        this.error = '';
        this.documentNumber = 'Loading...';
        await this.fetchDocumentNumber();
    },
    async fetchDocumentNumber() {
        console.log('Starting fetch...');
        
        try {
            const url = '{{ route("documents.next-number") }}';
            console.log('Fetching from URL:', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            
            console.log('Response received:', response);
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error response:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Data received:', data);
            
            this.documentNumber = data.document_number;
            this.loading = false;
            console.log('Document number set to:', this.documentNumber);
            
        } catch (error) {
            console.error('Fetch error:', error);
            this.documentNumber = 'Error: ' + error.message;
            this.error = error.message;
            this.loading = false;
        }
    },
    submitUpload(event) {
        event.preventDefault();
        this.open = false;
        this.showSuccessUpload = true;
        setTimeout(() => {
            event.target.submit();
        }, 1500);
    }
}" x-init="console.log('Alpine component initialized')">

    <!-- FLOATING BUTTON -->
    <button
        @click="openModal()"
        type="button"
        style="
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            background-color: #0B1F3A;
            color: #ffffff;
            padding: 14px 22px;
            border-radius: 9999px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
            font-weight: 600;
        "
    >
        ＋ Document
    </button>

    <!-- MODAL BACKDROP + MODAL (CENTERED) -->
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 z-[10000] flex items-center justify-center p-4"
        style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);"
    >

        <!-- MODAL CARD (PERFECTLY CENTERED - SQUARE) -->
        <div
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="bg-white rounded-5xl shadow-2xl overflow-y-auto"
            style="width: 500px; max-height: 90vh; border-radius: 2rem;"
        >

            <!-- HEADER -->
            <div class="flex items-center justify-between px-2 py-2 border-b bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-l font-semibold text-gray-800 mb-0.5 px-4">
                    Upload New Document
                </h2>

                <button
                    @click="open = false"
                    type="button"
                    class="w-9 h-9 flex items-center justify-center rounded-full 
                           hover:bg-gray-200 text-gray-500 hover:text-gray-700 transition duration-200 mb-0.5 px-4"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- FORM -->
            <form
                action="{{ route('documents.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="px-6 py-4 space-y-2.5"
                @submit="submitUpload($event)"
            >
            @csrf

            <!-- Document Number (Auto-generated, Read-only) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Document Number <span class="text-red-500">*</span>
                </label>
                <input
                    name="document_number"
                    x-model="documentNumber"
                    required
                    readonly
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                           bg-gray-50 text-gray-600 cursor-not-allowed
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           outline-none text-sm transition duration-200"
                    :placeholder="loading ? 'Loading...' : 'Auto-generated'"
                >
                <p class="text-xs text-gray-500 mt-1">Auto-generated document number</p>
                <p x-show="error" class="text-xs text-red-500 mt-1" x-text="'Error: ' + error"></p>
            </div>

            <!-- Document Title -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Document Title <span class="text-red-500">*</span>
                </label>
                <input
                    name="title"
                    required
                    placeholder="Enter descriptive title"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           outline-none text-sm transition duration-200
                           hover:border-gray-400"
                >
            </div>

            <!-- Receiving Unit -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Receiving Unit <span class="text-red-500">*</span>
                </label>
                <select
                    name="receiving_unit_id"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                           bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           outline-none text-sm transition duration-200
                           hover:border-gray-400"
                >
                    <option value="">Select Receiving Unit</option>
                    @foreach($units as $unit)
                        @if($unit->id != auth()->user()->unit_id)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endif
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">You cannot send to your own unit</p>
            </div>

            <!-- Document Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Document Type <span class="text-red-500">*</span>
                </label>
                <select
                    name="document_type"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                           bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           outline-none text-sm transition duration-200
                           hover:border-gray-400"
                >
                    <option value="">Select document type</option>
                    <option>Birth Certificate</option>
                    <option>Marriage Certificate</option>
                    <option>Clearance</option>
                    <option>Memorandum</option>
                    <option>Letter</option>
                    <option>Report</option>
                    <option>Others</option>
                </select>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Attach File <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <input
                        type="file"
                        name="file"
                        required
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="block w-full text-sm text-gray-600
                               file:mr-4 file:rounded-lg file:border-0
                               file:bg-blue-50 file:text-blue-700
                               file:px-4 file:py-2 file:text-sm file:font-semibold
                               hover:file:bg-blue-100 transition duration-200
                               border border-gray-300 rounded-lg
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               cursor-pointer"
                    >
                </div>
                <p class="text-xs text-gray-500 mt-1">Accepted: PDF, DOC, DOCX, JPG, PNG (Max: 25MB)</p>
            </div>

            <!-- FOOTER -->
            <div class="flex justify-end gap-3 pt-4 border-t">

                <button
                    type="button"
                    @click="open = false"
                    class="px-6 py-2.5 rounded-lg border-2 border-gray-300
                           text-gray-700 hover:bg-gray-50 transition duration-200 
                           font-semibold text-sm"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-6 py-2.5 rounded-lg text-white font-semibold text-sm
                           shadow-lg hover:shadow-xl transition duration-200
                           transform hover:-translate-y-0.5"
                    style="background-color:#0B1F3A;"
                >
                    Upload
                </button>

            </div>
            </form>

        </div>
    </div>

    <!-- SUCCESS UPLOAD MODAL -->
    <div x-show="showSuccessUpload" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center" 
         style="background-color: rgba(11, 31, 58, 0.6); backdrop-filter: blur(4px);"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="rounded-3xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center" 
             style="background-color: white;"
             x-transition:enter="transition ease-out duration-300 delay-75"
             x-transition:enter-start="opacity-0 scale-75"
             x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Animated checkmark -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center shadow-lg animate-bounce-in" 
                     style="background: linear-gradient(to bottom right, #60a5fa, #3b82f6);">
                    <svg class="w-10 h-10" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Success message -->
            <h3 class="text-2xl font-bold mb-2" style="color: #111827;">Uploaded!</h3>
            <p class="text-sm" style="color: #6b7280;">
                Document has been uploaded successfully
            </p>
        </div>
    </div>

</div>

<!-- Add this to your CSS or in a style tag -->
<style>
    [x-cloak] { 
        display: none !important; 
    }

    @keyframes bounce-in {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-bounce-in {
        animation: bounce-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
</style>

@endsection
