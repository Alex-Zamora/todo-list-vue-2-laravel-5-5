<template>
    <div class='row'>
        <h1>Todo List</h1>

        <form action="#" @submit.prevent="createTask()">
            <div class="form-group">
                <input v-model="task.title" type="text" class="form-control" autofocus placeholder="Title">
            </div>
            <div class="form-group">  
                <input v-model="task.body" type="text" class="form-control" autofocus placeholder="Body">
            </div>
            <button type="submit" class="btn btn-primary">Crear Tarea</button>
        </form>

        <div class="alert alert-danger" v-if="errors.length > 0">
            <ul>
                <li v-for="error in errors">{{error}}</li>
            </ul>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Title</th>
                    <th>Body</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="list.length === 0">¡No hay tareas!</tr>
                <tr v-for="(task, index) in list">
                    <td>{{ index + 1 }}</td>
                    <td>{{ task.title }}</td>
                    <td>{{ task.body }}</td>
                    <td>
                        <button class="btn btn-info" @click="editFormTask(task)">
                            Editar
                        </button>
                        <button class="btn btn-danger" @click="initDeleteModal(task)">
                            Eliminar
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <form method="POST" @submit.prevent="updateTask()">
            <div class="modal fade" id="edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                            <h4>Editar</h4>
                        </div>
                        <div class="modal-body">
                            <label for="task">Actualizar Tarea</label>

                            <div class="form-group">
                                <input v-model="update_task.title" type="text" class="form-control" autofocus placeholder="Title">
                            </div>
                            <div class="form-group">  
                                <input v-model="update_task.body" type="text" class="form-control" autofocus placeholder="Body">
                            </div>

                            <div class="alert alert-danger" v-if="errors.length > 0">
                                <ul>
                                    <li v-for="error in errors">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" value="Actualizar">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="modal fade" id="delete">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                        <h4>¿Deseas eliminar está tarea?</h4>
                    </div>
                    <div class="modal-body">
                        <label for="task">{{ delete_task.title }}</label>
                        <p>{{ delete_task.body }}</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" @click="closeModal()">Cancelar</button>
                        <button class="btn btn-danger" @click="deleteTask(delete_task.id)">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>


    </div><!-- row -->
</template>

<script>
    export default {
        data() {
            return {
                list: [], //List of the tasks
                task: {  //v-model connection to inputs create
                    title: '', 
                    body: ''
                },
                update_task: { //v-model connection to inputs update
                    title: '',
                    body: ''
                },
                delete_task: { //show data in the modal #delete
                    title: '',
                    body: ''
                },
                errors: [],
            };
        },
        
        created() {
            this.taskList();
        },
        
        methods: {
            closeModal() {
                $('#delete').modal('hide');
            },

            taskList() {
                axios.get('api/tasks').then((response) => {
                    this.list = response.data;
                });
            },
 
            createTask() {
                axios.post('api/tasks', { 
                    title: this.task.title,
                    body: this.task.body
                }).then(response => {
                    this.task = {};
                    this.taskList();

                }).catch((error) => {
                    this.errors = [];
                    if (error.response.data.errors) {
                        this.errors.push(error.response.data.errors.body[0]);
                        this.errors.push(error.response.data.errors.title[0]);
                    }
                });
            },
            
            editFormTask: function (task) {
                this.errors = []; 
                this.update_task.id = task.id;
                this.update_task.title = task.title;
                this.update_task.body = task.body;
                $('#edit').modal('show');
            },

            updateTask: function () {
                var url = '/api/tasks/' + this.update_task.id;
                axios.put(url, {
                    id: this.update_task.id,
                    title: this.update_task.title,
                    body: this.update_task.body,
                }).then((response) => {
                    this.taskList();
                    this.update_task = {};
                    $('#edit').modal('hide');
                }).catch(error => {
                    this.errors = []; 
                    if (error.response.data.errors.title){
                        this.errors.push(error.response.data.errors.title[0]);
                    }
                    if (error.response.data.errors.body){
                        this.errors.push(error.response.data.errors.body[0]);
                    }
                });
            },

            initDeleteModal(task) {
                this.delete_task.id = task.id;
                this.delete_task.title = task.title;
                this.delete_task.body = task.body;
                $('#delete').modal('show');  
            },

            deleteTask(id) {
                axios.delete('api/tasks/' + id).then((response) => {
                    this.taskList();
                    this.closeModal();
                }).catch((err) => console.error(err));
            },

            // deleteTask(id) {
            //     axios.delete('api/tasks/' + id).then((response) => {
            //         this.list.splice(this.id, 1);
            //         this.closeModal();
            //     }).catch((err) => console.error(err));
            // },

            
        }
    }
</script>

<style>
    table{
        margin-top: 30px;
    }
    .alert{
        margin-top: 20px;
    }
    ul{ padding-left: 0px; }
    li{ list-style: none; }
</style>