<template>
    <header>
        <div class="px-3 py-2 bg-dark text-white">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <h3 class="text-white">Панель управления</h3>
                    <ul class="nav col-12 col-lg-auto my-0 justify-content-center my-md-0 text-small">
                        <li class="mx-3">
                            <router-link :to="{ name: 'user.index' }" class=" link-dark">
                                <i class="bi bi-person text-white"></i>
                                <span class="text-white">Пользователи</span>
                            </router-link>
                        </li>
<!--                        <li class="mx-3">-->
<!--                            <router-link :to="{ name: 'user.index' }" class=" link-dark">-->
<!--                                <i class="bi-files text-white"></i>-->
<!--                                <span class="text-white">Файлы</span>-->
<!--                            </router-link>-->
<!--                        </li>-->

                    </ul>
                </div>
            </div>
        </div>
        <div>
            <router-view></router-view>
        </div>
    </header>

</template>

<script>
import router from "../router";

export default {
    name: "Index",

}
</script>

<style scoped>
</style>
