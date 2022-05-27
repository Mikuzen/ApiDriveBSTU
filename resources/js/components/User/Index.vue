<template>
    <div>
        <h1>Все пользователи Диск.БГТУ</h1>

        <router-link :to="{ name: 'user.create' }" class="btn btn-success m-2">Добавить пользователя</router-link>

        <table class="table table-hover text-center table-striped table-dark">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Имя пользователя</th>
                <th scope="col">Почта</th>
                <th scope="col">Является ли админом</th>
                <th scope="col">Пароль</th>
                <th scope="col"><i class="bi-pencil-square text-white"></i></th>
                <th scope="col"><i class="bi-trash-fill text-white"></i></th>
            </tr>
            </thead>
            <tbody>
            <template v-for="user in displayedUsers">
                <tr>
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.admin ? 'Да' : 'Нет' }}</td>
                    <td>{{ user.password }}</td>
                    <td>
                        <router-link :to="{ name: 'user.show', params: { user: user.id}}">
                            <i class="bi-pencil-square"></i>
                        </router-link>
                    </td>
                    <td>
                        <a href="#" @click.prevent="deleteUser(user.id)">
                            <i class="bi-trash-fill text-danger"></i>
                        </a>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
        <div class="w-50">
            <button type="button" class="btn btn-primary" :disabled="page == 1"
                    @click.prevent="page = 1">First
            </button>
            <button type="button" class="btn btn-primary" :disabled="page == 1"
                    @click.prevent="page--">Prev
            </button>
            <button type="button" class="btn btn-primary mx-1"
                    v-for="pageNumber in pages.slice(page-1, page+5)" @click.prevent="page = pageNumber">
                {{ pageNumber }}
            </button>
            <button type="button" class="btn btn-primary" :disabled="page > pages.length - 1"
                    @click.prevent="page++">Next
            </button>
            <button type="button" class="btn btn-primary" :disabled="page > pages.length - 1 "
                    @click.prevent="page = pages[pages.length - 1]">Last
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Index",

    data() {
        return {
            users: [],
            page: 1,
            perPage: 15,
            pages: [],
        }
    },

    computed: {
        displayedUsers() {
            return this.paginate(this.users)
        }
    },

    created() {
        this.getUsers()
    },

    watch: {
        users() {
            this.setPages()
        }
    },

    methods: {
        getUsers() {
            axios.get('/api/V1/users')
                .then(res => {
                    this.users = res.data.data
                })
        },

        deleteUser(user) {
            axios.delete(`/api/V1/users/` + user)
                .then(res => {
                    this.getUsers()
                })
        },

        setPages() {
            let countOfPages = Math.ceil(this.users.length / this.perPage)
            for (let i = 1; i <= countOfPages; i++) {
                this.pages.push(i)
            }
        },

        paginate(users) {
            let from = (this.page * this.perPage) - this.perPage
            let to = this.page * this.perPage

            return users.slice(from, to);
        }
    }
}
</script>

<style scoped>

</style>
