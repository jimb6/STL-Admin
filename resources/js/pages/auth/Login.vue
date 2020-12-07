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

            <div :class="{'shake': error}" class="card-body login-card-body" >
                <h3 class="login-box-msg">LOGIN</h3>
                <form>

                    <div class="mb-4">
                        <div class="input-group" :class="{ 'form-group--error': $v.email.$error }">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope text-white"></span>
                                </div>
                            </div>
                            <input type="email" class="form-control form__input"
                                   v-model.trim="email"
                                   @input="setEmail($event.target.value)"
                                   placeholder="Email" v-model="email">
                        </div>
                        <div class="error text-xs text-white-50 text-center mt-1" v-if="!$v.email.required && error != ''">Email is required</div>
                        <div class="error text-xs text-white-50 text-center mt-1" v-if="!$v.email.email && error != ''">Not an email</div>
                    </div>

                    <div class="mb-4">
                        <div class="input-group" :class="{ 'form-group--error': $v.password.$error }">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock text-white"></span>
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
                                <label for="remember" class="font-weight-light">
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
            this.error = '';
            this.email = value
            this.$v.email.$touch()
        },
        setPassword(value) {
            this.error = '';
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

<style>
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
}
.login-card-body:nth-of-type(1) {
    background: unset;
}
.login-card-body:nth-of-type(2) {
     border-radius: 10px;
     background: unset;
     border: 1px solid rgba(255,255,255,.75);;
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
}
.cstm-submit-btn {
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 300;
    background: #1d3557;
    border: none;
    padding: 10px;
    transition-duration: 400ms;
    color: #fff;
}
.cstm-submit-btn:hover {
    background: #457b9d;
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
