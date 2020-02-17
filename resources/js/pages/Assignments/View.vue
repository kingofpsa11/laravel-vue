<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phiếu giao việc</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phiếu giao việc - <span class="fw-semi-bold">Xem</span>
    </h2>
    <b-form>
      <b-row>
        <b-col>
          <b-form-group label="Đơn vị" name="factory">
            <b-form-input
              :value="assignment.factory_name"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số phiếu giao việc">
            <b-form-input :value="assignment.number" readonly></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày đặt hàng">
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              :value="assignment.date"
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
                <th>LSX</th>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Tiến độ</th>
                <th>Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr
                v-for="(row, index) in assignment.assignment_details"
                :key="row.id"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ row.manufacturer_order_number }}</td>
                <td>{{ row.product_code }}</td>
                <td>
                  {{ row.product_name }}
                </td>
                <td>
                  {{ row.quantity }}
                </td>
                <td v-mask="{ alias: 'dd/mm/yyyy' }">
                  {{ row.deadline }}
                </td>
                <td>
                  {{ row.note }}
                </td>
              </b-tr>
            </b-tbody>
          </table>
          <router-link :to="`/assignments/${id}/edit`" class="btn btn-warning"
            >Sửa</router-link
          >
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
      this.getAssignments(this.id);
    }
  },
  data() {
    return {
      id: null,
      assignment: {}
    };
  },
  computed: {
    count() {
      return this.assignment.assignment_details.length;
    }
  },
  methods: {
    getAssignments(id) {
      axios.get(`api/assignments/${id}`).then(res => {
        this.assignment = res.data.data;
      });
    }
  }
};
</script>
<style src="./Create.scss" lang="scss" scoped />
