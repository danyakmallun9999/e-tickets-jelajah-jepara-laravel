{{--
Editor.js Component for Admin Forms
Usage: <x-admin.editorjs name="content" :value="$post->content" label="Konten" />

Wraps Editor.js in an Alpine.js component for seamless Laravel form integration.
Stores output as JSON in a hidden input.
--}}

@props([
    'name' => 'content',
    'value' => null,
    'label' => 'Konten',
    'formatName' => null,
])

@php
    $formatFieldName = $formatName ?? $name . '_format';
    $holderId = 'editorjs-' . $name . '-' . uniqid();
@endphp

<div x-data="{
        editor: null,
        editorData: {},
        holderId: '{{ $holderId }}',
        
        async init() {
            const { initEditorJs } = await window.loadEditorJs();
            
            const initialData = @js($value);
            let parsedData = {};
            
            if (initialData) {
                try {
                    parsedData = typeof initialData === 'string' ? JSON.parse(initialData) : initialData;
                } catch (e) {
                    parsedData = {};
                }
            }
            
            this.editorData = parsedData;
            
            this.editor = await initEditorJs(this.holderId, {
                data: parsedData,
                uploadUrl: '{{ route('admin.editor.upload') }}',
                csrfToken: '{{ csrf_token() }}',
                onChange: (data) => {
                    this.editorData = data;
                },
            });
        },
        
        async getContent() {
            if (this.editor) {
                const data = await this.editor.save();
                this.editorData = data;
                return JSON.stringify(data);
            }
            return JSON.stringify(this.editorData);
        },
        
        insertBlock(type, data = {}) {
            if (this.editor) {
                const currentIndex = this.editor.blocks.getCurrentBlockIndex();
                const insertIndex = currentIndex >= 0 ? currentIndex + 1 : this.editor.blocks.getBlocksCount();
                this.editor.blocks.insert(type, data, {}, insertIndex, true);
                
                // Set focus to the newly added block
                setTimeout(() => {
                    this.editor.caret.setToBlock(insertIndex, 'start');
                }, 50);
            }
        }
     }"
     x-on:submit.window="
        if ($el.closest('form')) {
            const data = await getContent();
            $refs.hiddenInput.value = data;
        }
     "
     class="space-y-2">
    
    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $label }}</label>
    
    {{-- Static Quick Insert Toolbar (Familiar UX for users) --}}
    <div class="flex flex-wrap items-center gap-2 p-2 bg-gray-50 border border-gray-200 rounded-lg mb-2">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mr-2 ml-1">Sisipkan:</span>
        
        <button type="button" @click="insertBlock('paragraph')" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-blue-600 transition-colors tooltip-btn" title="Paragraf Baru">
            <i class="fa-solid fa-paragraph"></i> 
        </button>
        <button type="button" @click="insertBlock('header', {level: 2})" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-blue-600 transition-colors tooltip-btn" title="Judul Utama (H2)">
            <strong>H2</strong>
        </button>
        <button type="button" @click="insertBlock('header', {level: 3})" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-blue-600 transition-colors tooltip-btn" title="Sub-Judul (H3)">
            <strong>H3</strong>
        </button>
        <div class="w-px h-5 bg-gray-300 mx-1"></div>
        <button type="button" @click="insertBlock('image')" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-blue-600 transition-colors tooltip-btn" title="Gambar">
            <i class="fa-regular fa-image"></i>
        </button>
        <button type="button" @click="insertBlock('table')" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-blue-600 transition-colors tooltip-btn" title="Tabel">
            <i class="fa-solid fa-table"></i>
        </button>
        <button type="button" @click="insertBlock('list')" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-blue-600 transition-colors tooltip-btn" title="Daftar Bullet">
            <i class="fa-solid fa-list-ul"></i>
        </button>
        <button type="button" @click="insertBlock('quote')" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-blue-600 transition-colors tooltip-btn" title="Kutipan">
            <i class="fa-solid fa-quote-right"></i>
        </button>
    </div>
    
    {{-- Editor Container --}}
    <div :id="holderId" 
         class="editorjs-container border border-gray-300 rounded-xl bg-white min-h-[400px] focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 shadow-inner transition-all">
    </div>
    
    {{-- Hidden inputs for form submission --}}
    <input type="hidden" 
           name="{{ $name }}" 
           x-ref="hiddenInput"
           :value="JSON.stringify(editorData)">
    <input type="hidden" name="{{ $formatFieldName }}" value="editorjs">
    
    <x-input-error :messages="$errors->get($name)" class="mt-1" />
</div>

@pushOnce('styles')
<style>
    /* Editor.js Container Styles */
    .editorjs-container .codex-editor {
        padding: 1.5rem 2.5rem; /* Increased padding to allow popups to overflow safely */
    }
    .editorjs-container .codex-editor__redactor {
        padding-bottom: 200px !important;
    }
    .editorjs-container .ce-block__content,
    .editorjs-container .ce-toolbar__content {
        /* Keep tools inside the container and provide even more space for the left toolbar popups */
        max-width: calc(100% - 150px) !important;
        margin: 0 auto !important;
    }
    /* Enlarge and highlight native toolbar buttons for better UX */
    .editorjs-container .ce-toolbar__plus,
    .editorjs-container .ce-toolbar__settings-btn {
        background: #eff6ff !important; /* light blue bg */
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        color: #2563eb !important; /* blue icon */
        width: 32px !important;
        height: 32px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    .editorjs-container .ce-toolbar__plus:hover,
    .editorjs-container .ce-toolbar__settings-btn:hover {
        background: #dbeafe !important;
        color: #1d4ed8 !important;
        transform: scale(1.05);
    }
    
    /* Make the plus sign slightly thicker */
    .editorjs-container .ce-toolbar__plus svg {
        stroke-width: 1px;
    }
    .editorjs-container .ce-inline-toolbar {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .editorjs-container .ce-conversion-toolbar {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    /* Image tool styles */
    .editorjs-container .image-tool__image {
        border-radius: 12px;
        overflow: hidden;
    }
    /* Code block */
    .editorjs-container .ce-code__textarea {
        font-family: 'JetBrains Mono', monospace;
        background: #1e293b;
        color: #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        border: none;
        min-height: 100px;
    }
    /* Header placeholder */
    .editorjs-container h1.ce-header[contenteditable=true][data-placeholder]:empty::before,
    .editorjs-container h2.ce-header[contenteditable=true][data-placeholder]:empty::before,
    .editorjs-container h3.ce-header[contenteditable=true][data-placeholder]:empty::before,
    .editorjs-container h4.ce-header[contenteditable=true][data-placeholder]:empty::before,
    .editorjs-container h5.ce-header[contenteditable=true][data-placeholder]:empty::before,
    .editorjs-container h6.ce-header[contenteditable=true][data-placeholder]:empty::before {
        color: #9ca3af;
    }
    
    /* Z-Index fixes for toolbars to prevent overlapping blocks */
    .editorjs-container .ce-toolbar { z-index: 10 !important; }
    .editorjs-container .ce-inline-toolbar { z-index: 20 !important; }
    .editorjs-container .ce-settings { z-index: 30 !important; }
    
    /* Typography Rendering inside Editor */
    .editorjs-container h1.ce-header { font-size: 2.25rem; line-height: 2.5rem; font-weight: 700; margin-bottom: 1rem; margin-top: 2rem; color: #111827; }
    .editorjs-container h2.ce-header { font-size: 1.875rem; line-height: 2.25rem; font-weight: 700; margin-bottom: 1rem; margin-top: 1.5rem; color: #1f2937; }
    .editorjs-container h3.ce-header { font-size: 1.5rem; line-height: 2rem; font-weight: 600; margin-bottom: 0.75rem; margin-top: 1.5rem; color: #374151; }
    .editorjs-container h4.ce-header { font-size: 1.25rem; line-height: 1.75rem; font-weight: 600; margin-bottom: 0.5rem; margin-top: 1.25rem; color: #4b5563; }
    .editorjs-container h5.ce-header { font-size: 1.125rem; line-height: 1.75rem; font-weight: 600; margin-bottom: 0.5rem; margin-top: 1rem; color: #4b5563; }
    .editorjs-container h6.ce-header { font-size: 1rem; line-height: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; margin-top: 1rem; color: #6b7280; }
    
    .editorjs-container .cdx-list { margin-top: 1rem; margin-bottom: 1rem; padding-left: 1.5rem; }
    .editorjs-container .cdx-list--unordered { list-style-type: decimal; } /* Editor.js uses decimal for ordered, disc for unordered conceptually but class names are swapped in DOM sometimes */
    ol.cdx-list { list-style-type: decimal; }
    ul.cdx-list { list-style-type: disc; }
    .editorjs-container .cdx-list__item { padding: 0.25rem 0; }
    
    .editorjs-container .cdx-quote { border-left: 4px solid #93c5fd; padding-left: 1.25rem; margin: 1.5rem 0; font-style: italic; color: #4b5563; }
    .editorjs-container .cdx-quote__text { font-size: 1.125rem; line-height: 1.75rem; }
    .editorjs-container .cdx-quote__caption { font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem; font-style: normal; }
    .editorjs-container .cdx-quote__caption::before { content: "— "; }
    
    .editorjs-container mark.cdx-marker { background-color: #fef08a; padding: 0.125rem 0.25rem; border-radius: 0.25rem; }
    .editorjs-container code.inline-code { background-color: #f3f4f6; color: #ef4444; padding: 0.125rem 0.375rem; border-radius: 0.375rem; font-size: 0.875em; font-family: 'JetBrains Mono', monospace; }
@endPushOnce
