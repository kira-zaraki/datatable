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
                    <div class="row justify-content-end">
                        <x-export-list :href='"country.export"'></x-export-list>
                    </div>
                    <table id="country-table" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
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
            $(document).ready(function(){
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
                        { data: 'code' },
                        {
                            data: null,
                            render: function (data, type, row) {

                                var countryId = row.id;
                                var url = "{{ route('country.edit', ':id') }}".replace(':id', countryId);
                                var archive = row.archived ? 'btn-success' : 'btn-danger';
                                var btn = row.archived ? 'Active' : 'Delete';
                                @if(auth()->user()->is_admin)
                                    return `
                                        <a class="btn btn-primary" href="${url}">Edit</a>
                                        <button class="btn ${archive}" onclick="deleteCountry(${countryId})">${btn}</button>
                                    `;
                                @else 
                                    return '';
                                @endif
                            }
                        }
                    ]
                });

        });

        function deleteCountry(id) {
            if (confirm("Are you sure you want to delete this country?")) {
                var url = "{{ route('country.destroy', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#country-table').DataTable().draw();
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