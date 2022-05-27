<template>
    <div>

        <div class="w-25 m-auto mt-3 text-center" v-if="user">
            <h3><strong>Изменение данных о пользователе</strong></h3>
            <div class="mb-3">
                <label for="name">Имя пользователя</label>
                <input type="text" v-model="user.name" id="name" class="form-control" placeholder="Ваше имя">
            </div>
            <div class="mb-3">
                <label for="email">Почта </label>
                <input type="email" v-model="user.email" id="email" class="form-control" placeholder="Ваша эл. почта">
            </div>
            <div class="mb-3" v-if="user.id != 1">
                <input type="radio" v-model="user.admin" value="0" name="admin" id="user">
                <label for="user">Пользователь</label>
                <input type="radio" value="1" v-model="user.admin" name="admin" id="admin">
                <label for="user">Администратор</label>
            </div>
            <div class="mb-3">
                <input type="submit" @click.prevent="update" value="Обновить данные пользователя"
                       class="btn btn-primary">
            </div>
        </div>
    </div>
</template>

<script>
import router from "../../router";

export default {
    name: "Edit",

    data() {
        return {
            user: null
        }
    },

    mounted() {
        this.getUser();
    },

    methods: {
        getUser() {
            axios.get('/api/V1/users/' + this.$route.params.user)
                .then(res => {
                    this.user = res.data.data;
                })
        },

        update() {
            axios.patch('/api/V1/users/' + this.$route.params.user, {
                name: this.user.name,
                email: this.user.email,
                admin: this.user.admin,
            }).then(res => {
                router.push({name: 'user.show', params: {user: this.$route.params.user}})
            })
        },
    }
}
</script>

<style scoped>

</style>
