<template>
    <div class="custom-content custom-content-with-margin nomargin  psr">
        <router-view></router-view>
    </div>
</template>
<script type="text/javascript">
    import { post, interceptors } from '../../helpers/api'

    export default {
        data(){
            return{
            }
        },
        created() {
            interceptors((err) => {
                if(err.response.status === 401) {
                    //登录状态消失
                    location.href='/dealer/login';
//                    Auth.remove()
//                    this.$router.push('/login')
                }

                if(err.response.status === 500) {
//                    Flash.setError(err.response.statusText)
                }

                if(err.response.status === 404) {
//                    this.$router.push('/dealer/prices/settlement/404')
                }
            })
        },
    }
</script>
