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
                        <h1>{{ isset($city) ? 'Edit City' : 'Add City' }}</h1>
                        <form action="{{ isset($city) ? route('city.update', $city->id) : route('city.store') }}" method="POST">
                            @csrf
                            @if(isset($city))
                                @method('PUT')
                            @endif
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="{{ isset($city) ? $city->name : old('name') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="country_id">Country:</label>
                                <select id="country_id" name="country_id" class="form-control">
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{  isset($user) && $user->city && $city->country_id == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="latitude">Latitude:</label>
                                <input type="text" id="latitude" name="latitude" value="{{ isset($city) ? $city->latitude : old('latitude') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="longitude">Longitude:</label>
                                <input type="text" id="longitude" name="longitude" value="{{ isset($city) ? $city->longitude : old('longitude') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postal Code:</label>
                                <input type="text" id="postal_code" name="postal_code" value="{{ isset($city) ? $city->postal_code : old('postal_code') }}" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">{{ isset($city) ? 'Update' : 'Add' }}</button>
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