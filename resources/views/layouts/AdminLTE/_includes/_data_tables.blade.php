@section('layout_js')


    <script>
        $(function () {
            const table = $('#tabelapadrao').DataTable({
                "order": [[0, "desc"]],
                responsive: true,

                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

                //"dom": 'Bfrtip',
                //"buttons": ['pageLength', 'copy', 'excel', 'pdf', 'colvis',],
            });
        });
    </script>
    @yield('in_data_table')
@endsection
