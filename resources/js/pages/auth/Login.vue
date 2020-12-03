<template>
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form>
                    <div class="input-group mb-3" >

                        <input type="email" class="form-control form__input"
                               v-model.trim="email"
                               @input="setEmail($event.target.value)"
                               placeholder="Email" v-model="email">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="error" v-if="email.required">Field is required</div>
                    <div class="error" v-if="email.email">Not an email</div>
                    <div class="input-group mb-3">
                        <input type="password"
                               class="form-control form__input"
                               placeholder="Password"
                               @change="setPassword($event.target.value)">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block" >Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
                <hr/>
                <p class="mb-1">
                    <router-link :to="{}">I forgot my password</router-link>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</template>

<script>

import { required, minLength, email } from 'vuelidate/lib/validators'

export default {
    name: "Login",
    data(){
        return {
            email: '',
            password: '',
        };
    },

    validations:{
        // return {
            email: {
                required,
                email,
            },
            password: {
                minLength: minLength(8),
                required,
            }
        // };
    },

    async mounted(){

    },
    methods: {
        setEmail(value){
            this.email = value
            // this.$v.email.$touch()
        },
        setPassword(value){
            this.password = value
            // this.$v.password.$touch()
        }
    },



}
</script>

<style scoped>

</style>
