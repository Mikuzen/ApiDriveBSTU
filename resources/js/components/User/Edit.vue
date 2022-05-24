<template>
    <div class="w-25">
        <div class="mb-3">
            <input type="text" v-model="name" class="form-control" placeholder="Ваше имя">
        </div>
        <div class="mb-3">
            <input type="email" v-model="email" class="form-control" placeholder="Ваша эл. почта">
        </div>
        <div class="mb-3">
            <input type="radio" v-model="admin" value="0" name="admin" id="user">
            <label for="user">Пользователь</label>
            <input type="radio" value="1" v-model="admin" name="admin" id="admin">
            <label for="user">Администратор</label>
        </div>
        <div class="mb-3">
            <input type="submit" @click.prevent="update" value="Обновить данные пользователя" class="btn btn-primary">
        </div>
    </div>
</template>

<script>
import router from "../../router";

export default {
    name: "Edit",

    data() {
        return{
            name: null,
            email: null,
            admin: null,
        }
    },

    mounted() {
        this.getUser();
    },

    methods: {
        getUser() {
            axios.get('/api/V1/users/' + this.$route.params.user)
            .then(res => {
                this.name = res.data.data.name;
                this.email = res.data.data.email;
                this.admin = res.data.data.admin;
            })
        },

        update() {
            axios.patch('/api/V1/users/' + this.$route.params.user, {
                name: this.name,
                email: this.email,
                admin: this.admin,
            }).then(res => {
                router.push({ name: 'user.show', params:{ user: this.$route.params.user }})
            })
        },
    }
}
</script>

<style scoped>

</style>
