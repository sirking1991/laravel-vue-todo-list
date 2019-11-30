<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">        

    </head>
    <body>
        <div class="container full-height full-width" style="padding-left: 0px;padding-right: 0px;">
            <ul class="list-group">
                <li class="list-group-item active">TODO List
                        <button class='btn btn-sm float-right btn-primary'
                         onClick='openModal()'
                        >+ New</button> 
                </li>
            </ul>
        </div>

        <div class="modal" id="todoModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-body">
                    <input type='text' class='form-control' name='description' placeholder='Walk the dog' />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onClick='save()'>Save</button>
                </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>        

        <script>
            var todoList = [];
            var todoItem;
            $(function(){
                loadList();
            });

            function loadList(){
                console.log('loading todo list');

                $.get('/todo', function(data){
                    todoList = data;
                    // clear existing todo-list
                    $('li.todo-item').remove();
                    // append todo-list
                    for(var x=0; x<todoList.length; x++) {
                        var todo = todoList[x];
                        $('ul').append(`<li class='list-group-item todo-item' data-id=${todo.id}>
                                            <span onClick='openModal(${x})'>${todo.description}</span>
                                            <button class='btn btn-sm float-right btn-primary' onClick='markDone(${todo.id})'>Done</button>
                                        </li>`);
                    }
                })
            }

            function save() {
                $('#todoModal').modal('hide');
                $.ajax({
                    method: undefined==todoItem.id ? 'post' : 'put',
                    url:  undefined==todoItem.id ? '/todo' : '/todo/' + todoItem.id,
                    data: {
                        id: todoItem.id,
                        description: $('#todoModal input[name=description').val()
                    },
                    complete: function(ret) {
                        console.log(ret);
                        loadList();
                    }
                });
            }

            function openModal(idx) {
                console.log('opening modal:'+idx);
                todoItem = {id:undefined, description:''};
                if (undefined!=idx) todoItem = todoList[idx];

                $('#todoModal input[name=description').val(todoItem.description);

                $('#todoModal').modal('show');
            }


            function markDone(id) {
                $.ajax({
                    method: 'put',
                    url:  '/todo/' + id + '/mark-done',
                    complete: function(ret) {
                        console.log(ret);
                        loadList();
                    }
                });
            }

        </script>

    </body>
</html>
