<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phiếu xin lĩnh vật tư</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phiếu xin lĩnh vật tư - <span class="fw-semi-bold">Xem</span>
    </h2>
    <b-form>
      <b-row>
        <b-col>
          <b-form-group label="Đơn vị" name="department">
            <b-form-input
              :value="material_requisition.department_name"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số đơn hàng">
            <b-form-input
              :value="material_requisition.number"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày lập">
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              :value="material_requisition.date"
              readonly
            />
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
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr
                v-for="(row,
                index) in material_requisition.material_requisition_details"
                :key="row.id"
              >
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
            :to="`/material-requisitions/${id}/edit`"
            class="btn btn-warning"
            >Sửa</router-link
          >
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
  created() {
    if (this.$route.params.id) {
      this.id = this.$route.params.id;
      this.getMaterialRequisition(this.id);
    }
  },
  data() {
    return {
      id: null,
      material_requisition: {}
    };
  },
  computed: {
    count() {
      return this.material_requisition.material_requisition_details.length;
    }
  },
  methods: {
    getMaterialRequisition(id) {
      axios.get(`api/material-requisitions/${id}`).then(res => {
        this.material_requisition = res.data.data;
      });
    },
    onApproved() {
      axios
        .put(`api/material-requisitions/${this.id}`, { approved: true })
        .then(res => {
          if (res.data.data === "success") {
            this.$router.push("/");
          }
        });
    }
  }
};
</script>
<style src="./Create.scss" lang="scss" scoped />
