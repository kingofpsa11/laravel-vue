import Vue from "vue";
import Router from "vue-router";

import Layout from "./components/Layout/Layout";
import Login from "./pages/Login/Login";
import ErrorPage from "./pages/Error/Error";
// Core
import BomView from "./pages/Boms/View";
import BomCreate from "./pages/Boms/Create";
import BomList from "./pages/Boms/List";

import ContractView from "./pages/Contracts/View";
import ContractCreate from "./pages/Contracts/Create";
import ContractList from "./pages/Contracts/List";

import ManufacturerOrderView from "./pages/ManufacturerOrder/View";
import ManufacturerOrderList from "./pages/ManufacturerOrder/List";

import ProductCreate from "./pages/Products/Create";
import ProductList from "./pages/Products/List";

import PriceCreate from "./pages/Prices/Create";
import PriceList from "./pages/Prices/List";

import AssignmentCreate from "./pages/Assignments/Create";
import AssignmentList from "./pages/Assignments/List";
import AssignmentView from "./pages/Assignments/View";

import FactoryCreate from "./pages/Factories/Create";
import FactoryList from "./pages/Factories/List";
// Tables
import TablesBasicPage from "./pages/Tables/Basic";

// Maps
import GoogleMapPage from "./pages/Maps/Google";

// Main
import AnalyticsPage from "./pages/Dashboard/Dashboard";

// Charts
import ChartsPage from "./pages/Charts/Charts";

// Ui
import IconsPage from "./pages/Icons/Icons";
import NotificationsPage from "./pages/Notifications/Notifications";

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: "/login",
      name: "Login",
      component: Login
    },
    {
      path: "/error",
      name: "Error",
      component: ErrorPage
    },
    {
      path: "/",
      name: "Layout",
      component: Layout,
      children: [
        {
          path: "dashboard",
          name: "AnalyticsPage",
          component: AnalyticsPage
        },
        {
          path: "products/create",
          name: "ProductCreate",
          component: ProductCreate
        },
        {
          path: "products/list",
          name: "ProductList",
          component: ProductList
        },
        {
          path: "prices/create",
          name: "PriceCreate",
          component: PriceCreate
        },
        {
          path: "prices/list",
          name: "Price",
          component: PriceList
        },
        {
          path: "boms/create",
          name: "BomCreate",
          component: BomCreate
        },
        {
          path: "boms/list",
          name: "BomList",
          component: BomList
        },
        {
          path: "boms/:id",
          name: "BomView",
          component: BomView
        },
        {
          path: "boms/:id/edit",
          name: "BomEdit",
          component: BomCreate
        },
        {
          path: "contracts/create",
          name: "ContractCreate",
          component: ContractCreate
        },
        {
          path: "contracts/list",
          name: "ContractList",
          component: ContractList
        },
        {
          path: "contracts/:id",
          name: "ContractView",
          component: ContractView
        },
        {
          path: "contracts/:id/edit",
          name: "ContractEdit",
          component: ContractCreate
        },
        {
          path: "manufacturer-orders/list",
          name: "ManufacturerOrderList",
          component: ManufacturerOrderList
        },
        {
          path: "manufacturer-orders/:id",
          name: "ManufacturerOrderView",
          component: ManufacturerOrderView
        },
        {
          path: "factories/create",
          name: "FactoryCreate",
          component: FactoryCreate
        },
        {
          path: "factories/list",
          name: "FactoryList",
          component: FactoryList
        },
        {
          path: "assignments/create",
          name: "AssignmentCreate",
          component: AssignmentCreate
        },
        {
          path: "assignments/list",
          name: "AssignmentList",
          component: AssignmentList
        },
        {
          path: "assignments/:id",
          name: "AssignmentView",
          component: AssignmentView
        },
        {
          path: "assignments/:id/edit",
          name: "AssignmentEdit",
          component: AssignmentCreate
        },
        {
          path: "components/icons",
          name: "IconsPage",
          component: IconsPage
        },
        {
          path: "notifications",
          name: "NotificationsPage",
          component: NotificationsPage
        },
        {
          path: "components/charts",
          name: "ChartsPage",
          component: ChartsPage
        },
        {
          path: "tables",
          name: "TablesBasicPage",
          component: TablesBasicPage
        },
        {
          path: "components/maps",
          name: "GoogleMapPage",
          component: GoogleMapPage
        }
      ]
    }
  ]
});
