<template>
    <div class="full">
        <div class="cstm-title-cont">
            <h2>Welcome to<br>Small Town Lottery</h2>
            <a class="cstm-btn-link" href="#about">About STL <i class="fas fa-long-arrow-alt-down"></i></a>
        </div>
        <div id="about" class="darkBg">
            <div class="w-50 text-center py-5">
                <h3>About STL</h3>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            </div>
            <img class="img-fit-50 center" :src="backgroundVector">
        </div>
        <div class="float-bg">
            <img :src="backgroundUrl">
        </div>
    </div>
</template>

<script>
export default {
    name: "DefaultDashboard",
    props: {
        backgroundUrl: String,
        backgroundVector: String
    },
}
</script>

<style scoped>
.cstm-title-cont {
    padding: 100px;
    height: 100vh;
}
.cstm-title-cont a.cstm-btn-link{
    margin-top: 50px !important;
    display: block;
    border: unset;
    background: var(--myWarning);
    width: fit-content;
    padding: 15px 40px;
    border-radius: 5px;
    text-transform: uppercase;
    color: #ffffff;
    letter-spacing: 1px;
    font-weight: 300;
    padding-right: 21px;
    transition-duration: 500ms;
}
.cstm-title-cont a.cstm-btn-link i {
    opacity: 0;
    transition-duration: 500ms;
    margin-left: 10px;
}
.cstm-title-cont a.cstm-btn-link:hover {
    padding-right: 40px;
}
.cstm-title-cont a.cstm-btn-link:hover i {
    opacity: 1;
}
h2 {
    text-align: left;
    max-width: 50%;
    color: var(--gray2);
    font-weight: 200;
    font-size: 60px;
    line-height: 1.5em;
    position: relative;
}
.float-bg {
    position: absolute;
    width: 50%;
    height: 100vh;
    top: -20%;
    right: -15%;
    transform: rotate3d(124, 72, -56, 45deg);
    background: var(--myBlue);
    border-radius: 15%;
    box-shadow: 0 0 50px 3px rgba(0,0,0,.15);
}
.float-bg img {
    position: absolute;
    width: 70%;
    bottom: 0;
    left: -30%;
}
img.img-fit-50.center{
    width: 50%;
    margin: auto;
    display: block;
}
.darkBg {
    background: var(--gray2);
    padding: 50px !important;
    color: #fff;
    height: 100vh;
}
.darkBg h3 {
    line-height: 2em;
    padding-bottom: 20px;
}
.darkBg p {
    font-weight: 200;
    line-height: 2em;
}
.darkBg img {
    margin-top: 30px !important;
}
.w-50 {
    width: 50%;
    margin: auto;
    display: block;
}
.cstm-row {
    padding: 40px 0;
}
</style>
