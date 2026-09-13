@extends('layouts.vendor')

@section('title', __('messages.vendor_menu_products') . ' - ' . __('messages.vendor_brand'))

@section('styles')
<style>
    /* Restricted Card */
    .restricted-card {
        background: white;
        padding: 40px 30px;
        border-radius: 16px;
        border: 1px solid #fee2e2;
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.05);
        text-align: center;
        max-width: 600px;
        margin: 40px auto;
    }
    .restricted-card h3 {
        color: #dc2626;
        margin: 0 0 14px 0;
        font-size: 1.35rem;
        font-weight: 700;
    }
    .restricted-card p {
        color: #475569;
        line-height: 1.6;
        margin-bottom: 24px;
        font-size: 0.9375rem;
    }
    .btn-subscribe {
        background: #10b981;
        color: white;
        padding: 12px 26px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9375rem;
        display: inline-block;
        margin-bottom: 16px;
        transition: 0.2s;
    }
    .btn-subscribe:hover { background: #059669; }
    .link-back {
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .link-back:hover { text-decoration: underline; }

    /* Product Table Card */
    .product-table-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .product-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }
    .product-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .product-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .product-table tr:last-child td { border-bottom: none; }
    .product-table tr:hover { background-color: #f8fafc; }

    .btn-table-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-edit-prod { background: #e0f2fe; color: #0369a1; }
    .btn-edit-prod:hover { background: #0284c7; color: white; }
    .btn-del-prod { background: #fee2e2; color: #b91c1c; }
    .btn-del-prod:hover { background: #ef4444; color: white; }

    /* Modals */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        overflow-y: auto;
        padding: 20px 15px;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        animation: modalIn 0.2s ease-out;
        margin: auto;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.96) translateY(8px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-header {
        padding: 16px 22px;
        background: #1e1b4b;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { font-size: 1rem; font-weight: 600; margin: 0; }
    .modal-close {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 22px;
        cursor: pointer;
        line-height: 1;
    }
    .modal-close:hover { color: #fff; }
    .modal-body { padding: 22px; }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 5px; }
    .form-control {
        width: 100%;
        padding: 9px 13px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        outline: none;
        transition: 0.2s;
        box-sizing: border-box;
    }
    .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
    .modal-footer {
        padding: 14px 22px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
            📦 {{ __('messages.vendor_prod_list_heading') }}
        </h1>
        <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
            {{ app()->getLocale() === 'sw' 
                ? 'Dhibiti orodha ya bidhaa zako, ongeza bidhaa mpya, sasisha picha na bei.' 
                : 'Manage your store catalog, upload new products with multi-image gallery, and update pricing.' }}
        </p>
    </div>
    @if(!$isRestricted)
        <div>
            <button type="button" onclick="openAddModal()" style="background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);">
                ➕ {{ __('messages.vendor_prod_add_heading') }}
            </button>
        </div>
    @endif
</div>

@if(session('success'))
    <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
        ⚠️ {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($isRestricted)
    <div class="restricted-card">
        <h3>{{ __('messages.vendor_unauthorized_title') }}</h3>
        <p>{!! __('messages.vendor_unauthorized_desc') !!}</p>
        <a href="{{ route('vendor.subscribe.page') }}" class="btn-subscribe">
            💎 {{ __('messages.vendor_btn_subscribe') }}
        </a>
        <br>
        <a href="{{ route('vendor.dashboard') }}" class="link-back">
            ← {{ __('messages.vendor_back_dash') }}
        </a>
    </div>
@else
    <div class="product-table-card">
        <div style="overflow-x: auto;">
            <table class="product-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">{{ __('messages.vendor_prod_th_image') }}</th>
                        <th style="width: 30%;">{{ __('messages.vendor_prod_th_name') }}</th>
                        <th style="width: 20%;">{{ __('messages.vendor_prod_th_category') }}</th>
                        <th style="width: 18%;">{{ __('messages.vendor_prod_th_price') }}</th>
                        <th style="width: 10%;">{{ __('messages.vendor_prod_th_stock') }}</th>
                        <th style="width: 12%;">{{ __('messages.vendor_prod_th_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $prod)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <img src="{{ $prod->image_url }}" alt="" style="width: 46px; height: 46px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    @php
                                        $imgs = $prod->images_list;
                                        $cnt = count($imgs);
                                    @endphp
                                    @if($cnt > 1)
                                        <span style="background: #e0f2fe; color: #0369a1; font-size: 0.7rem; font-weight: 700; padding: 2px 6px; border-radius: 9999px;" title="{{ $cnt }} {{ app()->getLocale() === 'sw' ? 'picha' : 'images' }}">
                                            +{{ $cnt - 1 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a;">{{ $prod->name }}</div>
                                <div style="color: #64748b; font-size: 0.75rem;">ID: #{{ $prod->id }}</div>
                            </td>
                            <td style="color: #475569;">{{ $prod->category->name ?? '-' }}</td>
                            <td>
                                <span style="color: #0f172a; font-weight: 700;">TZS {{ number_format($prod->price, 2) }}</span>
                            </td>
                            <td>
                                @if($prod->stock > 5)
                                    <span style="background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 9999px; font-weight: 600; font-size: 0.75rem;">
                                        {{ $prod->stock }}
                                    </span>
                                @else
                                    <span style="background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 9999px; font-weight: 600; font-size: 0.75rem;">
                                        {{ $prod->stock }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <button type="button" class="btn-table-action btn-edit-prod" onclick="openEditModal({{ json_encode([
                                        'id' => $prod->id,
                                        'name' => $prod->name,
                                        'category_id' => $prod->category_id,
                                        'price' => $prod->price,
                                        'stock' => $prod->stock,
                                        'details' => $prod->details,
                                        'images' => $prod->images_list
                                    ]) }})" title="{{ app()->getLocale() === 'sw' ? 'Hariri Bidhaa' : 'Edit Product' }}">
                                        ✏️ {{ app()->getLocale() === 'sw' ? 'Badili' : 'Edit' }}
                                    </button>

                                    <form action="{{ route('vendor.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') ?? 'Are you sure?' }}');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-table-action btn-del-prod" title="{{ __('messages.vendor_prod_btn_delete') }}">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px; color: #64748b;">
                                {{ __('messages.vendor_prod_no_products') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $products->links() }}
        </div>
    @endif
@endif

<!-- Add Product Modal -->
<div id="addProductModal" class="modal-overlay {{ ($errors->any() && !session('error')) ? 'active' : '' }}">
    <div class="modal-box">
        <div class="modal-header">
            <h3>➕ {{ __('messages.vendor_prod_add_heading') }}</h3>
            <button type="button" class="modal-close" onclick="closeAddModal()">&times;</button>
        </div>
        <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('messages.vendor_prod_lbl_name') }}</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ __('messages.vendor_prod_lbl_category') }}</label>
                    <select name="category_id" required class="form-control">
                        <option value="">{{ __('messages.vendor_prod_opt_select_cat') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group">
                        <label>{{ __('messages.vendor_prod_lbl_price') }}</label>
                        <input type="number" name="price" value="{{ old('price') }}" min="0" step="100" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.vendor_prod_lbl_stock') }}</label>
                        <input type="number" name="stock" value="{{ old('stock', 10) }}" min="1" required class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        📸 {{ app()->getLocale() === 'sw' ? 'Picha za Bidhaa (Chagua picha angalau 2 na kuendelea):' : 'Product Images (Select at least 2 images):' }} <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="file" name="images[]" id="productImagesInput" multiple accept="image/*" required class="form-control" onchange="previewProductImages(this)">
                    <small style="display: block; color: #64748b; margin-top: 5px; font-size: 0.75rem;">
                        💡 {{ app()->getLocale() === 'sw' ? 'Shikilia kitufe cha Ctrl (au Cmd kwenye Mac) ili kuchagua picha 2 au zaidi kwa pamoja.' : 'Hold Ctrl (or Cmd on Mac) to select multiple images.' }}
                    </small>
                    <div id="imageCountNotice" style="margin-top: 6px; font-size: 0.75rem; font-weight: 600;"></div>
                    <div id="productImagesPreview" style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px;"></div>
                </div>

                <div class="form-group">
                    <label>{{ __('messages.vendor_prod_lbl_desc') }}</label>
                    <textarea name="details" rows="2" class="form-control">{{ old('details') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-table-action" style="background: #e2e8f0; color: #475569;" onclick="closeAddModal()">
                    {{ __('messages.vendor_prod_btn_cancel') }}
                </button>
                <button type="submit" class="btn-table-action" style="background: #2563eb; color: white; font-weight: 700;">
                    💾 {{ __('messages.vendor_prod_btn_save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>✏️ {{ app()->getLocale() === 'sw' ? 'Badili Taarifa za Bidhaa' : 'Edit Product' }}</h3>
            <button type="button" class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('messages.vendor_prod_lbl_name') }}</label>
                    <input type="text" name="name" id="edit_prod_name" required class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ __('messages.vendor_prod_lbl_category') }}</label>
                    <select name="category_id" id="edit_prod_category" required class="form-control">
                        <option value="">{{ __('messages.vendor_prod_opt_select_cat') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group">
                        <label>{{ __('messages.vendor_prod_lbl_price') }}</label>
                        <input type="number" name="price" id="edit_prod_price" min="0" step="100" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.vendor_prod_lbl_stock') }}</label>
                        <input type="number" name="stock" id="edit_prod_stock" min="0" required class="form-control">
                    </div>
                </div>

                <!-- Current Images preview -->
                <div style="margin-bottom: 12px; background: #f8fafc; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 6px;">
                        🖼️ {{ app()->getLocale() === 'sw' ? 'Picha za Sasa za Bidhaa:' : 'Current Images:' }}
                    </label>
                    <div id="editCurrentImages" style="display: flex; flex-wrap: wrap; gap: 6px;"></div>
                    <small style="display: block; color: #64748b; font-size: 0.7rem; margin-top: 4px;">
                        {{ app()->getLocale() === 'sw' ? 'Picha hizi zitaendelea kutumika isipokuwa ukichagua picha mpya hapa chini.' : 'These images will remain unless you upload replacements below.' }}
                    </small>
                </div>

                <div class="form-group">
                    <label>
                        📸 {{ app()->getLocale() === 'sw' ? 'Badilisha Picha (Hiari - chagua picha angalau 2 na kuendelea):' : 'Replace Images (Optional - select at least 2 images):' }}
                    </label>
                    <input type="file" name="images[]" id="editProductImagesInput" multiple accept="image/*" class="form-control" onchange="previewEditProductImages(this)">
                    <div id="editImageCountNotice" style="margin-top: 6px; font-size: 0.75rem; font-weight: 600;"></div>
                    <div id="editProductImagesPreview" style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px;"></div>
                </div>

                <div class="form-group">
                    <label>{{ __('messages.vendor_prod_lbl_desc') }}</label>
                    <textarea name="details" id="edit_prod_details" rows="2" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-table-action" style="background: #e2e8f0; color: #475569;" onclick="closeEditModal()">
                    {{ __('messages.vendor_prod_btn_cancel') }}
                </button>
                <button type="submit" class="btn-table-action" style="background: #0284c7; color: white; font-weight: 700;">
                    💾 {{ app()->getLocale() === 'sw' ? 'Hifadhi Mabadiliko' : 'Save Changes' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAddModal() {
        document.getElementById('addProductModal').classList.add('active');
    }
    function closeAddModal() {
        document.getElementById('addProductModal').classList.remove('active');
    }

    function previewProductImages(input) {
        const previewContainer = document.getElementById('productImagesPreview');
        const notice = document.getElementById('imageCountNotice');
        previewContainer.innerHTML = '';
        
        if (!input.files || input.files.length === 0) {
            notice.innerHTML = '';
            return;
        }

        const count = input.files.length;
        if (count < 2) {
            notice.innerHTML = '<span style="color:#ef4444;">⚠️ ' + ( '{{ app()->getLocale() === "sw" ? "Umechagua picha 1 tu! Mfumo unahitaji picha kuanzia 2 na kuendelea." : "You selected only 1 photo! Please select 2 or more photos." }}' ) + '</span>';
        } else {
            notice.innerHTML = '<span style="color:#10b981;">✓ ' + count + ' ' + ( '{{ app()->getLocale() === "sw" ? "picha zimechaguliwa." : "images selected." }}' ) + '</span>';
        }

        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgWrap = document.createElement('div');
                    imgWrap.style.position = 'relative';
                    imgWrap.style.width = '52px';
                    imgWrap.style.height = '52px';
                    imgWrap.style.borderRadius = '6px';
                    imgWrap.style.overflow = 'hidden';
                    imgWrap.style.border = '2px solid #2563eb';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    
                    const badge = document.createElement('span');
                    badge.innerText = '#' + (index + 1);
                    badge.style.position = 'absolute';
                    badge.style.bottom = '2px';
                    badge.style.right = '2px';
                    badge.style.background = 'rgba(0,0,0,0.65)';
                    badge.style.color = '#fff';
                    badge.style.fontSize = '9px';
                    badge.style.padding = '1px 3px';
                    badge.style.borderRadius = '3px';

                    imgWrap.appendChild(img);
                    imgWrap.appendChild(badge);
                    previewContainer.appendChild(imgWrap);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function openEditModal(prod) {
        document.getElementById('editProductForm').action = "{{ url('/vendor/products') }}/" + prod.id + "/update";
        document.getElementById('edit_prod_name').value = prod.name || '';
        document.getElementById('edit_prod_category').value = prod.category_id || '';
        document.getElementById('edit_prod_price').value = prod.price || '';
        document.getElementById('edit_prod_stock').value = prod.stock !== undefined ? prod.stock : 10;
        document.getElementById('edit_prod_details').value = prod.details || '';
        
        const fileInput = document.getElementById('editProductImagesInput');
        if (fileInput) fileInput.value = '';
        document.getElementById('editImageCountNotice').innerHTML = '';
        document.getElementById('editProductImagesPreview').innerHTML = '';

        const currentContainer = document.getElementById('editCurrentImages');
        currentContainer.innerHTML = '';
        if (prod.images && prod.images.length > 0) {
            prod.images.forEach((imgUrl) => {
                const img = document.createElement('img');
                img.src = imgUrl;
                img.style.width = '44px';
                img.style.height = '44px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '6px';
                img.style.border = '1px solid #cbd5e1';
                currentContainer.appendChild(img);
            });
        }

        document.getElementById('editProductModal').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editProductModal').classList.remove('active');
    }

    function previewEditProductImages(input) {
        const previewContainer = document.getElementById('editProductImagesPreview');
        const notice = document.getElementById('editImageCountNotice');
        previewContainer.innerHTML = '';
        
        if (!input.files || input.files.length === 0) {
            notice.innerHTML = '';
            return;
        }

        const count = input.files.length;
        if (count < 2) {
            notice.innerHTML = '<span style="color:#ef4444;">⚠️ ' + ( '{{ app()->getLocale() === "sw" ? "Umechagua picha 1 tu! Ukibadilisha, chagua angalau picha 2." : "Please select at least 2 images." }}' ) + '</span>';
        } else {
            notice.innerHTML = '<span style="color:#10b981;">✓ ' + count + ' ' + ( '{{ app()->getLocale() === "sw" ? "picha mpya zimechaguliwa." : "new images selected." }}' ) + '</span>';
        }

        Array.from(input.files).forEach((file) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgWrap = document.createElement('div');
                    imgWrap.style.width = '48px';
                    imgWrap.style.height = '48px';
                    imgWrap.style.borderRadius = '6px';
                    imgWrap.style.overflow = 'hidden';
                    imgWrap.style.border = '2px solid #0284c7';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';

                    imgWrap.appendChild(img);
                    previewContainer.appendChild(imgWrap);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addForm = document.querySelector('#addProductModal form');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const input = document.getElementById('productImagesInput');
                if (!input.files || input.files.length < 2) {
                    e.preventDefault();
                    alert('{{ app()->getLocale() === "sw" ? "⚠️ Tafadhali chagua angalau picha 2 au zaidi za bidhaa kabla ya kuhifadhi!" : "⚠️ Please select at least 2 images for the product before saving!" }}');
                    return false;
                }
            });
        }

        const editForm = document.getElementById('editProductForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                const input = document.getElementById('editProductImagesInput');
                if (input.files && input.files.length > 0 && input.files.length < 2) {
                    e.preventDefault();
                    alert('{{ app()->getLocale() === "sw" ? "⚠️ Ukibadilisha picha, lazima uchague angalau picha 2 au zaidi!" : "⚠️ When changing images, you must select at least 2 images!" }}');
                    return false;
                }
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
</script>
@endsection
