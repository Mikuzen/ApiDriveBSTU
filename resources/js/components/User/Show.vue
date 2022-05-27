<template>
    <div>
        <h1><strong>Информация о пользователе</strong></h1>
        <div v-if="user">
            <p>
                <strong>Имя пользователя: </strong>
                {{ user.name}}
            </p>
            <p>
                <strong>Почта: </strong>
               {{ user.email}}
            </p>
            <p>
                <strong>Есть права администратора: </strong>
                {{ user.admin ? 'Да' : 'Нет'}}
            </p>
            <p>
                <strong>Дата создания: </strong>
                {{ user.created_at}}
            </p>
            <p>
                <router-link :to="{ name: 'user.edit', params: { user: user.id } }">
                    <input  class="btn btn-primary" value="Изменить данные пользователя" style="width: 250px">
                </router-link>

                <a href="#" @click.prevent="deleteUser(user.id)">
                    <input class="btn btn-danger" value="Удалить пользователя">
                </a>
            </p>
        </div>
        <h3><strong>Файлы пользователя</strong></h3>
        <files :initial-files="files"></files>
    </div>
</template>

<script>
import Files from './Files';
import router from "../../router";

export default {
    name: "Show",

    data() {
        return {
            user: null,
            files: null,
        }
    },

    mounted() {
        this.getUser()
    },

    methods: {
        getUser() {
            axios.get('/api/V1/users/' + this.$route.params.user)
                .then(res => {
                    this.user = res.data.data;
                    this.files = res.data.data.files
                })
        },

        deleteUser(user) {
            axios.delete(`/api/V1/users/` + user)
                .then(res => {
                    router.push('user.index')
                    alert('Пользователь был удален')
                })
        }
    },

    components: {
        Files
    }
}
</script>

<style scoped>
</style>
