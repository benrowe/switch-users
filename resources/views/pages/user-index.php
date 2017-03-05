<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet">

    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">

</head>
<body>

    <div class="container">
        <div class="row">
            <table class="table" id="userTable">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
        $('#userTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "/user",
            "columnDefs": [
                { "name": "first_name", "targets": 0 },
                { "name": "last_name", "targets": 1 },
                { "name": "email", "targets": 2 },
                { "name": "role", "targets": 3 },
                { "name": "department", "targets": 4 },
                {
                    "targets": 5,
                    "render": function(data, type, row) {
                        return '<a href="#" data-id="'+row.DT_RowData.pkey+'">view</a>';
                    }
                    // "data":"name", "targets": 5
                }
            ]
        });
        $('#userTable tbody').on('click', 'tr a', function() {
            let id = $(this).data('id');
            $('#userModal').modal({remote: '/user/' + id });

        })
    });
    </script>
</body>
</html>
