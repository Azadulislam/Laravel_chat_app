@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-3">
        <div x-data='projectPreview(@json($approvedComments))' x-init="loadPage()">
            <!-- Mode Toggle (outside preview container) -->
            <div class="mb-4 flex gap-2">
                <button 
                    @click="mode = 'interact'"
                    :class="mode === 'interact' ? 'bg-blue-600' : 'bg-gray-300'"
                    class="px-4 py-2 rounded text-white font-medium shadow"
                >
                    🖱️ Interact Mode
                </button>
                <button 
                    @click="mode = 'comment'"
                    :class="mode === 'comment' ? 'bg-red-600' : 'bg-gray-300'"
                    class="px-4 py-2 rounded text-white font-medium shadow"
                >
                    💬 Comment Mode
                </button>
            </div>
            
            <div class="relative" id="previewContainer" style="min-height: 800px;">
                <!-- Iframe to load the proxied page -->
                <iframe 
                    id="pageIframe" 
                    src=""
                    class="w-full border-0"
                    style="min-height: 800px;"
                ></iframe>
                
                <!-- Overlay (only visible in comment mode) -->
                <div 
                    id="overlay" 
                    x-show="mode === 'comment'"
                    @click="clickOverlay($event)" 
                    class="absolute inset-0 cursor-crosshair"
                    style="background: rgba(255, 0, 0, 0.05); pointer-events: auto;"
                ></div>

                <!-- pins -->
                <template x-for="pin in pins" :key="pin.id">
                    <div :style="`position:absolute; left:${pin.xPercent}%; top:${pin.yPercent}%;`" class="z-40">
                        <button @click.stop="selectPin(pin)" :style="`transform:translate(-50%,-100%)`" class="bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs hover:bg-red-700 transition-colors">!</button>
                        <div x-show="activePin && activePin.id === pin.id" x-cloak :style="`transform:translate(8px,-50%);`" class="absolute z-50 min-w-[280px]">
                            <div class="bg-white rounded-sm border border-gray-200 p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="font-semibold text-gray-800" x-text="pin.user"></div>
                                    <button @click="activePin = null" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="text-gray-700 mb-4" x-text="pin.text"></div>
                                <button @click="openReplyModal(pin)" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    ↩️ Reply
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Comment/Reply Modal -->
            <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-[100]" @click.self="closeModal()">
                <div class="bg-white rounded-sm shadow-2xl w-full max-w-md mx-4 p-6">
                    <h3 class="text-lg font-semibold mb-4" x-text="isReply ? 'Reply to Comment' : 'Add Comment'"></h3>
                    <textarea 
                        x-model="commentText" 
                        class="w-full border rounded-lg p-3 mb-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        rows="4" 
                        placeholder="Enter your comment..."
                    ></textarea>
                    <div class="flex gap-3 justify-end">
                        <button @click="closeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                        <button @click="submitComment()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function projectPreview(initialPins){
    return {
        mode: 'interact',
        pins: (initialPins || []).map(function(p){ return { id: p.id, xPercent: (p.x || 0) * 100, yPercent: (p.y || 0) * 100, text: p.text, user: (p.user && (p.user.username || p.user.name)) } }),
        activePin: null,
        showModal: false,
        isReply: false,
        commentText: '',
        pendingX: 0,
        pendingY: 0,
        replyToPin: null,
        
        selectPin(pin){ 
            if (this.activePin && this.activePin.id === pin.id) {
                this.activePin = null;
            } else {
                this.activePin = pin;
            }
        },
        
        openReplyModal(pin) {
            this.isReply = true;
            this.replyToPin = pin;
            this.commentText = '';
            this.showModal = true;
        },
        
        clickOverlay(e){
            const overlay = e.currentTarget;
            const rect = overlay.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const xPercent = (x / rect.width) * 100;
            const yPercent = (y / rect.height) * 100;
            
            this.pendingX = xPercent / 100;
            this.pendingY = yPercent / 100;
            this.isReply = false;
            this.replyToPin = null;
            this.commentText = '';
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.commentText = '';
            this.isReply = false;
            this.replyToPin = null;
        },
        
        async submitComment() {
            if (!this.commentText.trim()) return;
            
            const data = {
                text: this.commentText,
                x: this.isReply ? this.replyToPin.xPercent / 100 : this.pendingX,
                y: this.isReply ? this.replyToPin.yPercent / 100 : this.pendingY,
            };
            
            if (this.isReply && this.replyToPin) {
                data.parent_id = this.replyToPin.id;
            }
            
            try {
                const response = await fetch('{{ route('projects.comments.store', $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });
                
                if (response.status === 401) {
                    window.dispatchEvent(new CustomEvent('open-login'));
                    return;
                }
                
                const result = await response.json();
                
                if (result && result.comment) {
                    if (result.comment.status === 'approved') {
                        const userName = result.comment.user && (result.comment.user.username || result.comment.user.name);
                        this.pins.push({ 
                            id: result.comment.id, 
                            xPercent: (result.comment.x || 0) * 100, 
                            yPercent: (result.comment.y || 0) * 100, 
                            text: result.comment.text, 
                            user: userName 
                        });
                    }
                    alert('Comment submitted and pending approval');
                    this.mode = 'interact';
                }
            } catch(err) {
                console.error(err);
            }
            
            this.closeModal();
        },
        
        async loadPage(){
            try {
                // Set iframe source directly to proxy route
                document.getElementById('pageIframe').src = '{{ route('projects.proxy', $project) }}';
            } catch(err) {
                console.error(err);
            }
        }
    }
}
</script>

@endsection
