@extends('layouts.app', ['title' => '📦 Kelola Produk'])

@section('content')

<style>
    .header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:1rem;
    }

    .header h2{
        font-size:1.25rem;
        color:#0f172a;
    }

    .btn-group{
        display:flex;
        gap:0.5rem;
    }

    table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
        background:white;
        border:1px solid #e5e7eb;
        border-radius:0.75rem;
        overflow:hidden;
    }

    thead{
        background:#f8fafc;
    }

    th{
        text-align:left;
        padding:0.9rem;
        font-size:0.75rem;
        text-transform:uppercase;
        color:#64748b;
        border-bottom:1px solid #e5e7eb;
    }

    td{
        padding:0.9rem;
        border-bottom:1px solid #f1f5f9;
        font-size:0.9rem;
        color:#0f172a;
    }

    tr:hover{
        background:#f9fafb;
    }

    .badge{
        display:inline-block;
        padding:0.25rem 0.6rem;
        border-radius:999px;
        font-size:0.75rem;
        background:#eef2ff;
        color:#3730a3;
        font-weight:600;
    }

    .price{
        color:#16a34a;
        font-weight:700;
    }

    .btn{
        border:none;
        padding:0.4rem 0.6rem;
        border-radius:0.4rem;
        font-size:0.75rem;
        cursor:pointer;
        text-decoration:none;
        display:inline-block;
        transition:.2s;
        font-weight:600;
    }

    .btn-primary{
        background:#3b82f6;
        color:white;
    }

    .btn-primary:hover{
        background:#2563eb;
    }

    .btn-secondary{
        background:#64748b;
        color:white;
    }

    .btn-secondary:hover{
        background:#475569;
    }

    .btn-danger{
        background:#ef4444;
        color:white;
    }

    .btn-danger:hover{
        background:#dc2626;
    }

    .action-group{
        display:flex;
        gap:0.4rem;
        flex-wrap:wrap;
    }
    
    /* RESPONSIVE */
    @media(max-width:768px){
        .header{
            flex-direction:column;
            align-items:flex-start;
            gap:1rem;
        }
        
        .header h2{
            font-size:1.1rem;
        }
        
        .btn-group{
            width:100%;
            gap:0.4rem;
        }
        
        .btn-group .btn{
            flex:1;
            text-align:center;
            padding:0.5rem;
        }
        
        table{
            font-size:0.8rem;
        }
        
        th, td{
            padding:0.6rem;
        }
        
        .badge{
            padding:0.2rem 0.5rem;
            font-size:0.65rem;
        }
        
        .action-group{
            flex-direction:column;
            gap:0.3rem;
        }
        
        .btn{
            padding:0.3rem 0.5rem;
            font-size:0.7rem;
            width:100%;
            text-align:center;
        }
    }
    
    @media(max-width:480px){
        .header h2{
            font-size:1rem;
        }
        
        .btn-group{
            flex-direction:column;
        }
        
        .btn-group .btn{
            width:100%;
        }
        
        table{
            font-size:0.7rem;
        }
        
        th, td{
            padding:0.4rem;
        }
        
        .badge{
            padding:0.15rem 0.4rem;
            font-size:0.6rem;
        }
        
        .btn{
            padding:0.25rem 0.4rem;
            font-size:0.65rem;
        }
    }

</style>

<div class="card">

    <div class="header">
        <h2>📦 Kelola Produk</h2>

        <div class="btn-group">
            <a href="/admin/products/create" class="btn btn-primary">+ Tambah</a>
            <a href="/admin/products/stock/history" class="btn btn-secondary">📜 History</a>
        </div>
    </div>

    <div style="overflow-x:auto; -webkit-overflow-scrolling: touch;">

        <table>

            <thead>
                <tr>
                    <th>Nama</th>
                    <th>SKU</th>
                    <th>Harga</th>
                    <th>Stock</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($products as $p)

                <tr>

                    <td style="font-weight:600;">
                        {{ $p->name }}
                    </td>

                    <td>
                        <span class="badge">{{ $p->sku }}</span>
                    </td>

                    <td class="price">
                        Rp {{ number_format($p->price) }} / pcs
                    </td>

                    <td>
                        {{ $p->stock }}
                    </td>

                    <td>
                        <div class="action-group">

                            <a href="/admin/products/{{ $p->id }}/edit"
                               class="btn btn-primary">
                                Edit
                            </a>

                            <a href="/admin/products/{{ $p->id }}/stock-history"
                               class="btn btn-secondary">
                                History
                            </a>

                            <form method="POST"
                                  action="/admin/products/{{ $p->id }}"
                                  onsubmit="return confirm('Hapus produk ini?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger">
                                    Hapus
                                </button>

                            </form>

                        </div>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection