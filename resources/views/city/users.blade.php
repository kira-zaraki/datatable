<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $city->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="row justify-content-end">
                        @if(auth()->user()->is_admin)
                            <x-export-list :href='"user.export"'></x-export-list>
                        @endif
                    </div>
                    <table id="user-table" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
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
                $('#user-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('city.users.list', $city->id) }}",
                    columns: [
                        { data: 'id' },
                        { data: 'name'},
                        { data: 'email' },
                        {
                            data: null,
                            render: function (data, type, row) {

                                var userId = row.id;
                                var url = "{{ route('user.edit', ':id') }}".replace(':id', userId);
                                var archive = row.archived ? 'btn-success' : 'btn-danger';
                                var btn = row.archived ? 'Active' : 'Delete';
                                @if(auth()->user()->is_admin)
                                    return `
                                        <a class="btn btn-primary" href="${url}">Edit</a>
                                        <button class="btn ${archive}" onclick="deleteUser(${userId})">${btn}</button>
                                    `;
                                @else 
                                    return '';
                                @endif
                            }
                        }
                    ],
                });
            });

            function deleteUser(id) {
                if (confirm("Are you sure you want to delete this user?")) {
                    var url = "{{ route('user.destroy', ':id') }}".replace(':id', id);
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('#user-table').DataTable().draw();
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