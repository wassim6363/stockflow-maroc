@php
    $productPayload = $products->map(fn ($product) => [
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku,
        'categoryId' => $product->category_id,
        'category' => $product->category?->name ?? 'Sans catégorie',
        'price' => (float) $product->sale_price,
        'stock' => (float) $product->current_stock,
        'unit' => $product->unit ?? 'piece',
        'image' => $product->image_path ? \Illuminate\Support\Facades\Storage::url($product->image_path) : null,
    ])->values();
@endphp

<div
    class="sf-pos"
    x-data="stockflowPos({ products: @js($productPayload), companyName: @js($companyName) })"
>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');
        
        [x-cloak] { display: none !important; }

        .sf-pos {
            min-height: 100vh;
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 40% 20%, hsla(210,100%,93%,1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(230,100%,95%,1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(215,100%,91%,1) 0px, transparent 50%);
            color: #0f172a;
            font-family: 'Outfit', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        .sf-pos-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 28rem;
            gap: 1.5rem;
            padding: 1.5rem;
            max-width: 1920px;
            margin: 0 auto;
        }

        .sf-pos-topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }

        .sf-pos-back {
            display: inline-flex;
            align-items: center;
            gap: 0.85rem;
            color: #0f172a;
            font-size: 1.15rem;
            font-weight: 800;
            text-decoration: none;
            transition: transform 0.2s ease;
        }

        .sf-pos-back:hover {
            transform: translateX(-3px);
        }

        .sf-pos-back span {
            display: inline-flex;
            width: 2.75rem;
            height: 2.75rem;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff, #f1f5f9);
            color: #2563eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), inset 0 2px 4px rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .sf-pos-company {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sf-pos-company-pill {
            border: 1px solid rgba(219, 234, 254, 0.6);
            border-radius: 999px;
            background: rgba(239, 246, 255, 0.8);
            backdrop-filter: blur(8px);
            color: #1d4ed8;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            font-weight: 700;
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.8);
        }

        .sf-pos-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1.5rem;
            align-items: center;
            margin-bottom: 2rem;
            border-radius: 24px;
            background: linear-gradient(135deg, #1e40af, #3b82f6, #6366f1);
            background-size: 200% 200%;
            animation: gradientMove 8s ease infinite;
            color: #ffffff;
            padding: 2.5rem;
            box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .sf-pos-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .sf-pos-hero h1 {
            margin: 0;
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 900;
            letter-spacing: -0.02em;
            line-height: 1.1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .sf-pos-hero p {
            max-width: 42rem;
            margin: 0.75rem 0 0;
            color: #e0e7ff;
            font-size: 1.05rem;
            font-weight: 400;
            line-height: 1.6;
        }

        .sf-pos-hero-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(8rem, 1fr));
            gap: 1rem;
            position: relative;
            z-index: 10;
        }

        .sf-pos-mini {
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            padding: 1.1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .sf-pos-mini:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.25);
        }

        .sf-pos-mini span {
            display: block;
            color: #dbeafe;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sf-pos-mini strong {
            display: block;
            margin-top: 0.3rem;
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sf-pos-toolbar {
            display: grid;
            grid-template-columns: minmax(18rem, 1fr) auto;
            gap: 1.25rem;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .sf-pos-search {
            position: relative;
        }

        .sf-pos-search svg {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            width: 1.25rem;
            color: #64748b;
            transform: translateY(-50%);
            transition: color 0.3s ease;
        }

        .sf-pos-search input {
            width: 100%;
            height: 3.5rem;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            color: #0f172a;
            padding: 0 1rem 0 3.2rem;
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), inset 0 2px 4px rgba(255, 255, 255, 0.5);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sf-pos-search input:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2), inset 0 2px 4px rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }

        .sf-pos-search input:focus + svg {
            color: #3b82f6;
        }

        .sf-pos-actions {
            display: flex;
            gap: 0.75rem;
        }

        .sf-pos-action {
            display: inline-flex;
            height: 3.5rem;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            color: #1e293b;
            padding: 0 1.25rem;
            font-size: 0.95rem;
            font-weight: 700;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), inset 0 2px 4px rgba(255, 255, 255, 0.5);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .sf-pos-action:hover {
            transform: translateY(-2px);
            background: #ffffff;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #e2e8f0;
        }

        .sf-pos-categories {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            margin-bottom: 2rem;
            padding-bottom: 0.5rem;
            scrollbar-width: none;
        }

        .sf-pos-categories::-webkit-scrollbar {
            display: none;
        }

        .sf-pos-category {
            display: inline-flex;
            min-height: 2.75rem;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            color: #475569;
            padding: 0.5rem 1.25rem;
            white-space: nowrap;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .sf-pos-category:hover {
            background: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .sf-pos-category.is-active {
            border-color: #2563eb;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.25);
            transform: translateY(-1px);
        }

        .sf-pos-products-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .sf-pos-products-head h2 {
            margin: 0;
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .sf-pos-products-head span {
            color: #64748b;
            font-size: 1rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.7);
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.8);
        }

        .sf-pos-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .sf-pos-product {
            display: flex;
            min-height: 16rem;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            text-align: left;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }

        .sf-pos-product:hover {
            transform: translateY(-6px) scale(1.02);
            background: rgba(255, 255, 255, 0.95);
            border-color: #bfdbfe;
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.25);
        }

        .sf-pos-product-media {
            position: relative;
            display: flex;
            height: 9rem;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f1f5f9, #f8fafc);
            overflow: hidden;
        }

        .sf-pos-product-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .sf-pos-product:hover .sf-pos-product-media img {
            transform: scale(1.08);
        }

        .sf-pos-product-initial {
            display: inline-flex;
            width: 3.5rem;
            height: 3.5rem;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 800;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.2);
        }

        .sf-pos-stock {
            position: absolute;
            right: 0.75rem;
            top: 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            color: #16a34a;
            padding: 0.25rem 0.6rem;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sf-pos-stock.is-low {
            color: #ea580c;
            background: rgba(255, 237, 213, 0.95);
        }

        .sf-pos-product-body {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 1rem;
        }

        .sf-pos-product-body small {
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sf-pos-product-body strong {
            display: -webkit-box;
            min-height: 2.75rem;
            overflow: hidden;
            margin-top: 0.4rem;
            color: #0f172a;
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1.3;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .sf-pos-product-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: auto;
            padding-top: 0.75rem;
        }

        .sf-pos-price {
            color: #2563eb;
            font-size: 1.15rem;
            font-weight: 900;
        }

        .sf-pos-add {
            display: inline-flex;
            width: 2.5rem;
            height: 2.5rem;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            transition: all 0.3s ease;
        }

        .sf-pos-product:hover .sf-pos-add {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            transform: scale(1.1);
        }

        .sf-pos-add svg {
            width: 1.25rem;
        }

        .sf-pos-empty-products {
            display: grid;
            min-height: 20rem;
            place-items: center;
            border: 2px dashed rgba(203, 213, 225, 0.8);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(8px);
            color: #64748b;
            text-align: center;
            font-weight: 600;
        }

        .sf-pos-ticket {
            position: sticky;
            top: 5.5rem;
            display: flex;
            max-height: calc(100vh - 7rem);
            min-height: 42rem;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px) saturate(150%);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.1);
        }

        .sf-pos-ticket-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.5);
        }

        .sf-pos-ticket-head h2 {
            margin: 0;
            color: #0f172a;
            font-size: 1.25rem;
            font-weight: 800;
        }

        .sf-pos-ticket-head span {
            display: inline-flex;
            min-width: 2.25rem;
            height: 2.25rem;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        .sf-pos-customer {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            background: rgba(248, 250, 252, 0.5);
        }

        .sf-pos-customer label {
            display: block;
            margin-bottom: 0.5rem;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sf-pos-customer input {
            width: 100%;
            height: 3.25rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            padding: 0 1rem;
            background: #ffffff;
            color: #0f172a;
            font-weight: 600;
            outline: none;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }
        
        .sf-pos-customer input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .sf-pos-ticket-items {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .sf-pos-ticket-items::-webkit-scrollbar {
            width: 6px;
        }
        .sf-pos-ticket-items::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .sf-pos-ticket-empty {
            display: grid;
            height: 100%;
            min-height: 18rem;
            place-items: center;
            color: #64748b;
            text-align: center;
            font-weight: 600;
        }

        .sf-pos-ticket-empty svg {
            width: 4rem;
            margin: 0 auto 1.5rem;
            color: #94a3b8;
            animation: floatCart 4s ease-in-out infinite;
        }

        @keyframes floatCart {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); color: #3b82f6; filter: drop-shadow(0 10px 15px rgba(59,130,246,0.2)); }
            100% { transform: translateY(0px); }
        }

        .sf-pos-line {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            background: #ffffff;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: transform 0.2s ease;
        }

        .sf-pos-line:hover {
            transform: translateX(2px);
            border-color: #bfdbfe;
        }

        .sf-pos-line + .sf-pos-line {
            margin-top: 0.75rem;
        }

        .sf-pos-line strong {
            display: block;
            color: #0f172a;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .sf-pos-line small {
            display: block;
            margin-top: 0.2rem;
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .sf-pos-line-total {
            color: #0f172a;
            text-align: right;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .sf-pos-qty {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            background: #f8fafc;
            padding: 0.25rem;
        }

        .sf-pos-qty button {
            display: inline-flex;
            width: 1.75rem;
            height: 1.75rem;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #ffffff;
            color: #1e293b;
            font-weight: 800;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }

        .sf-pos-qty button:hover {
            background: #3b82f6;
            color: white;
        }

        .sf-pos-qty span {
            min-width: 1.5rem;
            color: #0f172a;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .sf-pos-remove {
            margin-top: 0.75rem;
            color: #ef4444;
            font-size: 0.8rem;
            font-weight: 700;
            transition: color 0.2s;
            cursor: pointer;
        }
        
        .sf-pos-remove:hover {
            color: #b91c1c;
            text-decoration: underline;
        }

        .sf-pos-ticket-bottom {
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            padding: 1.5rem;
            background: rgba(248, 250, 252, 0.5);
        }

        .sf-pos-total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            color: #475569;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .sf-pos-total-row.is-grand {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px dashed #cbd5e1;
            color: #0f172a;
            font-size: 1.35rem;
            font-weight: 900;
        }

        .sf-pos-payments {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin: 1.25rem 0;
        }

        .sf-pos-payment {
            min-height: 3rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            background: #ffffff;
            color: #475569;
            font-size: 0.9rem;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
        }

        .sf-pos-payment:hover {
            border-color: #bfdbfe;
            background: #f8fafc;
        }

        .sf-pos-payment.is-active {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #1d4ed8;
            box-shadow: inset 0 0 0 1px #3b82f6;
        }

        .sf-pos-checkout {
            display: flex;
            width: 100%;
            min-height: 3.75rem;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            border-radius: 16px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 800;
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.25);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            border: none;
        }

        .sf-pos-checkout:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(16, 185, 129, 0.35);
        }

        .sf-pos-checkout:disabled {
            cursor: not-allowed;
            background: #cbd5e1;
            box-shadow: none;
            color: #94a3b8;
        }

        .sf-pos-clear {
            display: flex;
            width: 100%;
            min-height: 3rem;
            align-items: center;
            justify-content: center;
            margin-top: 0.75rem;
            border: 1px solid #fecaca;
            border-radius: 14px;
            background: #fff1f2;
            color: #e11d48;
            font-size: 0.95rem;
            font-weight: 700;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .sf-pos-clear:hover {
            background: #ffe4e6;
            border-color: #fda4af;
        }

        @media (max-width: 1280px) {
            .sf-pos-shell {
                grid-template-columns: minmax(0, 1fr) 25rem;
            }

            .sf-pos-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 980px) {
            .sf-pos-shell,
            .sf-pos-hero,
            .sf-pos-toolbar {
                grid-template-columns: 1fr;
            }

            .sf-pos-ticket {
                position: static;
                min-height: 32rem;
            }

            .sf-pos-hero-metrics {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .sf-pos-shell,
            .sf-pos-topbar {
                padding: 1rem;
            }

            .sf-pos-company-pill {
                display: none;
            }

            .sf-pos-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sf-pos-actions,
            .sf-pos-hero-metrics {
                display: none;
            }
        }

        /* --- PRINT STYLES FOR RECEIPT (TICKET) --- */
        @media screen {
            .sf-print-receipt { display: none !important; }
        }

        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }
            body, html {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .sf-pos-shell, .sf-pos-topbar { 
                display: none !important; 
            }
            .sf-print-receipt {
                display: block !important;
                width: 80mm;
                padding: 5mm;
                margin: 0 auto;
                font-family: 'Courier New', Courier, monospace;
                color: #000;
                font-size: 12px;
                line-height: 1.2;
            }
            .sf-print-header { text-align: center; margin-bottom: 4mm; }
            .sf-print-header h2 { font-size: 16px; margin: 0 0 2mm 0; font-weight: bold; }
            .sf-print-header p { margin: 1mm 0; font-size: 11px; }
            
            .sf-print-divider { border-top: 1px dashed #000; margin: 3mm 0; }
            
            .sf-print-items { width: 100%; border-collapse: collapse; margin-bottom: 3mm; }
            .sf-print-items th { border-bottom: 1px dashed #000; border-top: 1px dashed #000; padding: 2mm 0; text-align: left; font-size: 11px; font-weight: bold; }
            .sf-print-items td { padding: 2mm 0; vertical-align: top; font-size: 11px; }
            .sf-print-items th:last-child, .sf-print-items td:last-child { text-align: right; }
            .sf-print-items th:nth-child(1), .sf-print-items td:nth-child(1) { width: 10%; }
            
            .sf-print-totals { border-top: 1px dashed #000; padding-top: 3mm; margin-bottom: 4mm; }
            .sf-print-totals div { display: flex; justify-content: space-between; margin-bottom: 1.5mm; }
            .sf-print-totals strong { font-size: 14px; font-weight: bold; }
            
            .sf-print-footer { text-align: center; font-size: 10px; margin-top: 5mm; }
            .sf-print-footer p { margin: 1mm 0; }
        }
    </style>

    <header class="sf-pos-topbar">
        <a href="{{ url('/admin') }}" class="sf-pos-back">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </span>
            Caisse
        </a>

        <div class="sf-pos-company">
            <div class="sf-pos-company-pill">{{ $companyName }}</div>
            <a href="{{ url('/admin/sales-orders') }}" class="sf-pos-action">Ventes</a>
        </div>
    </header>

    <main class="sf-pos-shell">
        <section>
            <div class="sf-pos-hero">
                <div>
                    <h1>Point de vente rapide</h1>
                    <p>Encaissez une vente en quelques clics: recherchez un produit, ajoutez-le au ticket, choisissez le paiement et validez.</p>
                </div>

                <div class="sf-pos-hero-metrics">
                    <div class="sf-pos-mini">
                        <span>Produits</span>
                        <strong x-text="products.length"></strong>
                    </div>
                    <div class="sf-pos-mini">
                        <span>Ticket</span>
                        <strong x-text="itemsCount()"></strong>
                    </div>
                    <div class="sf-pos-mini">
                        <span>Total</span>
                        <strong x-text="money(total())"></strong>
                    </div>
                </div>
            </div>

            <div class="sf-pos-toolbar">
                <label class="sf-pos-search">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="search" x-model.debounce.150ms="search" placeholder="Rechercher produit, SKU ou catégorie...">
                </label>

                <div class="sf-pos-actions">
                    <button type="button" class="sf-pos-action" @click="search = ''; selectedCategory = null">
                        Effacer filtre
                    </button>
                    <button type="button" class="sf-pos-action" @click="clearCart()">
                        Nouveau ticket
                    </button>
                </div>
            </div>

            <nav class="sf-pos-categories" aria-label="Catégories">
                <button type="button" class="sf-pos-category" :class="{ 'is-active': selectedCategory === null }" @click="selectedCategory = null">
                    Tous
                </button>
                @foreach($categories as $category)
                    <button type="button" class="sf-pos-category" :class="{ 'is-active': selectedCategory === {{ $category->id }} }" @click="selectedCategory = {{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </nav>

            <div class="sf-pos-products-head">
                <h2>Catalogue</h2>
                <span><span x-text="filteredProducts().length"></span> produit(s)</span>
            </div>

            <template x-if="filteredProducts().length > 0">
                <div class="sf-pos-grid">
                    <template x-for="product in filteredProducts()" :key="product.id">
                        <button type="button" class="sf-pos-product" @click="add(product)">
                            <div class="sf-pos-product-media">
                                <template x-if="product.image">
                                    <img :src="product.image" :alt="product.name">
                                </template>
                                <template x-if="! product.image">
                                    <span class="sf-pos-product-initial" x-text="initials(product.name)"></span>
                                </template>
                                <span class="sf-pos-stock" :class="{ 'is-low': product.stock <= 5 }" x-text="'Stock ' + stockLabel(product.stock)"></span>
                            </div>
                            <div class="sf-pos-product-body">
                                <small x-text="product.category"></small>
                                <strong x-text="product.name"></strong>
                                <div class="sf-pos-product-foot">
                                    <span class="sf-pos-price" x-text="money(product.price)"></span>
                                    <span class="sf-pos-add">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>
            </template>

            <template x-if="filteredProducts().length === 0">
                <div class="sf-pos-empty-products">
                    <div>
                        <strong>Aucun produit trouvé</strong>
                        <p>Essayez un autre mot-clé ou une autre catégorie.</p>
                    </div>
                </div>
            </template>
        </section>

        <aside class="sf-pos-ticket">
            <div class="sf-pos-ticket-head">
                <h2>Ticket en cours</h2>
                <span x-text="itemsCount()"></span>
            </div>

            <div class="sf-pos-customer">
                <label for="pos-customer">Client</label>
                <input id="pos-customer" type="text" x-model="customer" placeholder="Client comptoir">
            </div>

            <div class="sf-pos-ticket-items">
                <template x-if="cart.length === 0">
                    <div class="sf-pos-ticket-empty">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437m0 0L7.5 14.25h11.218c1.121-2.3 2.1-4.684 2.924-7.138A60.114 60.114 0 0 0 5.106 5.272Zm2.394 8.978a3 3 0 0 0-3 3h15.75m-12.75-3h11.218M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            <div>Cliquez sur un produit pour l'ajouter au ticket.</div>
                        </div>
                    </div>
                </template>

                <template x-for="line in cart" :key="line.id">
                    <div class="sf-pos-line">
                        <div>
                            <strong x-text="line.name"></strong>
                            <small x-text="money(line.price) + ' / ' + line.unit"></small>
                            <div class="sf-pos-qty">
                                <button type="button" @click="decrease(line.id)">-</button>
                                <span x-text="line.qty"></span>
                                <button type="button" @click="increase(line.id)">+</button>
                            </div>
                            <button type="button" class="sf-pos-remove" @click="remove(line.id)">Retirer</button>
                        </div>
                        <div class="sf-pos-line-total" x-text="money(line.price * line.qty)"></div>
                    </div>
                </template>
            </div>

            <div class="sf-pos-ticket-bottom">
                <div class="sf-pos-total-row">
                    <span>Sous-total</span>
                    <strong x-text="money(subtotal())"></strong>
                </div>
                <div class="sf-pos-total-row">
                    <span>Remise</span>
                    <strong>0,00 MAD</strong>
                </div>
                <div class="sf-pos-total-row is-grand">
                    <span>Total à payer</span>
                    <strong x-text="money(total())"></strong>
                </div>

                <div class="sf-pos-payments">
                    <button type="button" class="sf-pos-payment" :class="{ 'is-active': payment === 'cash' }" @click="payment = 'cash'">Espèces</button>
                    <button type="button" class="sf-pos-payment" :class="{ 'is-active': payment === 'card' }" @click="payment = 'card'">Carte</button>
                    <button type="button" class="sf-pos-payment" :class="{ 'is-active': payment === 'other' }" @click="payment = 'other'">Autre</button>
                </div>

                <button type="button" class="sf-pos-checkout" :disabled="cart.length === 0" @click="checkoutPreview()">
                    Valider le ticket
                </button>
                <button type="button" class="sf-pos-clear" x-show="cart.length > 0" x-cloak @click="clearCart()">
                    Vider le ticket
                </button>
            </div>
        </aside>
    </main>

    <!-- HIDDEN PRINT RECEIPT -->
    <div class="sf-print-receipt">
        <div class="sf-print-header">
            <h2 x-text="companyName"></h2>
            <p>Ticket de Caisse</p>
            <p x-text="new Date().toLocaleString('fr-FR')"></p>
            <template x-if="customer">
                <p x-text="'Client: ' + customer"></p>
            </template>
        </div>
        
        <div class="sf-print-divider"></div>

        <table class="sf-print-items">
            <thead>
                <tr>
                    <th>QT</th>
                    <th>DESIGNATION</th>
                    <th>MONTANT</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="line in cart" :key="line.id">
                    <tr>
                        <td x-text="line.qty"></td>
                        <td>
                            <div x-text="line.name" style="max-width: 40mm; word-wrap: break-word;"></div>
                            <small x-text="money(line.price)" style="font-size: 9px; color: #555;"></small>
                        </td>
                        <td x-text="money(line.price * line.qty)"></td>
                    </tr>
                </template>
            </tbody>
        </table>

        <div class="sf-print-totals">
            <div>
                <span>SOUS-TOTAL:</span>
                <span x-text="money(subtotal())"></span>
            </div>
            <div>
                <span>REMISE:</span>
                <span>0,00 MAD</span>
            </div>
            <div style="margin-top: 2mm;">
                <strong>TOTAL:</strong>
                <strong x-text="money(total())"></strong>
            </div>
            <div class="sf-print-divider" style="margin: 2mm 0;"></div>
            <div>
                <span>PAIEMENT:</span>
                <strong x-text="payment === 'cash' ? 'ESPECES' : (payment === 'card' ? 'CARTE' : 'AUTRE')"></strong>
            </div>
        </div>

        <div class="sf-print-footer">
            <p>Merci de votre visite !</p>
            <p>A Bientôt.</p>
        </div>
    </div>

    <script>
        function stockflowPos(config) {
            return {
                products: config.products || [],
                companyName: config.companyName || 'StockFlow Maroc',
                search: '',
                selectedCategory: null,
                customer: '',
                payment: 'cash',
                cart: [],

                filteredProducts() {
                    const query = this.search.trim().toLowerCase();

                    return this.products.filter((product) => {
                        const matchesCategory = this.selectedCategory === null || product.categoryId === this.selectedCategory;
                        const haystack = [product.name, product.sku, product.category].filter(Boolean).join(' ').toLowerCase();
                        return matchesCategory && (!query || haystack.includes(query));
                    });
                },

                add(product) {
                    const existing = this.cart.find((line) => line.id === product.id);

                    if (existing) {
                        existing.qty += 1;
                        return;
                    }

                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        price: Number(product.price || 0),
                        unit: product.unit || 'piece',
                        qty: 1,
                    });
                },

                increase(id) {
                    const line = this.cart.find((item) => item.id === id);
                    if (line) line.qty += 1;
                },

                decrease(id) {
                    const line = this.cart.find((item) => item.id === id);
                    if (! line) return;
                    line.qty -= 1;
                    if (line.qty <= 0) this.remove(id);
                },

                remove(id) {
                    this.cart = this.cart.filter((item) => item.id !== id);
                },

                clearCart() {
                    this.cart = [];
                    this.customer = '';
                    this.payment = 'cash';
                },

                itemsCount() {
                    return this.cart.reduce((sum, line) => sum + line.qty, 0);
                },

                subtotal() {
                    return this.cart.reduce((sum, line) => sum + (line.price * line.qty), 0);
                },

                total() {
                    return this.subtotal();
                },

                money(value) {
                    return new Intl.NumberFormat('fr-MA', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }).format(Number(value || 0)) + ' MAD';
                },

                initials(name) {
                    return String(name || '?')
                        .split(' ')
                        .filter(Boolean)
                        .slice(0, 2)
                        .map((part) => part[0])
                        .join('')
                        .toUpperCase();
                },

                stockLabel(value) {
                    const stock = Number(value || 0);
                    return Number.isInteger(stock) ? String(stock) : stock.toFixed(2);
                },

                checkoutPreview() {
                    if (this.cart.length === 0) return;

                    // Trigger the browser print dialog
                    window.print();
                    
                    // Note: Here you can later add an API call to save the order to the database.
                },
            };
        }
    </script>
</div>
