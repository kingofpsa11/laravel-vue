<template>
  <nav
    :class="{ sidebar: true, sidebarStatic, sidebarOpened }"
    @mouseenter="sidebarMouseEnter"
    @mouseleave="sidebarMouseLeave"
  >
    <header class="logo">
      <router-link to="/app"
        ><span class="text-warning">Sing</span> App</router-link
      >
    </header>
    <ul class="nav">
      <NavLink
        header="Dashboard"
        link="/dashboard"
        iconName="flaticon-home"
        index="dashboard"
        isHeader
      />
      <NavLink
        :activeItem="activeItem"
        header="Định mức"
        link="/boms"
        iconName="flaticon-list"
        index="boms"
        :childrenLinks="[
          { header: 'Tạo mới', link: '/boms/create' },
          { header: 'Danh sách', link: '/boms/list' }
        ]"
      />
      <NavLink
        :activeItem="activeItem"
        header="Sản phẩm"
        link="/products"
        iconName="flaticon-list"
        index="products"
        :childrenLinks="[
          { header: 'Tạo mới', link: '/products/create' },
          { header: 'Danh sách', link: '/products/list' }
        ]"
      />
      <NavLink
        :activeItem="activeItem"
        header="Giá"
        link="/prices"
        iconName="flaticon-list"
        index="prices"
        :childrenLinks="[
          { header: 'Tạo mới', link: '/prices/create' },
          { header: 'Danh sách', link: '/prices/list' }
        ]"
      />
      <NavLink
        :activeItem="activeItem"
        header="Đơn hàng"
        link="/contracts"
        iconName="flaticon-list"
        index="contracts"
        :childrenLinks="[
          { header: 'Tạo mới', link: '/contracts/create' },
          { header: 'Danh sách', link: '/contracts/list' }
        ]"
      />
      <NavLink
        header="Lệnh sản xuất"
        link="/manufacturer-orders/list"
        iconName="flaticon-home"
        index="manufacturer-orders"
        isHeader
      />
      <NavLink
        :activeItem="activeItem"
        header="Phân xưởng"
        link="/factories"
        iconName="flaticon-list"
        index="factories"
        :childrenLinks="[
          { header: 'Tạo mới', link: '/factories/create' },
          { header: 'Danh sách', link: '/factories/list' }
        ]"
      />
      <NavLink
        :activeItem="activeItem"
        header="Phiếu giao việc"
        link="/assignments"
        iconName="flaticon-list"
        index="assignments"
        :childrenLinks="[
          { header: 'Tạo mới', link: '/assignments/create' },
          { header: 'Danh sách', link: '/assignments/list' }
        ]"
      />
      <NavLink
        :activeItem="activeItem"
        header="Phiếu xin lĩnh vật tư"
        link="/material-requisitions"
        iconName="flaticon-list"
        index="material-requisitions"
        :childrenLinks="[
          { header: 'Tạo mới', link: '/material-requisitions/create' },
          { header: 'Danh sách', link: '/material-requisitions/list' }
        ]"
      />
      <NavLink
        :activeItem="activeItem"
        header="Phiếu xuất hàng"
        link="/good-deliveries"
        iconName="flaticon-list"
        index="good-deliveries"
        :childrenLinks="[
          { header: 'Tạo mới', link: '/good-deliveries/create' },
          { header: 'Danh sách', link: '/good-deliveries/list' }
        ]"
      />
      <NavLink
        header="Notifications"
        link="/app/notifications"
        iconName="flaticon-star"
        index="notifications"
        isHeader
      />
      <NavLink
        :activeItem="activeItem"
        header="Components"
        link="/app/components"
        iconName="flaticon-network"
        index="components"
        :childrenLinks="[
          { header: 'Charts', link: '/app/components/charts' },
          { header: 'Icons', link: '/app/components/icons' },
          { header: 'Maps', link: '/app/components/maps' }
        ]"
      />
    </ul>
  </nav>
</template>

<script>
import { mapState, mapActions } from "vuex";
import isScreen from "../../core/screenHelper";
import NavLink from "./NavLink/NavLink";

export default {
  name: "Sidebar",
  components: { NavLink },
  data() {
    return {
      alerts: [
        {
          id: 0,
          title: "Sales Report",
          value: 15,
          footer: "Calculating x-axis bias... 65%",
          color: "info"
        },
        {
          id: 1,
          title: "Personal Responsibility",
          value: 20,
          footer: "Provide required notes",
          color: "danger"
        }
      ]
    };
  },
  methods: {
    ...mapActions("layout", ["changeSidebarActive", "switchSidebar"]),
    setActiveByRoute() {
      const paths = this.$route.fullPath.split("/");
      paths.pop();
      this.changeSidebarActive(paths.join("/"));
    },
    sidebarMouseEnter() {
      if (!this.sidebarStatic && (isScreen("lg") || isScreen("xl"))) {
        this.switchSidebar(false);
        this.setActiveByRoute();
      }
    },
    sidebarMouseLeave() {
      if (!this.sidebarStatic && (isScreen("lg") || isScreen("xl"))) {
        this.switchSidebar(true);
        this.changeSidebarActive(null);
      }
    }
  },
  created() {
    this.setActiveByRoute();
  },
  computed: {
    ...mapState("layout", {
      sidebarStatic: state => state.sidebarStatic,
      sidebarOpened: state => !state.sidebarClose,
      activeItem: state => state.sidebarActiveElement
    })
  }
};
</script>

<!-- Sidebar styles should be scoped -->
<style src="./Sidebar.scss" lang="scss" scoped />
