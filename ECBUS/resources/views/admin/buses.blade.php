@extends('layouts.admin')

@section('title', 'Manage Buses & Layouts')
@section('header', 'Manage Buses & Seat Layouts')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
    <ul class="list-disc list-inside font-bold text-sm">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div x-data="busManager()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Add New Bus Form with Layout Builder -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6" x-data="layoutBuilder()">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">Add New Bus</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.buses.store') }}" method="POST" id="add-bus-form" @submit.prevent="submitForm">
                    @csrf
                    
                    <input type="hidden" name="seat_layout" id="seat_layout_input">


                    <input type="hidden" name="total_seats" :value="countSeats()">


                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Bus Type</label>
                        <input type="text" name="bus_type_name" value="{{ old('bus_type_name') }}" placeholder="e.g. Normal, AC, Sleeper" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required autocomplete="off">
                        @error('bus_type_name') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Bus Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Royal Cruiser" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Bus Number</label>
                            <input type="text" name="bus_number" value="{{ old('bus_number') }}" placeholder="e.g. B-001" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Reg Number</label>
                            <input type="text" name="registration_number" value="{{ old('registration_number') }}" placeholder="e.g. ND-1234" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="font-extrabold text-sm text-dark-text mb-4"><i data-lucide="layout-grid" class="w-4 h-4 inline mr-1 text-primary-maroon"></i> Seat Layout Builder</h4>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Rows</label>
                                <input type="number" x-model.number="rows" min="1" max="20" @change="generateMap()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-primary-maroon outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Columns</label>
                                <input type="number" x-model.number="cols" min="1" max="10" @change="generateMap()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-primary-maroon outline-none">
                            </div>
                        </div>

                        <p class="text-[10px] text-gray-500 mb-4 font-bold uppercase tracking-wider text-center">Click cells to change type</p>
                        
                        <!-- Mini Legend -->
                        <div class="flex justify-center space-x-3 mb-6 text-[10px] font-bold text-gray-600">
                            <div class="flex items-center"><div class="w-3 h-3 bg-blue-100 border border-blue-300 rounded mr-1"></div> Window</div>
                            <div class="flex items-center"><div class="w-3 h-3 bg-white border border-gray-300 rounded mr-1"></div> Aisle Seat</div>
                            <div class="flex items-center"><div class="w-3 h-3 bg-gray-100 mr-1 rounded"></div> Empty</div>
                        </div>

                        <!-- Visual Builder Grid -->
                        <div class="overflow-x-auto pb-4">
                            <div class="inline-block min-w-full bg-gray-50 p-4 rounded-xl border-2 border-gray-200">
                                <div class="flex flex-col space-y-2 items-center">
                                    <template x-for="(row, rIndex) in map" :key="rIndex">
                                        <div class="flex space-x-2">
                                            <template x-for="(cell, cIndex) in row" :key="cIndex">
                                                <button type="button" @click="cycleCell(rIndex, cIndex)"
                                                    :class="{
                                                        'bg-gray-100 border-transparent': cell === 'empty',
                                                        'bg-white border-gray-300 shadow-sm': cell === 'seat',
                                                        'bg-blue-50 border-blue-300 text-blue-700 shadow-sm': cell === 'window'
                                                    }"
                                                    class="w-8 h-8 rounded border flex items-center justify-center text-[10px] font-bold transition-colors">
                                                    <span x-show="cell === 'window'">W</span>
                                                    <span x-show="cell === 'seat'">S</span>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 bg-gray-50 rounded-lg p-3 text-center border border-gray-200">
                            <span class="text-xs text-gray-500 font-bold">Total Seats Calculated:</span>
                            <span class="text-lg font-extrabold text-dark-maroon ml-2" x-text="countSeats()"></span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary-maroon text-white font-bold rounded-lg px-4 py-3 hover:bg-dark-maroon transition shadow-md">
                        <i data-lucide="save" class="w-5 h-5 inline-block mr-1"></i> Save Bus & Layout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Buses List -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">All Buses</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Name / Reg</th>
                            <th class="px-6 py-4">Total Seats</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($buses as $bus)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-500">{{ $bus->id }}</td>
                            <td class="px-6 py-4 font-bold text-gray-700">{{ $bus->busType->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-dark-text">{{ $bus->name }}</div>
                                <div class="text-xs text-gray-500">{{ $bus->registration_number }}</div>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-primary-maroon">{{ $bus->total_seats }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" class="inline-flex gap-2" onsubmit="return confirm('Are you sure you want to delete this bus?');">
                                    <button type="button" @click='viewBus({{ $bus->id }}, @json($bus->name), @json($bus->busType->name ?? "N/A"), @json($bus->registration_number), {{ $bus->total_seats }}, @json($bus->seat_layout))' class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition" title="View Bus">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <button type="button" @click='editBus({{ $bus->id }}, @json($bus->name), @json($bus->busType->name ?? "N/A"), @json($bus->registration_number), @json($bus->bus_number), {{ $bus->total_seats }}, @json($bus->seat_layout))' class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Edit Bus">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete Bus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No buses found. Add one on the left!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View/Edit Bus Modal -->
    <div x-show="isViewing" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
         style="display: none;"
         x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
             @click.away="isViewing = false">
            <div class="flex justify-between items-center p-6 border-b border-gray-100 bg-gray-50/50 sticky top-0 z-10">
                <h3 class="font-extrabold text-xl text-dark-text" x-text="isEditMode ? 'Edit Bus' : viewingBus.name"></h3>
                <button @click="isViewing = false" class="text-gray-400 hover:text-red-500 transition bg-white p-2 rounded-full shadow-sm">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-6">
                <!-- VIEW MODE -->
                <div x-show="!isEditMode">
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Type</p>
                            <p class="font-bold text-dark-text" x-text="viewingBus.type"></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Reg No</p>
                            <p class="font-bold text-dark-text" x-text="viewingBus.reg"></p>
                        </div>
                        <div class="bg-primary-maroon/10 p-4 rounded-xl border border-primary-maroon/20">
                            <p class="text-xs text-primary-maroon font-bold uppercase mb-1">Total Seats</p>
                            <p class="font-extrabold text-primary-maroon text-lg" x-text="viewingBus.seats"></p>
                        </div>
                    </div>

                    <h4 class="font-bold text-gray-700 mb-4 border-b pb-2">Seat Layout</h4>
                    
                    <div class="bg-gray-100 p-8 rounded-2xl border border-gray-200 overflow-x-auto flex justify-center">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 inline-block">
                            <template x-for="(row, rowIndex) in viewingBus.layout.map" :key="rowIndex">
                                <div class="flex gap-3 mb-3 justify-center">
                                    <template x-for="(seat, colIndex) in row" :key="colIndex">
                                        <div class="w-12 h-12 flex items-center justify-center rounded-lg border-2 text-xs font-bold transition-all"
                                             :class="{
                                                'bg-blue-50 border-blue-200 text-blue-600 shadow-sm': seat === 'window',
                                                'bg-gray-50 border-gray-200 text-gray-600 shadow-sm': seat === 'seat',
                                                'border-transparent bg-transparent': seat === 'empty'
                                             }">
                                            <span x-show="seat !== 'empty'" x-text="getSeatLabel(rowIndex, colIndex)"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-center gap-6">
                                <div class="flex items-center text-xs text-gray-500 font-medium">
                                    <div class="w-4 h-4 bg-blue-50 border-2 border-blue-200 rounded mr-2"></div> Window
                                </div>
                                <div class="flex items-center text-xs text-gray-500 font-medium">
                                    <div class="w-4 h-4 bg-gray-50 border-2 border-gray-200 rounded mr-2"></div> Aisle Seat
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT MODE -->
                <div x-show="isEditMode" x-data="editLayoutBuilder()">
                    <form :action="`{{ url('/admin/buses') }}/${viewingBus.id}`" method="POST" @submit.prevent="submitEditForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="seat_layout" id="edit_seat_layout_input">
                        <input type="hidden" name="total_seats" :value="countSeats()">

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Bus Type</label>
                            <input type="text" name="bus_type_name" x-model="editForm.bus_type_name" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required autocomplete="off">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Bus Name</label>
                            <input type="text" name="name" x-model="editForm.name" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Bus Number</label>
                                <input type="text" name="bus_number" x-model="editForm.bus_number" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Reg Number</label>
                                <input type="text" name="registration_number" x-model="editForm.registration_number" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                        </div>

                        <!-- Edit Layout Builder -->
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h4 class="font-extrabold text-sm text-dark-text mb-4"><i data-lucide="layout-grid" class="w-4 h-4 inline mr-1 text-primary-maroon"></i> Edit Seat Layout</h4>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Rows</label>
                                    <input type="number" x-model.number="rows" min="1" max="20" @change="generateMap()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-primary-maroon outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Columns</label>
                                    <input type="number" x-model.number="cols" min="1" max="10" @change="generateMap()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-primary-maroon outline-none">
                                </div>
                            </div>

                            <p class="text-[10px] text-gray-500 mb-4 font-bold uppercase tracking-wider text-center">Click cells to change type</p>
                            
                            <!-- Visual Builder Grid -->
                            <div class="overflow-x-auto pb-4">
                                <div class="inline-block min-w-full bg-gray-50 p-4 rounded-xl border-2 border-gray-200">
                                    <div class="flex flex-col space-y-2 items-center">
                                        <template x-for="(row, rIndex) in map" :key="rIndex">
                                            <div class="flex space-x-2">
                                                <template x-for="(cell, cIndex) in row" :key="cIndex">
                                                    <button type="button" @click="cycleCell(rIndex, cIndex)"
                                                        :class="{
                                                            'bg-gray-100 border-transparent': cell === 'empty',
                                                            'bg-white border-gray-300 shadow-sm': cell === 'seat',
                                                            'bg-blue-50 border-blue-300 text-blue-700 shadow-sm': cell === 'window'
                                                        }"
                                                        class="w-8 h-8 rounded border flex items-center justify-center text-[10px] font-bold transition-colors">
                                                        <span x-show="cell === 'window'">W</span>
                                                        <span x-show="cell === 'seat'">S</span>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 bg-gray-50 rounded-lg p-3 text-center border border-gray-200">
                                <span class="text-xs text-gray-500 font-bold">Total Seats Calculated:</span>
                                <span class="text-lg font-extrabold text-dark-maroon ml-2" x-text="countSeats()"></span>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse border-t border-gray-100 mt-6 rounded-b-xl -mx-6 -mb-6">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-maroon text-base font-medium text-white hover:bg-dark-maroon sm:ml-3 sm:w-auto sm:text-sm transition">
                                Save Changes
                            </button>
                            <button type="button" @click="isViewing = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('busManager', () => ({
        isViewing: false,
        isEditMode: false,
        viewingBus: {
            id: null, name: '', type: '', reg: '', seats: 0, layout: { map: [] }
        },
        editForm: {
            bus_type_name: '', name: '', bus_number: '', registration_number: ''
        },
        viewBus(id, name, type, reg, seats, layout) {
            this.isEditMode = false;
            this.viewingBus = { id, name, type, reg, seats, layout: typeof layout === 'string' ? JSON.parse(layout) : layout };
            this.isViewing = true;
        },
        editBus(id, name, type, reg, bus_number, seats, layout) {
            this.isEditMode = true;
            this.viewingBus = { id, name, type, reg, seats, layout: typeof layout === 'string' ? JSON.parse(layout) : layout };
            this.editForm = {
                bus_type_name: type,
                name: name,
                bus_number: bus_number,
                registration_number: reg
            };
            this.isViewing = true;
        },
        getSeatLabel(r, c) {
            if (!this.isViewing || !this.viewingBus.layout || !this.viewingBus.layout.map) return '';
            let count = 0;
            for (let i = 0; i <= r; i++) {
                for (let j = 0; j < (i === r ? c + 1 : this.viewingBus.layout.map[0]?.length || 0); j++) {
                    if (this.viewingBus.layout.map[i] && this.viewingBus.layout.map[i][j] !== 'empty') {
                        count++;
                    }
                }
            }
            const seat = this.viewingBus.layout.map[r][c];
            if (seat === 'window') return 'W' + count;
            if (seat === 'seat') return 'S' + count;
            return '';
        }
    }));

    Alpine.data('layoutBuilder', () => ({
        rows: 10,
        cols: 5,
        map: [],

        init() {
            this.generateMap();
        },

        generateMap() {
            let newMap = [];
            for (let r = 0; r < this.rows; r++) {
                let row = [];
                for (let c = 0; c < this.cols; c++) {
                    // Try to preserve existing cell type if resizing
                    if (this.map[r] && this.map[r][c]) {
                        row.push(this.map[r][c]);
                    } else {
                        // Default intelligent layout for 5 cols: Window, Seat, Empty, Seat, Window
                        if (c === 0 || c === this.cols - 1) row.push('window');
                        else if (c === Math.floor(this.cols / 2)) row.push('empty');
                        else row.push('seat');
                    }
                }
                newMap.push(row);
            }
            this.map = newMap;
        },

        cycleCell(r, c) {
            const current = this.map[r][c];
            if (current === 'empty') this.map[r][c] = 'seat';
            else if (current === 'seat') this.map[r][c] = 'window';
            else this.map[r][c] = 'empty';
        },

        countSeats() {
            let count = 0;
            for (let r = 0; r < this.rows; r++) {
                for (let c = 0; c < this.cols; c++) {
                    if (this.map[r][c] === 'window' || this.map[r][c] === 'seat') {
                        count++;
                    }
                }
            }
            return count;
        },

        submitForm(e) {
            if (this.countSeats() === 0) {
                alert("Please add at least 1 seat to the layout.");
                return;
            }
            
            const layoutData = {
                rows: this.rows,
                cols: this.cols,
                map: this.map
            };
            
            document.getElementById('seat_layout_input').value = JSON.stringify(layoutData);
            e.target.submit();
        }
    }));
    Alpine.data('editLayoutBuilder', () => ({
        rows: 1,
        cols: 1,
        map: [],

        init() {
            // Watch for when viewingBus changes
            this.$watch('viewingBus', (val) => {
                if (val && val.layout) {
                    this.rows = val.layout.rows || 1;
                    this.cols = val.layout.cols || 1;
                    // deep copy the map to avoid mutating the original
                    this.map = JSON.parse(JSON.stringify(val.layout.map || []));
                }
            });
        },

        generateMap() {
            let newMap = [];
            for (let r = 0; r < this.rows; r++) {
                let row = [];
                for (let c = 0; c < this.cols; c++) {
                    if (this.map[r] && this.map[r][c]) {
                        row.push(this.map[r][c]);
                    } else {
                        if (c === 0 || c === this.cols - 1) row.push('window');
                        else if (c === Math.floor(this.cols / 2)) row.push('empty');
                        else row.push('seat');
                    }
                }
                newMap.push(row);
            }
            this.map = newMap;
        },

        cycleCell(r, c) {
            const current = this.map[r][c];
            if (current === 'empty') this.map[r][c] = 'seat';
            else if (current === 'seat') this.map[r][c] = 'window';
            else this.map[r][c] = 'empty';
        },

        countSeats() {
            let count = 0;
            for (let r = 0; r < this.rows; r++) {
                for (let c = 0; c < this.cols; c++) {
                    if (this.map[r] && this.map[r][c] !== 'empty') {
                        count++;
                    }
                }
            }
            return count;
        },

        submitEditForm(e) {
            if (this.countSeats() === 0) {
                alert("Please add at least 1 seat to the layout.");
                return;
            }
            
            const layoutData = {
                rows: this.rows,
                cols: this.cols,
                map: this.map
            };
            
            document.getElementById('edit_seat_layout_input').value = JSON.stringify(layoutData);
            e.target.submit();
        }
    }));
});
</script>
@endpush
