@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Main Card */

        .card-header {
            background: linear-gradient(135deg,
                    rgba(102, 126, 234, 0.05),
                    rgba(118, 75, 162, 0.05));
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            padding: 1.8rem 2rem;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        /* Filter & Search */
        .form-control,
        select {
            border-radius: 12px;
            border: 2px solid #e8ecf1;
            padding: 0.7rem 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus,
        select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        /* Buttons */

        .btn-success {
            background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 176, 155, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 176, 155, 0.4);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, #1e1212 0%, #0f3665 100%);
            border: none;
            color: white;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        }

        a {
            color: #0e1616;
        }


        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        /* Table Styles */
        .table-responsive {
            border-radius: 0;
        }

        .table {
            margin-bottom: 0;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        thead tr th {
            border: none !important;
            letter-spacing: 0.5px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            padding: 1.2rem 1rem;
            color: white !important;
        }

        tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr:hover {
            background: linear-gradient(135deg,
                    rgba(102, 126, 234, 0.05),
                    rgba(118, 75, 162, 0.05)) !important;
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fafbfc;
        }

        /* Badges */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #1e1212 0%, #b0a814 100%);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        .badge-info {
            background: linear-gradient(135deg, #22425f 0%, #0e1616 100%);
            color: white;
        }

        .badge-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .badge-light {
            background: #e8ecf1;
            color: #5a5a5a;
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 0.7rem 1.2rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg,
                    rgba(102, 126, 234, 0.1),
                    rgba(118, 75, 162, 0.1));
            color: #667eea;
            transform: translateX(5px);
        }

        /* Pagination */
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .pagination .page-link {
            border-radius: 10px;
            margin: 0 0.2rem;
            border: 2px solid #e8ecf1;
            color: #667eea;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg,
                    rgba(102, 126, 234, 0.1),
                    rgba(118, 75, 162, 0.1));
            border-color: #667eea;
            transform: translateY(-2px);
        }

        /* Modal */
        .modal-content {
            border-radius: 25px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.8rem 2rem;
            border: none;
        }

        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.4rem;
        }

        .modal-header .close {
            color: white;
            opacity: 0.9;
            text-shadow: none;
            font-size: 2rem;
            font-weight: 300;
        }

        .modal-header .close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-body h6 {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .list-group-item {
            border: none;
            border-bottom: 1px solid #f0f0f0;
            padding: 0.8rem 0;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .list-group-item strong {
            color: #667eea;
            font-weight: 600;
        }

        .modal-footer {
            background: #f8f9fa;
            border: none;
            padding: 1.5rem 2rem;
        }

        /* Empty State */
        .fa-box-open {
            color: #e8ecf1;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeIn 0.5s ease;
        }

        /* Loading Spinner */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-header h1 {
                font-size: 1.5rem;
            }

            .card-header {
                padding: 1.2rem;
            }

            .card-header-form {
                flex-direction: column;
                gap: 1rem;
            }

            .card-header-form .d-flex {
                flex-direction: column;
                width: 100%;
            }

            .card-header-form select,
            .card-header-form .input-group {
                width: 100% !important;
                margin: 0 !important;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Text Colors */
        .text-dark {
            color: #1a1a1a !important;
        }

        .font-weight-600 {
            font-weight: 600;
        }

        /* Small improvements */
        .text-small {
            font-size: 0.85rem;
        }

        .shadow-sm {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        }
    </style>
@endpush

<div>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Profile</div>
                </div>
            </div>
            <div class="section-body">
                {{-- Judul dinamis --}}
                <h2 class="section-title">Hi, {{ auth()->user()->name }}!</h2>
                <p class="section-lead">
                    Change information about yourself on this page.
                </p>

                <div class="row mt-sm-4">

                    {{-- KOLOM KIRI: WIDGET PROFIL (Dinamis) + GANTI PASSWORD --}}
                    <div class="col-12 col-md-12 col-lg-5">

                        {{-- WIDGET PROFIL (Sudah dinamis) --}}
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                {{-- Logika Avatar --}}
                                @if ($avatar)
                                    <img alt="image" src="{{ $avatar->temporaryUrl() }}"
                                        class="rounded-circle profile-widget-picture"
                                        style="object-fit: cover; width: 100px; height: 100px;">
                                @elseif ($existingAvatar)
                                    <img alt="image" src="{{ asset('storage/' . $existingAvatar) }}"
                                        class="rounded-circle profile-widget-picture"
                                        style="object-fit: cover; width: 100px; height: 100px;">
                                @else
                                    <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                                        class="rounded-circle profile-widget-picture"
                                        style="object-fit: cover; width: 100px; height: 100px;">
                                @endif

                                {{-- --- TAMBAHAN BLOK STATISTIK --- --}}
                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Role</div>
                                        <div class="profile-widget-item-value badge badge-primary text-uppercase">
                                            {{ auth()->user()->role }}
                                        </div>
                                    </div>
                                    @if(auth()->user()->role == 'karyawan')
                                        <div class="profile-widget-item">
                                            <div class="profile-widget-item-label">Handle POS</div>
                                            <div class="profile-widget-item-value">{{ $posOrdersHandled }}</div>
                                        </div>
                                    @endif
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Bergabung</div>
                                        <div class="profile-widget-item-value">
                                            {{ auth()->user()->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                                {{-- --- AKHIR BLOK STATISTIK --- --}}

                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">
                                    {{ auth()->user()->name }}
                                </div>

                                {{-- Tombol Upload Foto --}}
                                <div class="mt-3 text-center">
                                    <label for="avatar-upload" class="btn btn-sm btn-outline-primary"
                                        style="cursor: pointer;">
                                        <i class="fas fa-camera"></i> Change Photo
                                    </label>
                                    <input id="avatar-upload" type="file" wire:model="avatar" class="d-none"
                                        accept="image/*">

                                    @error('avatar') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Social Icons (Opsional) --}}
                            <div class="text-center card-footer">
                                <div class="mb-2 font-weight-bold">Follow {{ auth()->user()->name }} On</div>
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-github"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>

                        {{-- FORM GANTI PASSWORD (Dipindah ke bawah widget profil) --}}
                    </div>

                    {{-- KOLOM KANAN: FORM EDIT PROFIL (Dinamis) --}}
                    <div class="col-12 col-md-12 col-lg-7">
                        <div class="card">
                            <form wire:submit.prevent="updateInfo" class="needs-validation" novalidate="">
                                <div class="card-header">
                                    <h4>Edit Profile</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-12 col-12">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                wire:model="name" required>
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-7 col-12">
                                            <label>Email</label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                wire:model="email" required>
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="form-group col-md-5 col-12">
                                            <label>Phone</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                wire:model="phone">
                                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                wire:model="address" style="height: 100px;"></textarea>
                                            @error('address') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right card-footer">
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="updateInfo">Save Changes</span>
                                        <span wire:loading wire:target="updateInfo"><i
                                                class="fas fa-spinner fa-spin"></i> Saving...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>

    @push('js')
        <script>
            window.addEventListener('notify', event => {
                Swal.fire({
                    icon: event.detail.icon,
                    title: event.detail.icon === 'success' ? 'Success!' : 'Error!',
                    text: event.detail.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>
    @endpush


</div>
