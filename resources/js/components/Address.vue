<template>
    <div>
        <v-select
            v-model="selectProvince"
            :items="provinces"
            item-text="provDesc"
            item-value="provCode"
            label="Province"
            return-object
            @change="getMunicipalities(selectProvince.provCode)"
        />
        <v-select
            v-model="selectMunicipality"
            :items="municipalities"
            item-text="citymunDesc"
            item-value="citymunCode"
            label="Municipality/City"
            return-object
            @change="getBarangays(selectMunicipality.citymunCode)"
        />
        <v-select
            v-model="selectBarangay"
            :items="barangays"
            item-text="brgyDesc"
            item-value="brgyCode"
            label="Barangay"
            return-object
            @change="streetDisabled = false"
        />
        <v-text-field
            v-model="selectStreet"
            label="Street"
            :disabled="streetDisabled"
            @change="changeAddress()"
        />
    </div>
</template>

<script>
export default {
    name: "Address",
    data: () => ({
        provinces: [],
        selectProvince: {id: 0, psgcCode: "", provDesc: "", regCode: "", provCode: ""},
        municipalities: [],
        selectMunicipality: {id: 0, psgcCode: "", citymunDesc: "", regDesc: "", provCode: "", citymunCode: ""},
        barangays: [],
        selectBarangay: {id: 0, brgyCode: "", brgyDesc: "", regCode: "", provCode: "", citymunCode: ""},
        selectStreet: '',
        streetDisabled: true
    }),
    created() {
        this.getProvinces();
    },
    methods: {
        async getProvinces() {
            const api_url = 'https://raw.githubusercontent.com/clavearnel/philippines-region-province-citymun-brgy/master/json/refprovince.json';
            const request = {
                method: 'GET',
            }
            const response = await fetch(api_url, request);
            const data = await response.json();
            this.provinces = data.RECORDS;
        },
        async getMunicipalities() {
            const api_url = 'https://raw.githubusercontent.com/clavearnel/philippines-region-province-citymun-brgy/master/json/refcitymun.json';
            const request = {
                method: 'GET',
            }
            const response = await fetch(api_url, request);
            const data = await response.json();
            this.municipalities = [];
            this.barangays = [];
            const municipalities = data.RECORDS;
            for (let item in municipalities) {
                if (municipalities[item].provCode == this.selectProvince.provCode) {
                    this.municipalities.push(municipalities[item]);
                }
            }
        },
        async getBarangays() {
            const api_url = 'https://raw.githubusercontent.com/clavearnel/philippines-region-province-citymun-brgy/master/json/refbrgy.json';
            const request = {
                method: 'GET',
            }
            const response = await fetch(api_url, request);
            const data = await response.json();
            this.barangays = [];
            const barangays = data.RECORDS;
            for (let item in barangays) {
                if (barangays[item].citymunCode == this.selectMunicipality.citymunCode) {
                    this.barangays.push(barangays[item]);
                }
            }
        },
        changeAddress() {
            let street = this.selectStreet.toUpperCase();
            let barangay = this.selectBarangay.brgyDesc.toUpperCase();
            let municipality = this.selectMunicipality.citymunDesc.toUpperCase();
            let province = this.selectProvince.provDesc.toUpperCase();
            let address = [street, barangay, municipality, province];
            this.$emit('changeAddress', address);
        },
        close() {
            this.selectProvince= {id: 0, psgcCode: "", provDesc: "", regCode: "", provCode: ""}
            this.selectMunicipality= {id: 0, psgcCode: "", citymunDesc: "", regDesc: "", provCode: "", citymunCode: ""}
            this.selectBarangay= {id: 0, brgyCode: "", brgyDesc: "", regCode: "", provCode: "", citymunCode: ""},
            this.selectStreet= ''
            this.streetDisabled= true
        }
    }
}
</script>

<style scoped>

</style>
