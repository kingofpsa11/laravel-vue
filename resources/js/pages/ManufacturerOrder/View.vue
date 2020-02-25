<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Lệnh sản xuất</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Lệnh sản xuất - <span class="fw-semi-bold">Xem</span>
    </h2>
    <b-form>
      <b-row>
        <b-col>
          <b-form-group label="Đơn vị đặt hàng" name="customer">
            <b-form-input
              :value="manufacturer_order.customer_name"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số đơn hàng">
            <b-form-input
              :value="manufacturer_order.manufacturer_order_number"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Số đơn hàng">
            <b-form-input
              :value="manufacturer_order.contract_number"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày đặt hàng">
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              :value="manufacturer_order.date"
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
                <th>Tiến độ</th>
                <th>Trạng thái</th>
                <th>Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr
                v-for="(row,
                index) in manufacturer_order.manufacturer_order_details"
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
                <td v-mask="{ alias: 'dd/mm/yyyy' }">
                  {{ row.deadline }}
                </td>
                <td></td>
                <td>
                  {{ row.note }}
                </td>
              </b-tr>
            </b-tbody>
          </table>
          <b-button variant="primary" @click="onReceived"
            >Nhận lệnh sản xuất</b-button
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
    if (this.$route.params.id) this.getManufacturer(this.$route.params.id);
    this.id = this.$route.params.id;
  },
  data() {
    return {
      id: null,
      manufacturer_order: {}
    };
  },
  computed: {
    count() {
      return this.manufacturer_order.manufacturer_order_details.length;
    }
  },
  methods: {
    getManufacturer(id) {
      axios.get(`api/manufacturer-orders/${id}`).then(res => {
        this.manufacturer_order = res.data.data;
      });
    },
    onReceived() {
      axios
        .post(`api/assignments`, {
          manufacturer_order_id: this.id
        })
        .then(res => {
          console.log(res);
          if (res.data.status === "success") {
            this.$router.push(`/assignments/${res.data.id}`);
          }
        });
    }
  }
};
</script>
<style src="./Create.scss" lang="scss" scoped />
