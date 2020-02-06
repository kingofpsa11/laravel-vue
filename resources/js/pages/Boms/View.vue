<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Đơn hàng</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">Đơn hàng - <span class="fw-semi-bold">Xem</span></h2>
    <b-form>
      <b-row>
        <b-col>
          <b-form-group label="Khách Hàng" name="customer">
            <b-form-input
              :value="contract.customer_name"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số đơn hàng">
            <b-form-input :value="contract.number" readonly></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày đặt hàng">
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              :value="contract.date"
              readonly
            />
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Giá trị đơn hàng">
            <b-form-input
              :value="contract.total_value"
              readonly
              v-mask="{
                alias: 'integer',
                groupSeparator: '.',
                autoGroup: true,
                removeMaskOnSubmit: true,
                unmaskAsNumber: true
              }"
            ></b-form-input>
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
                <th>Đơn giá</th>
                <th>Tiến độ</th>
                <th>ĐVSX</th>
                <th>Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr
                v-for="(row, index) in contract.contract_details"
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
                  {{ row.selling_price }}
                </td>
                <td v-mask="{ alias: 'dd/mm/yyyy' }">
                  {{ row.deadline }}
                </td>
                <td>
                  {{ row.supplier_name }}
                </td>
                <td>
                  {{ row.note }}
                </td>
              </b-tr>
            </b-tbody>
          </table>
          <router-link :to="`/contracts/${id}/edit`" class="btn btn-warning"
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
  created() {
    if (this.$route.params.id) this.getContracts(this.$route.params.id);
    this.id = this.$route.params.id;
  },
  data() {
    return {
      id: null,
      contract: {}
    };
  },
  computed: {
    count() {
      return this.contract.contract_details.length;
    }
  },
  methods: {
    getContracts(id) {
      axios.get(`api/contracts/${id}`).then(res => {
        this.contract = res.data.data;
      });
    },
    onSigned() {
      axios
        .put(`api/contracts/${this.$route.params.id}`, { signed: true })
        .then(res => {
          if (res.data.data === "success") {
            this.$router.push("/");
          }
        });
    },
    onApproved() {
      axios
        .put(`api/contracts/${this.$route.params.id}`, { approved: true })
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
