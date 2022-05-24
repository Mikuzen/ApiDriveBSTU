<template>
    <div>
        <router-link :to="{ name: 'user.index' }">Пользователи</router-link>
        <router-link :to="{ name: 'user.create' }">Добавить пользователя</router-link>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "Index"
}
</script>

<style scoped>

</style>
