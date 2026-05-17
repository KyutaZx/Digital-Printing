<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order['order_code'] ?? '#'.$id }} - Jaya Mandiri</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #334155; }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            print: none;
        }
        .topbar a {
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar a:hover { color: #0f172a; }
        .topbar-actions { display: flex; gap: 10px; }
        .btn-print {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            color: #475569;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-print:hover { background: #f8fafc; }
        .btn-download {
            background: #2563eb;
            border: none;
            color: #fff;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-download:hover { background: #1d4ed8; }

        .page-wrapper { max-width: 860px; margin: 32px auto; padding: 0 20px 60px; }

        .invoice-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 8px 32px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        /* Header */
        .invoice-header {
            padding: 40px 48px 32px;
            border-bottom: 1px solid #f1f5f9;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 36px;
        }
        .logo-box {
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logo-icon {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, #2563eb, #10b981);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: 14px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .invoice-title .subtitle {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* Address blocks */
        .address-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        .address-box {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px 24px;
        }
        .address-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .address-tag {
            font-size: 11px;
            font-weight: 600;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }
        .address-name {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .address-detail {
            font-size: 13px;
            color: #64748b;
            line-height: 1.7;
        }

        /* Meta row */
        .meta-row {
            padding: 20px 48px;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            gap: 48px;
        }
        .meta-item label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #94a3b8;
            display: block;
            margin-bottom: 3px;
        }
        .meta-item span {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }
        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            background: #dcfce7;
            color: #16a34a;
        }
        .badge-status.pending { background: #fef9c3; color: #b45309; }
        .badge-status.cancelled { background: #fee2e2; color: #dc2626; }
        .badge-status.printing, .badge-status.production { background: #dbeafe; color: #2563eb; }
        .badge-status.ready { background: #f0fdf4; color: #15803d; }

        /* Table */
        .table-section { padding: 0 48px 32px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }
        thead tr {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #64748b;
        }
        thead th:not(:first-child) { text-align: right; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:last-child { border-bottom: none; }
        tbody td { padding: 16px; font-size: 14px; color: #334155; vertical-align: top; }
        tbody td:not(:first-child) { text-align: right; }
        .item-name { font-weight: 600; color: #0f172a; margin-bottom: 3px; }
        .item-note { font-size: 12px; color: #94a3b8; }
        .item-variant { font-size: 12px; color: #2563eb; font-weight: 500; }

        /* Summary */
        .summary-section {
            display: flex;
            justify-content: flex-end;
            padding: 0 48px 40px;
        }
        .summary-box {
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 20px 28px;
            min-width: 280px;
        }
        .summary-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #64748b;
            margin-bottom: 14px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .summary-row label { color: #64748b; }
        .summary-row span { font-weight: 600; color: #0f172a; }
        .summary-divider { border: none; border-top: 1px solid #e2e8f0; margin: 12px 0; }
        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        /* Footer */
        .invoice-footer {
            border-top: 1px solid #f1f5f9;
            padding: 20px 48px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }

        @media print {
            body { background: #fff; }
            .topbar { display: none; }
            .page-wrapper { margin: 0; padding: 0; }
            .invoice-card { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>

{{-- Top Action Bar --}}
<div class="topbar" id="topbar">
    <a href="{{ route('orders.show', $id) }}">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Detail Pesanan
    </a>
    <div class="topbar-actions">
        <button class="btn-print" onclick="window.print()">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak
        </button>
        <button class="btn-download" onclick="downloadInvoice()">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Unduh Invoice (PDF)
        </button>
    </div>
</div>

<div class="page-wrapper">
    <div class="invoice-card">

        {{-- Header --}}
        <div class="invoice-header">
            <div class="header-top">
                <div class="logo-box">
                    <div class="logo-icon">J</div>
                    Jaya Mandiri
                </div>
                <div class="invoice-title">
                    <div class="subtitle">Digital Printing</div>
                    <h1>INVOICE</h1>
                </div>
            </div>

            <div class="address-row">
                {{-- From --}}
                <div class="address-box">
                    <div class="address-label">From</div>
                    <div class="address-tag">Detail Toko</div>
                    <div class="address-name">Jaya Mandiri</div>
                    <div class="address-detail">
                        Digital Printing & Percetakan<br>
                        Jl. Percetakan No. 1, Kota<br>
                        Indonesia<br>
                        admin@jayamandiri.com
                    </div>
                </div>
                {{-- To --}}
                <div class="address-box">
                    <div class="address-label">To</div>
                    <div class="address-tag" style="color:#10b981;">Detail Pelanggan</div>
                    <div class="address-name">{{ $order['customer_name'] ?? session('user.name', 'Customer') }}</div>
                    <div class="address-detail">
                        {{ $order['customer_email'] ?? session('user.email', '-') }}<br>
                        {{ $order['customer_phone'] ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Meta --}}
        <div class="meta-row">
            <div class="meta-item">
                <label>No. Invoice</label>
                <span>{{ $order['order_code'] ?? 'INV-'.$id }}</span>
            </div>
            <div class="meta-item">
                <label>Tanggal Order</label>
                <span>{{ \Carbon\Carbon::parse($order['created_at'] ?? now())->locale('id')->isoFormat('D MMMM YYYY') }}</span>
            </div>
            <div class="meta-item">
                <label>Status</label>
                @php
                    $status = $order['status'] ?? 'waiting_payment';
                    $statusLabels = [
                        'waiting_payment'       => ['Menunggu Pembayaran', 'pending'],
                        'payment_verification'  => ['Verifikasi Pembayaran', 'pending'],
                        'paid'                  => ['Pembayaran Lunas', ''],
                        'production'            => ['Dalam Produksi', 'production'],
                        'printing'              => ['Sedang Cetak', 'printing'],
                        'ready'                 => ['Siap Diambil', 'ready'],
                        'completed'             => ['Selesai', ''],
                        'cancelled'             => ['Dibatalkan', 'cancelled'],
                    ];
                    $label = $statusLabels[$status][0] ?? $status;
                    $cls   = $statusLabels[$status][1] ?? '';
                @endphp
                <span class="badge-status {{ $cls }}">{{ $label }}</span>
            </div>
            @if(!empty($order['payment_method']))
            <div class="meta-item">
                <label>Metode Bayar</label>
                <span>{{ $order['payment_method'] }}</span>
            </div>
            @endif
        </div>

        {{-- Items Table --}}
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th style="width:45%">Item / Produk</th>
                        <th>Qty</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp
                    @forelse($order['items'] ?? [] as $item)
                    @php $subtotal += ($item['unit_price'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1); @endphp
                    <tr>
                        <td>
                            <div class="item-name">{{ $item['product_name'] ?? 'Produk' }}</div>
                            @if(!empty($item['variant_name']))
                                <div class="item-variant">Varian: {{ $item['variant_name'] }}</div>
                            @endif
                            @if(!empty($item['notes']))
                                <div class="item-note">{{ $item['notes'] }}</div>
                            @endif
                        </td>
                        <td>{{ $item['quantity'] ?? 1 }}</td>
                        <td>Rp {{ number_format($item['unit_price'] ?? $item['price'] ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format(($item['unit_price'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#94a3b8; padding: 32px;">Tidak ada item</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Summary --}}
        <div class="summary-section">
            <div class="summary-box">
                <div class="summary-title">Ringkasan Invoice</div>
                <div class="summary-row">
                    <label>Subtotal</label>
                    <span>Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <label>Biaya Layanan</label>
                    <span>Gratis</span>
                </div>
                <hr class="summary-divider">
                <div class="summary-total">
                    <span>Total</span>
                    <span>Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="invoice-footer">
            Terima kasih telah mempercayai <strong>Jaya Mandiri</strong> untuk kebutuhan cetak Anda. &nbsp;|&nbsp;
            Invoice diterbitkan otomatis oleh sistem.
        </div>

    </div>
</div>

<script>
    function downloadInvoice() {
        // Trigger browser print as PDF
        var topbar = document.getElementById('topbar');
        topbar.style.display = 'none';
        window.print();
        topbar.style.display = 'flex';
    }
</script>

</body>
</html>
