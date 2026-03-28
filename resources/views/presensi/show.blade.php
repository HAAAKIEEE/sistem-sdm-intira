<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Detail Presensi
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $user->name }} — {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                </p>
            </div>
            <div class="flex gap-3">
                {{-- Tombol Export --}}
                <button type="button" onclick="document.getElementById('modalExportUser').classList.remove('hidden')"
                    class="flex items-center gap-2 px-4 py-2 text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <span class="hidden sm:inline">Export Excel</span>
                </button>

                {{-- Tombol Kembali --}}
                <a href="{{ route('presensi.index') }}"
                    class="flex items-center gap-2 px-4 py-2 text-white transition bg-gray-500 rounded-lg hover:bg-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Success --}}
            @if (session('success'))
                <div class="p-4 mb-6 text-green-700 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info Card --}}
            <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <p class="text-sm text-gray-500">Karyawan</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Cabang</p>
                        @if ($branch)
                            <p class="mt-1 font-semibold text-gray-800">{{ $branch->name }}</p>
                            <p class="text-xs text-gray-500">{{ $branch->address ?? '-' }} ·
                                {{ $branch->timezone ?? '-' }}</p>
                        @else
                            <p class="mt-1 text-gray-400">Tidak ada cabang</p>
                        @endif
                    </div>
                    <div>
                        <form method="GET" class="flex gap-2">
                            <input type="date" name="tanggal" value="{{ $tanggal }}"
                                class="flex-1 px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-teal-500">
                            <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                                Ganti
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="mb-6 text-lg font-semibold text-gray-800">
                        Rincian Presensi
                    </h3>

                    {{-- Desktop Table --}}
                    <div class="hidden overflow-x-auto md:block">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-medium text-left text-gray-600 uppercase">Status
                                    </th>
                                    <th class="px-4 py-3 text-xs font-medium text-left text-gray-600 uppercase">Jam
                                    </th>
                                    <th class="px-4 py-3 text-xs font-medium text-left text-gray-600 uppercase">Wilayah
                                    </th>
                                    <th class="px-4 py-3 text-xs font-medium text-left text-gray-600 uppercase">
                                        Keterangan</th>
                                    <th class="px-4 py-3 text-xs font-medium text-left text-gray-600 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($rows as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-800">
                                            {{ str_replace('_', ' ', $row['status']) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($row['jam'])
                                                <span class="px-2 py-1 text-sm text-blue-700 rounded bg-blue-50">
                                                    {{ $row['jam'] }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($row['wilayah'])
                                                <span class="px-2 py-1 text-xs text-gray-700 bg-gray-100 rounded">
                                                    {{ $row['wilayah'] }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $row['keterangan'] ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($row['id'])
                                                <div class="relative inline-block text-left">
                                                    <button type="button"
                                                        onclick="toggleDropdown({{ $row['id'] ?? rand(1000, 9999) }})"
                                                        class="text-gray-400 hover:text-gray-600">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                    </button>

                                                    <div id="dropdown-{{ $row['id'] ?? rand(1000, 9999) }}"
                                                        class="fixed z-50 hidden w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                                        <div class="py-1">

                                                            @if ($row['photo'])
                                                                <button
                                                                    onclick="openPhoto('{{ Storage::url($row['photo']) }}', '{{ str_replace('_', ' ', $row['status']) }}', '{{ $row['jam'] ? \Carbon\Carbon::parse($row['jam'])->format('H:i') : '-' }}')"
                                                                    class="flex items-center w-full gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    Lihat Foto
                                                                </button>
                                                            @endif

                                                            <button
                                                                onclick="openEditModal('{{ $row['status'] }}', '{{ $row['jam'] }}', '{{ $row['wilayah'] }}', '{{ $row['keterangan'] }}')"
                                                                class="flex items-center w-full gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                Edit
                                                            </button>

                                                            @if ($row['id'])
                                                                <form
                                                                    action="{{ route('presensi.destroy', $row['id']) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        onclick="return confirm('Yakin hapus data {{ str_replace('_', ' ', $row['status']) }}?')"
                                                                        class="flex items-center w-full gap-2 px-4 py-2 text-sm text-left text-red-600 hover:bg-gray-100">
                                                                        <svg class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <button
                                                    onclick="openEditModal('{{ $row['status'] }}', '{{ $row['jam'] }}', '{{ $row['wilayah'] }}', '{{ $row['keterangan'] }}')"
                                                    class="text-sm font-medium text-teal-600 hover:text-teal-800">
                                                    Tambah
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="space-y-4 md:hidden">
                        @foreach ($rows as $row)
                            <div class="p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-gray-800">
                                        {{ str_replace('_', ' ', $row['status']) }}
                                    </h4>
                                    <div class="flex gap-2">
                                        @if ($row['photo'])
                                            <button
                                                onclick="openPhoto('{{ Storage::url($row['photo']) }}', '{{ str_replace('_', ' ', $row['status']) }}', '{{ $row['jam'] ? \Carbon\Carbon::parse($row['jam'])->format('H:i') : '-' }}')"
                                                class="px-3 py-1 text-xs text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                                                Lihat Foto
                                            </button>
                                        @endif
                                        <button
                                            onclick="openEditModal('{{ $row['status'] }}', '{{ $row['jam'] }}', '{{ $row['wilayah'] }}', '{{ $row['keterangan'] }}')"
                                            class="px-3 py-1 text-xs text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                                            Edit
                                        </button>
                                        @if ($row['id'])
                                            <form action="{{ route('presensi.destroy', $row['id']) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 text-xs text-white bg-red-500 rounded-lg hover:bg-red-600">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Jam:</span>
                                        @if ($row['jam'])
                                            <span class="px-2 py-1 font-medium text-blue-700 rounded bg-blue-50">
                                                {{ $row['jam'] }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Wilayah:</span>
                                        @if ($row['wilayah'])
                                            <span class="px-2 py-1 text-xs text-gray-700 bg-gray-100 rounded">
                                                {{ $row['wilayah'] }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Keterangan:</span>
                                        <span class="text-gray-800 text-right max-w-[60%]">
                                            {{ $row['keterangan'] ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- =====================================================
         MODAL EXPORT USER
    ====================================================== --}}
    <div id="modalExportUser"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm">
        <div class="w-full max-w-md p-6 mx-4 bg-white shadow-2xl rounded-2xl">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Export Presensi</h3>
                    <p class="text-sm text-gray-500">{{ $user->name }}</p>
                </div>
                <button type="button" onclick="document.getElementById('modalExportUser').classList.add('hidden')"
                    class="text-gray-400 transition hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('presensi.export.user', $user->id) }}">
                @csrf

                {{-- Shortcut range --}}
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <button type="button" onclick="setExportRange('week')"
                        class="text-xs py-1.5 px-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-600">
                        Minggu Ini
                    </button>
                    <button type="button" onclick="setExportRange('month')"
                        class="text-xs py-1.5 px-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-600">
                        Bulan Ini
                    </button>
                    <button type="button" onclick="setExportRange('lastmonth')"
                        class="text-xs py-1.5 px-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-600">
                        Bulan Lalu
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="userExportStart"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            value="{{ now()->startOfMonth()->toDateString() }}" max="{{ now()->toDateString() }}"
                            required>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="userExportEnd"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" required>
                    </div>
                </div>

                <p class="mb-4 text-xs text-gray-400">
                    <svg class="inline w-3 h-3 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Maksimal range 31 hari. Termasuk kolom foto (Ada/Tidak Ada) dan potongan gaji.
                </p>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 rounded-lg transition text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Excel
                </button>
            </form>

        </div>
    </div>

    {{-- =====================================================
         MODAL EDIT
    ====================================================== --}}
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 bg-black bg-opacity-50"
        onclick="if(event.target === this) closeEditModal()">
        <div class="w-full max-w-md bg-white rounded-lg shadow-xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Edit Presensi</h3>
                <button onclick="closeEditModal()"
                    class="p-1 text-gray-400 rounded-lg hover:text-gray-600 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('presensi.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="status" id="editStatus">

                    <div class="p-3 rounded-lg bg-gray-50">
                        <p class="text-sm text-gray-600">Status:</p>
                        <p id="editStatusDisplay" class="font-semibold text-gray-800"></p>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Jam <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="jam" id="editJam" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea name="keterangan" id="editKeterangan" rows="3" placeholder="Tambahkan keterangan (opsional)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Foto --}}
    <div id="photoModal" class="fixed inset-0 z-50 items-center justify-center hidden p-4 bg-black bg-opacity-80"
        onclick="if(event.target===this) closePhoto()">
        <div class="relative w-full max-w-sm overflow-hidden bg-white shadow-2xl rounded-xl"
            onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-gray-50">
                <div>
                    <p class="text-sm font-semibold text-gray-800" id="photoModalStatus"></p>
                    <p class="text-xs text-gray-500" id="photoModalTime"></p>
                </div>
                <button onclick="closePhoto()"
                    class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <img id="photoModalImg" src="" alt="Foto Absensi" class="object-cover w-full">
        </div>
    </div>

    <script>
        // =====================================================
        // EDIT MODAL
        // =====================================================
        function openEditModal(status, jam, wilayah, keterangan) {
            document.getElementById('editStatus').value = status;
            document.getElementById('editStatusDisplay').textContent = status.replace(/_/g, ' ');
            document.getElementById('editJam').value = jam || '';
            document.getElementById('editKeterangan').value = keterangan || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // =====================================================
        // FOTO MODAL
        // =====================================================
        function openPhoto(src, status, time) {
            document.getElementById('photoModalImg').src = src;
            document.getElementById('photoModalStatus').textContent = status;
            document.getElementById('photoModalTime').textContent = 'Jam ' + time;
            const modal = document.getElementById('photoModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePhoto() {
            const modal = document.getElementById('photoModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('photoModalImg').src = '';
        }

        // =====================================================
        // EXPORT USER - SHORTCUT RANGE
        // =====================================================
        function setExportRange(type) {
            const today = new Date();
            const fmt = d => d.toISOString().split('T')[0];
            let start, end;

            if (type === 'week') {
                const day = today.getDay();
                const diff = (day === 0) ? -6 : 1 - day;
                start = new Date(today);
                start.setDate(today.getDate() + diff);
                end = today;
            } else if (type === 'month') {
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end = today;
            } else if (type === 'lastmonth') {
                start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                end = new Date(today.getFullYear(), today.getMonth(), 0);
            }

            document.getElementById('userExportStart').value = fmt(start);
            document.getElementById('userExportEnd').value = fmt(end);
        }

        // =====================================================
        // DROPDOWN
        // =====================================================
        function toggleDropdown(id) {
            event.stopPropagation();
            const button = event.currentTarget;
            const dropdown = document.getElementById(`dropdown-${id}`);

            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (el !== dropdown) el.classList.add('hidden');
            });

            dropdown.classList.toggle('hidden');

            if (!dropdown.classList.contains('hidden')) {
                const rect = button.getBoundingClientRect();
                dropdown.style.top = `${rect.bottom + 8}px`;
                dropdown.style.left = `${rect.right - dropdown.offsetWidth}px`;
            }
        }

        // =====================================================
        // KEYBOARD SHORTCUTS
        // =====================================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
                closePhoto();
                document.getElementById('modalExportUser').classList.add('hidden');
            }
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
        });
    </script>

</x-app-layout>
