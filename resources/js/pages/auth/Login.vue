<template>
    <div class="login-box">

        <!--        //Loading Spinner-->
        <div class="loader-container" v-show="loading">
            <atom-spinner
                :animation-duration="1000"
                :size="160"
                :color="'#DC3545'"
            />
        </div>

        <!-- /.login-logo -->
        <div class="card">

            <div class="card-body login-card-body">
                <div class="error text-lg-center text-danger" v-model="error">{{ error }}</div>
                <p class="login-box-msg">Sign in to start your session</p>
                <form>
                    <div class="error text-xs text-danger" v-if="!$v.email.required">Email is required</div>
                    <div class="error text-xs text-danger" v-if="!$v.email.email">Not an email</div>
                    <div class="input-group mb-3" :class="{ 'form-group--error': $v.email.$error }">
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

                    <div class="error text-xs text-danger" v-if="!$v.password.required">Password is required</div>
                    <div class="error text-xs text-danger" v-if="!$v.password.minLength">Password must have at least
                        {{ $v.password.$params.minLength.min }} characters.
                    </div>
                    <div class="input-group mb-3" :class="{ 'form-group--error': $v.password.$error }">
                        <input type="password"
                               class="form-control form__input"
                               placeholder="Password"
                               @input="setPassword($event.target.value)">
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
                            <button type="submit" class="btn btn-primary btn-block" @click.prevent="handleLogin">Sign
                                In
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
                <hr/>
                <p class="mb-1">
                    <!--                    <router-link :to="{}">I forgot my password</router-link>-->
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</template>

<script>

import {email, minLength, required} from 'vuelidate/lib/validators'
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import {AtomSpinner} from 'epic-spinners'

Vue.use(Vuelidate)

export default {
    name: "Login",
    data() {
        return {
            secrets: [],
            email: '',
            password: '',
            error: '',
            loading: false
        };
    },

    validations: {
        email: {
            required,
            email,
        },
        password: {
            minLength: minLength(8),
            required,
        }
    },

    components: {
        AtomSpinner
    },

    async mounted() {

    },

    methods: {
        setEmail(value) {
            this.email = value
            this.$v.email.$touch()
        },
        setPassword(value) {
            this.password = value
            this.$v.password.$touch()
        },
        handleLogin() {
            this.loading = true
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/login', {email: this.email, password: this.password}).then(response => {
                    window.location.href = "/"
                    // console.log(response)
                }).catch(error => {
                    console.log(error)
                    this.error = "Invalid username or password"
                }).finally(() => this.loading = false); // credentials didn't match
            });
        },

        async getSecrets() {
            await axios.get('/user').then(response => console.log(response));
        }
    },


}
</script>

<style scoped>
.loader-container {
    position: absolute;
    z-index: 99;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
}
</style>
