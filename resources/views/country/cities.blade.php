<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $country->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="row justify-content-end">
                        <x-export-list :href='"city.export"'></x-export-list>
                    </div>
                    <table id="city-table" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Code postal</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#city-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('country.cities.list', $country->id) }}",
                    columns: [
                        { data: 'id' },
                        { data: 'name',
                            render : function(data, type, row, full, meta){
                                var cityId = row.id;
                                var url = "{{ route('city.users.view', ':id') }}".replace(':id', cityId);
                                return `<a href="${url}"> ${data} </a>`;
                            }
                        },
                        { data: 'latitude' },
                        { data: 'longitude' },
                        { data: 'postal_code' },
                        {
                            data: null,
                            render: function (data, type, row) {

                                var cityId = row.id;
                                var url = "{{ route('city.edit', ':id') }}".replace(':id', cityId);
                                var archive = row.archived ? 'btn-success' : 'btn-danger';
                                var btn = row.archived ? 'Active' : 'Delete';
                                @if(auth()->user()->is_admin)
                                    return `
                                        <a class="btn btn-primary" href="${url}">Edit</a>
                                        <button class="btn ${archive}" onclick="deleteCity(${cityId})">${btn}</button>
                                    `;
                                @else 
                                    return '';
                                @endif
                            }
                        }
                    ],
                });
            });

            function deleteCity(id) {
                if (confirm("Are you sure you want to delete this city?")) {
                    var url = "{{ route('city.destroy', ':id') }}".replace(':id', id);
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('#city-table').DataTable().draw();
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            }
        </script>
    @endpush
</x-app-layout>