@extends('layouts.app')

@section('content')


{{--    card--}}
<div class="container">
    <div class="card">
        <div class="card-header">
            Employee <a href="#" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Employee</a>
        </div>
        <div class="card-body">
            {{--    table--}}
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Image</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($emp as $empl)

                    <tr>

                        <th>{{$empl->id}}</th>
                        <td>{{$empl->name}}</td>
                        <td>{{$empl->phone}}</td>
                        <td><img height="50px" width="50px" src="http://127.0.0.1:8000/uploads/employee/{{$empl->image}}" /></td>
                        <td>
                            <button value="{{$empl->id}}" class="edit_btn btn btn-secondary">Edit</button>
                            <button value="{{$empl->id}}" class="delete_btn btn btn-danger">Delete</button>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Add Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="AddEmployeeForm" enctype="multipart/form-data">

            <div class="modal-body">

                    <ul class="alert-danger d-none" id="saveError"></ul>
                    <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control mt-3">
                    <input type="number" id="phone" name="phone" placeholder="Enter Phone" class="form-control mt-3">
                    <input type="file" id="image" name="image" placeholder="Upload Image" class="form-control mt-3">

{{--                    <button type="submit" class="form-control">Add Employee</button>--}}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>

        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="UpdateEmployeeForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="EditEmployeeForm" enctype="multipart/form-data">

                <div class="modal-body">

                    <ul class="alert-danger d-none" id="saveErrorEdit"></ul>
                    <input type="text" id="id" name="id"  class="form-control mt-3">
                    <input type="text" id="edit_name" name="name" placeholder="Enter Name" class="form-control mt-3">
                    <input type="number" id="edit_phone" name="phone" placeholder="Enter Phone" class="form-control mt-3">
                    <input type="file" id="image" name="image" placeholder="Upload Image" class="form-control mt-3">

                    {{--                    <button type="submit" class="form-control">Add Employee</button>--}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="DeleteEmployeeForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="DeleteForm" enctype="multipart/form-data">

                <div class="modal-body">

                    <ul class="alert-danger d-none" id="saveErrorEdit"></ul>

                    <h4>Are you sure you want to delete!</h4>
                    <input type="text" id="delete_id" name="delete_id"  class="form-control mt-3">


                    {{--                    <button type="submit" class="form-control">Add Employee</button>--}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="delete_employee_btn btn btn-primary">Delete</button>
                </div>
            </form>

        </div>
    </div>
</div>






@endsection


@section('script')

    <script>
    $(document).ready(function () {

        // add employee
        $(document).on('submit','#AddEmployeeForm', function (e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            e.preventDefault();

            let formData = new FormData($("#AddEmployeeForm")[0]);



            $.ajax({
                type: "POST",
                url: "/employee",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {

                    if(response.status == 400){

                        $("#saveError").html("");
                        $("#saveError").removeClass("d-none");

                        $.each(response.errors, function (key, value) {

                            $("#saveError").append('<li>' + value + '</li>')

                        } );

                    }else if(response.status == 200){
                        $("#saveError").html("");
                        $("#saveError").addClass("d-none");
                        // this.reset();
                        $("#AddEmployeeForm").find("input").val('');
                        $("#exampleModal").modal("hide");
                        // alert(response.message);
                        window.location.reload(false)
                    }
                }
            });
        });

        // edit employee
        $(document).on('click','.edit_btn', function (e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            e.preventDefault();

            // let formData = new FormData($("#AddEmployeeForm")[0]);

            var emp_id = $(this).val();
            alert(emp_id)
            $("#UpdateEmployeeForm").modal("show");


            $.ajax({
                type: "GET",
                url: "/edit/"+emp_id,
                contentType: false,
                processData: false,
                success: function (response) {

                    if(response.status == 400){



                        // $("#saveError").html("");
                        // $("#saveError").removeClass("d-none");
                        //
                        // $.each(response.errors, function (key, value) {
                        //
                        //     $("#saveError").append('<li>' + value + '</li>')
                        //
                        // } );
                        alert(response.message);
                        $("#UpdateEmployeeForm").modal("hide");


                    }else if(response.status == 200){
                        // alert(response.employee.name);

                        $("#id").val(response.employee.id);
                        $("#edit_name").val(response.employee.name);
                        $("#edit_phone").val(response.employee.phone);

                    }
                }
            });
        });

        // update employee
        $(document).on('submit','#EditEmployeeForm', function (e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            e.preventDefault();

            var id = $("#id").val();
            let EditformData = new FormData($("#EditEmployeeForm")[0]);

            alert(EditformData);
            console.log(EditformData);

            $.ajax({
                type: "POST",
                url: "/update/"+id,
                data: EditformData,
                contentType: false,
                processData: false,
                success: function (response) {

                    if(response.status == 400){

                        $("#saveErrorEdit").html("");
                        $("#saveErrorEdit").removeClass("d-none");

                        $.each(response.errors, function (key, value) {

                            $("#saveErrorEdit").append('<li>' + value + '</li>')

                        } );

                    }else if(response.status == 200){
                        $("#saveErrorEdit").html("");
                        $("#saveErrorEdit").addClass("d-none");
                        // this.reset();
                        $("#EditEmployeeForm").find("input").val('');
                        $("#UpdateEmployee").modal("hide");
                        // alert(response.message);
                        window.location.reload(false)
                    }
                }
            });
        });

        // Delete employee modal
        $(document).on('click','.delete_btn', function (e) {
            e.preventDefault();

            var emp_id = $(this).val();
            alert(emp_id)
            $("#DeleteEmployeeForm").modal("show");
            $("#delete_id").val(emp_id);
        });

        //Delete Employee
        $(document).on('click','.delete_employee_btn', function (e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            e.preventDefault();

            var emp_id = $("#delete_id").val();


            $.ajax({
                type: "POST",
                url: "/delete/"+emp_id,
                contentType: false,
                processData: false,
                success: function (response) {

                    if(response.status == 400){

                        alert(response.message);



                    }else if(response.status == 200){
                        alert(response.message);
                        $("#DeleteEmployeeForm").modal("hide");
                        window.location.reload(false)
                    }
                }
            });
        });
    });
    </script>

@endsection
