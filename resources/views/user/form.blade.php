<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <h1>{{ isset($user) ? 'Edit User' : 'Add User' }}</h1>
                        <form action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}" method="POST">
                            @csrf
                            @if(isset($user))
                                @method('PUT')
                            @endif
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="{{ isset($user) ? $user->name : old('name') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" value="{{ isset($user) ? $user->email : old('email') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="city_id">City:</label>
                                <select id="city_id" name="city_id" class="form-control">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ isset($user) && $user->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Add' }}</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#country-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('country.list') }}",
                    columns: [
                        { data: 'id' },
                        { data: 'name',
                            render : function(data, type, row, full, meta){
                                var countryId = row.id;
                                var url = "{{ route('country.cities.view', ':id') }}".replace(':id', countryId);
                                return `<a href="${url}"> ${data} </a>`;
                            }
                        },
                        { data: 'code' }
                    ],
                });
            });
        </script>
    @endpush
</x-app-layout>