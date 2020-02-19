<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phiếu xuất kho</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phiếu xuất kho - <span class="fw-semi-bold">Xem</span>
    </h2>
    <b-form>
      <b-row>
        <b-col>
          <b-form-group label="Khách Hàng" name="customer">
            <b-form-input
              :value="good_receive.customer_name"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số đơn hàng">
            <b-form-input :value="good_receive.number" readonly></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày lập">
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              :value="good_receive.date"
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
                <th>Kho</th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr
                v-for="(row, index) in good_receive.good_receive_details"
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
                  {{ row.store_name }}
                </td>
                <td>
                  {{ row.note }}
                </td>
              </b-tr>
            </b-tbody>
          </table>
          <router-link :to="`/good-receive/${id}/edit`" class="btn btn-warning"
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
      this.getGoodReceives(this.id);
    }
  },
  data() {
    return {
      id: null,
      good_receive: {}
    };
  },
  computed: {
    count() {
      return this.good_receive.good_receive_details.length;
    }
  },
  methods: {
    getGoodReceives(id) {
      axios.get(`api/good-receive/${id}`).then(res => {
        this.good_receive = res.data.data;
      });
    },
    onApproved() {
      axios.put(`api/good-receive/${this.id}`, { approved: true }).then(res => {
        if (res.data.data === "success") {
          this.$router.push("/");
        }
      });
    }
  }
};
</script>
<style src="./Create.scss" lang="scss" scoped />
