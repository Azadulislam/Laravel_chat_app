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
            
            <div class="relative" id="previewContainer">
                <div id="pageContent" class="w-full"></div>
                
                <!-- Overlay (only visible in comment mode) -->
                <div 
                    id="overlay" 
                    x-show="mode === 'comment'"
                    @click="clickOverlay($event)" 
                    class="absolute inset-0 cursor-crosshair"
                    style="background: rgba(255, 0, 0, 0.05);"
                ></div>

                <!-- pins -->
                <template x-for="pin in pins" :key="pin.id">
                    <div :style="`position:absolute; left:${pin.xPercent}%; top:${pin.yPercent}%;`" class="z-40">
                        <button @click.stop="selectPin(pin)" :style="`transform:translate(-50%,-100%)`" class="bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs">!</button>
                        <div x-show="activePin && activePin.id === pin.id" x-cloak :style="`transform:translate(8px,-50%);`" class="absolute z-50">
                            <div class="bg-white p-2 rounded shadow max-w-xs">
                                <div class="text-sm font-medium" x-text="pin.user"></div>
                                <div class="text-sm" x-text="pin.text"></div>
                            </div>
                        </div>
                    </div>
                </template>
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
        selectPin(pin){ 
            if (this.activePin && this.activePin.id === pin.id) {
                this.activePin = null;
            } else {
                this.activePin = pin;
            }
        },
        async loadPage(){
            try {
                const response = await fetch('{{ route('projects.proxy', $project) }}');
                const html = await response.text();
                document.getElementById('pageContent').innerHTML = html;
            } catch(err) {
                console.error(err);
                document.getElementById('pageContent').innerHTML = '<p class="text-red-500">Failed to load page</p>';
            }
        },
        clickOverlay(e){
            const overlay = e.currentTarget;
            const rect = overlay.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const xPercent = (x / rect.width) * 100;
            const yPercent = (y / rect.height) * 100;

            const text = prompt('Enter comment text:');
            if(!text) return;

            fetch('{{ route('projects.comments.store', $project) }}', {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ text: text, x: xPercent/100, y: yPercent/100 })
            }).then(r=>{
                if(r.status===401){
                    window.dispatchEvent(new CustomEvent('open-login'));
                    return;
                }
                return r.json();
            }).then(data=>{
                if(data && data.comment){
                    // only show immediately if approved
                    if(data.comment.status === 'approved'){
                        var userName = data.comment.user && (data.comment.user.username || data.comment.user.name);
                        this.pins.push({ id: data.comment.id, xPercent: xPercent, yPercent: yPercent, text: data.comment.text, user: userName });
                    }
                    alert('Comment submitted and pending approval');
                    // Switch back to interact mode after adding comment
                    this.mode = 'interact';
                }
            }).catch(err=>console.error(err));
        }
    }
}
</script>

@endsection
