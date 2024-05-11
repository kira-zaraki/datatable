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
                        <h1>{{ isset($country) ? 'Edit Country' : 'Add Country' }}</h1>
                        <form action="{{ isset($country) ? route('country.update', $country->id) : route('country.store') }}" method="POST">
                            @csrf
                            @if(isset($country))
                                @method('PUT')
                            @endif
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="{{ isset($country) ? $country->name : old('name') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="code">Code:</label>
                                <input type="text" id="code" name="code" value="{{ isset($country) ? $country->code : old('code') }}" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">{{ isset($country) ? 'Update' : 'Add' }}</button>
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