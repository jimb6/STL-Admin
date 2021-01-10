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
        <div>

            <div :class="{'shake': error}" class="card-body login-card-body" >
<!--                <v-icon class="cstm-icon-title">-->
<!--                    mdi-account-circle-->
<!--                </v-icon>-->
                <form>

                    <div class="mb-4">
                        <div class="input-group" :class="{ 'form-group--error': $v.username.$error }">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <v-icon class="text-white">
                                        mdi-account
                                    </v-icon>
                                </div>
                            </div>
                            <input type="text" class="form-control form__input"
                                   v-model.trim="username"
                                   @input="setUsername($event.target.value)"
                                   placeholder="Username" v-model="username">
                        </div>
                        <div class="error text-xs text-white-50 text-center mt-1" v-if="!$v.username.required && error != ''">Username is required</div>
                    </div>

                    <div class="mb-4">
                        <div class="input-group" :class="{ 'form-group--error': $v.password.$error }">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <v-icon class="text-white">
                                        mdi-key
                                    </v-icon>
                                </div>
                            </div>
                            <input type="password"
                                   class="form-control form__input"
                                   placeholder="Password"
                                   @input="setPassword($event.target.value)">
                        </div>
                        <div class="error text-xs text-white-50 text-center mt-1" v-if="!$v.password.required && error != ''">Password is required</div>
                        <div class="error text-xs text-white-50 text-center mt-1" v-if="!$v.password.minLength && error != ''">Password must have at least
                            {{ $v.password.$params.minLength.min }} characters.
                        </div>
                    </div>

                    <div :class="{'cstm-error': error}" class="error text-lg-center" v-model="error">{{ error }}</div>


                    <div class="row mt-5">
                        <div class="col-12">
                            <button type="submit" class="btn btn-block cstm-submit-btn" @click.prevent="handleLogin">Login</button>
                        </div>
                        <!-- /.col -->
                        <div class="col-12 mt-4">
                            <div class="icheck-primary text-white">
                                <input type="checkbox" id="remember">
                                <label for="remember" class="font-weight-light" v-model="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
                <!--                <hr/>-->
                <!--                <p class="mb-1">-->
                <!--                    &lt;!&ndash;                    <router-link :to="{}">I forgot my password</router-link>&ndash;&gt;-->
                <!--                </p>-->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</template>

<script>

import {minLength, required} from 'vuelidate/lib/validators'
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import { AtomSpinner } from 'epic-spinners'

Vue.use(Vuelidate)

export default {
    name: "Login",
    data() {
        return {
            secrets: [],
            username: '',
            password: '',
            error: '',
            loading: false,
            remember: false,
        };
    },

    validations: {
        username: {
            required,
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
        setUsername(value) {
            this.error = '';
            this.username = value
            this.$v.username.$touch()
        },
        setPassword(value) {
            this.error = '';
            this.password = value
            this.$v.password.$touch()
        },
        handleLogin() {
            this.loading = true
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('login', {username: this.username, password: this.password, remember: this.remember}).then(response => {
                    // window.location.href = "/"
                    console.log(response)
                }).catch(error => {
                    this.error = "Invalid username or password"
                }).finally(() => this.loading = false); // credentials didn't match
            }).catch(err => {
            });
        },

        async getSecrets() {
            await axios.get('/api/user').then(response => console.log(response));
        }
    },


}
</script>

<style>
.login-page #app {
    background: #00000033;
    width: 400px;
    padding: 40px !important;
    border-radius: 5px;
}
.login-page .login-logo {
    margin-bottom: 40px;
}
.login-box, .register-box {
    width: unset;
}
.loader-container {
    position: absolute;
    z-index: 99;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
}
.cstm-error {
    padding: 8px;
    border: 2px solid #EAF11B;
    color: #EAF11B;
    text-transform: uppercase;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 20px;
    transition-duration: 500ms;
}

/* CUSTOM LOGIN CSS */
body.login-page {
    background: rgb(29,53,87);
    background: linear-gradient(22deg, rgba(29,53,87,1) 0%, rgba(230,57,70,1) 100%);
}
.login-logo img {
    position: relative;
    top: -4px;
}
.login-logo a, .login-logo a b {
    color: #EAF11B;
    font-weight: 500;
}
.login-card-body {
    border-radius: 10px;
    padding: unset;
}
.login-card-body:nth-of-type(1) {
    background: unset;
}
.login-card-body:nth-of-type(2) {
    border-radius: 10px;
    background: unset;
    /*border: 1px solid rgba(255,255,255,.75);*/
}
.login-page .cstm-icon-title {
    font-size: 90px;
    color: #FFFFFF;
    display: block;
    margin: auto;
    width: fit-content;
    margin-bottom: 30px;
}
h3.login-box-msg {
    font-weight: 200;
    letter-spacing: 2px;
    color: #FFFFFF;
}
.input-group-text {
    border: 1px solid #ffffff !important;
    border-right: unset !important;
    border-radius: unset !important;
    border-top-left-radius: 5px !important;
    border-bottom-left-radius: 5px !important;
}
.login-page input.form-control.form__input {
    border: unset;
    background: unset;
    border-radius: unset;
    padding: 25px 25px 25px 56px !important;
    color: #fff;
    border-bottom: 1px solid #ffffff44;
    transition-duration: 400ms;
}
.login-page input.form-control.form__input:focus {
    border-bottom: 1px solid #ffffff99;
}
.input-group {
    position: relative;
}
.login-page .input-group-text {
    border: unset !important;
    padding-right: 20px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
}

.cstm-submit-btn {
    text-transform: uppercase;
    letter-spacing: 5px;
    font-weight: 300;
    background: var(--gray2);
    padding: 12px;
    border: none;
    transition-duration: 400ms;
    color: #fff;
}
.cstm-submit-btn:hover {
    background: var(--gray);
    color: #fff;
}
.shake {
    animation: shake 150ms 2 linear;
    -moz-animation: shake 150ms 2 linear;
    -webkit-animation: shake 150ms 2 linear;
    -o-animation: shake 150ms 2 linear;
}

@keyframes shake {
    0% {
        transform: translate(5px, 0);
    }
    50% {
        transform: translate(-5px, 0);
    }
    100% {
        transform: translate(0, 0);
    }
}

@-moz-keyframes shake {
    0% {
        -moz-transform: translate(5px, 0);
    }
    50% {
        -moz-transform: translate(-5px, 0);
    }
    100% {
        -moz-transform: translate(0, 0);
    }
}

@-webkit-keyframes shake {
    0% {
        -webkit-transform: translate(5px, 0);
    }
    50% {
        -webkit-transform: translate(-5px, 0);
    }
    100% {
        -webkit-transform: translate(0, 0);
    }
}

@-ms-keyframes shake {
    0% {
        -ms-transform: translate(5px, 0);
    }
    50% {
        -ms-transform: translate(-5px, 0);
    }
    100% {
        -ms-transform: translate(0, 0);
    }
}

@-o-keyframes shake {
    0% {
        -o-transform: translate(5px, 0);
    }
    50% {
        -o-transform: translate(-5px, 0);
    }
    100% {
        -o-transform: translate(0, 0);
    }
}
</style>
