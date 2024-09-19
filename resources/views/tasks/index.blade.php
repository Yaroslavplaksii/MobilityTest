@extends('layouts.app')

@section('content')
<div>
    <h1 class="text-center">Завдання</h1>
    <div class="container">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tasks">
            Створити завдання
          </button>
        <div id="message_alert"></div>
        <table class="table table-responsive ">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Назва завдання</th>
                <th scope="col">Статус</th>
                <th scope="col">Дата створення</th>
                <th scope="col"></th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody id="all_tasks">            
            </tbody>
          </table>
    </div>
</div>
{{-- Modal delete --}}
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Видалення завдання</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Ви дійсно бажаєте видалити вибране завдання?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ні</button>
          <button type="button" id="confirm_delete" data-id="" class="btn btn-primary" data-bs-dismiss="modal">Так</button>
        </div>
      </div>
    </div>
  </div>
  {{-- End modal delete --}}

<!-- Modal create task -->
<div class="modal fade" id="tasks" tabindex="-1" aria-labelledby="tasksLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="tasksLabel">Додати завдання</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-12">                       
                <div class="mb-3">
                    <label for="title_task" class="form-label">Назва завдання</label>
                    <input id="title_task" type="text" placeholder="Назва завдання" class="form-control" >
                </div>
                <div class="mb-3">
                    <label for="description_task" class="form-label">Опис завдання</label>
                    <textarea id="description_task" class="form-control" placeholder="Опис завдання" rows="3"></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="status_task">
                    <label class="form-check-label" for="status_task">
                    завершено
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
        <button type="button" id="createTask" data-id="" class="btn btn-primary" data-bs-dismiss="modal">Створити завдання</button>
    </div>
    </div>
</div>
</div> 
{{-- End create taks --}}
{{--Edit task--}}
<div class="modal fade" id="editTasks" tabindex="-1" aria-labelledby="editTasksLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="tasksLabel">Редагувати завдання</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-12">                       
                    <div class="mb-3">
                        <input type="hidden" id="task_id" value="">
                        <label for="title_edit_task" class="form-label">Назва завдання</label>
                        <input id="title_edit_task" type="text" placeholder="Назва завдання" class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label for="description_edit_task" class="form-label">Опис завдання</label>
                        <textarea id="description_edit_task" class="form-control" placeholder="Опис завдання" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="status_edit_task">
                        <label class="form-check-label" for="status_edit_task">
                        завершено
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
            <button type="button" id="editTask" class="btn btn-primary" data-bs-dismiss="modal">Редагувати завдання</button>
        </div>
        </div>
    </div>
    </div> 
    {{-- End edit task --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {        
        let token = "{{$token}}";
        //create task
        $('#createTask').on('click', function(e) {
            $.ajax({
                url: '/api/tasks',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    title: $('#title_task').val(),
                    description: $('#description_task').val(),
                    status: $('#status_task').prop('checked') ? 1 : 0
                },
                success: function(response) {
                    getAllTasks();
                    messageInfo(response.msg, response.status);
                },
                error: function(xhr, status, error) {
                    messageInfo(error, status);
                }
            });
        });
        //edit task
        $('#editTask').on('click', function(e) {
            $.ajax({
                url: '/api/tasks/' + $('#task_id').val(),
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    title: $('#title_edit_task').val(),
                    description: $('#description_edit_task').val(),
                    status: $('#status_edit_task').prop('checked') ? 1 : 0
                },
                success: function(response) {
                    getAllTasks();
                    messageInfo(response.msg, response.status);
                },
                error: function(xhr, status, error) {
                    messageInfo(error, status);
                }
            });
        });
        //get all tasks
        function getAllTasks() {
            $.ajax({
                url: '/api/tasks',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    let html = ``;
                    if (response && response.data && response.data.length) {
                        let countTasks = response.data.length;
                        for (let i=0; i<countTasks; i++) {
                            html += `<tr>
                                        <th scope="row">${i+1}</th>
                                        <td>${response.data[i].title}</td>
                                        <td>${response.data[i].status}</td>
                                        <td>${response.data[i].created_at}</td>
                                        <td><button data-id="${response.data[i].id}" type="button" class="btn btn-primary edit_task_button" data-bs-toggle="modal" data-bs-target="#editTasks">детальніше</button></td>
                                        <td><button data-id="${response.data[i].id}" type="button" class="btn btn-danger delete_task_button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">видалити</button></td>
                                    </tr>`;
                        }
                    } else {
                        html = `<tr>
                                    <th colspan="5">Завдання відсутні</th>
                                </tr>`;
                    }
                   $('#all_tasks').html(html);
                },
                error: function(xhr, status, error) {
                    messageInfo(error, status);
                }
            });
        }
        getAllTasks();

        //get task for edit
        $(document).on('click', '.edit_task_button', function(e) {
            $.ajax({
                url: '/api/tasks/' + $(this).data('id'),
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                   $('#task_id').val(response.data.id);
                   $('#title_edit_task').val(response.data.title);
                   $('#description_edit_task').val(response.data.description);
                    if (response.data.statusCode  == 1) {
                        $('#status_edit_task').prop('checked', true);
                    } else {
                        $('#status_edit_task').prop('checked', false);
                    }
                },
                error: function(xhr, status, error) {
                    messageInfo(error, status);
                }
            });
        });
        //select task for delete
        $(document).on('click', '.delete_task_button', function(e) {
            $('#confirm_delete').attr('data-id', $(this).data('id'));
        });
       //delete task
        $('#confirm_delete').on('click', function(e) {
            $.ajax({
                url: '/api/tasks/' + $(this).data('id'),
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    getAllTasks();
                    messageInfo(response.msg, response.status);
                },
                error: function(xhr, status, error) {
                    messageInfo(error, status);
                }
            });
        });
        //message after action
        function messageInfo(msg, status) {
            let statusInfo = status == 200 ? 'success' : 'danger';
            let html = `<div class="alert alert-${statusInfo} alert-dismissible fade show" role="alert">
                            ${msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
            $('#message_alert').html(html);
            setTimeout(() => {
                $('#message_alert').html('');
            }, 3000);
        }
    });
</script>
@endsection