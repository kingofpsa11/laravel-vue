<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Định mức</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">Định mức - <span class="fw-semi-bold">Xem</span></h2>
    <b-form>
      <b-row>
        <b-col md="9">
          <b-form-group label="Tên sản phẩm" name="product">
            <b-form-input :value="bom.product_name" readonly></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="3">
          <b-form-group label="Tên định mức">
            <b-form-input :value="bom.name" readonly></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <table class="table table-bordered">
            <b-thead>
              <b-tr>
                <th>STT</th>
                <th>Mã SP</th>
                <th>Tên vật tư</th>
                <th>Số lượng</th>
                <th>Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr v-for="(row, index) in bom.bom_details" :key="row.id">
                <td>{{ index + 1 }}</td>
                <td>{{ row.product_code }}</td>
                <td>
                  {{ row.product_name }}
                </td>
                <td>
                  {{ row.quantity }}
                </td>
                <td>
                  {{ row.note }}
                </td>
              </b-tr>
            </b-tbody>
          </table>
          <router-link
            :to="`/boms/${this.$route.params.id}/edit`"
            class="btn btn-warning"
            >Sửa</router-link
          >
          <b-button variant="primary" @click="onSigned">Ký Nháy</b-button>
          <b-button variant="success" @click="onApproved">Duyệt</b-button>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
import Vue from "vue";
import _ from "lodash";

export default {
  data() {
    return {
      id: null,
      bom: {}
    };
  },
  created() {
    if (this.$route.params.id) {
      this.id = this.$route.params.id;
      this.getBom(this.id);
    }
  },
  computed: {
    count() {
      return this.bom.bom_details.length;
    }
  },
  methods: {
    getBom(id) {
      axios.get(`api/boms/${id}`).then(res => {
        this.bom = res.data.data;
      });
    },
    onSigned() {
      axios.put(`api/boms/${this.id}`, { signed: true }).then(res => {
        if (res.data.data === "success") {
          this.$router.push("/");
        }
      });
    },
    onApproved() {
      axios.put(`api/boms/${this.id}`, { approved: true }).then(res => {
        if (res.data.data === "success") {
          this.$router.push("/");
        }
      });
    }
  }
};
</script>
<style lang="scss">
[name="product_id"] {
  .vs__dropdown-menu {
    width: 500px;
  }
}
</style>
<style src="./Create.scss" lang="scss" scoped />
