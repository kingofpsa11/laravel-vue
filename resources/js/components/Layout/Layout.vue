<template>
  <div :class="{ root: true, chatOpen, sidebarClose, sidebarStatic }">
    <Sidebar />
    <Helper />
    <div class="wrap">
      <Header />
      <Chat />
      <v-touch
        class="content"
        @swipe="handleSwipe"
        :swipe-options="{ direction: 'horizontal' }"
      >
        <router-view />
        <footer class="contentFooter">
          Sing Vue Version - Made by
          <a
            href="https://flatlogic.com"
            rel="nofollow noopener noreferrer"
            target="_blank"
            >Flatlogic</a
          >
        </footer>
      </v-touch>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

import Sidebar from "../Sidebar/Sidebar";
import Header from "../Header/Header";
import Chat from "../Chat/Chat";
import Helper from "../Helper/Helper";

import "./Layout.scss";

export default {
  name: "Layout",
  components: { Sidebar, Header, Chat, Helper },
  methods: {
    ...mapActions("layout", [
      "switchSidebar",
      "handleSwipe",
      "changeSidebarActive"
    ])
  },
  computed: {
    ...mapState("layout", {
      sidebarClose: state => state.sidebarClose,
      sidebarStatic: state => state.sidebarStatic,
      chatOpen: state => state.chatOpen
    })
  },
  created() {
    const staticSidebar = JSON.parse(localStorage.getItem("sidebarStatic"));

    if (staticSidebar) {
      this.$store.state.layout.sidebarStatic = true;
    } else if (!this.sidebarClose) {
      setTimeout(() => {
        this.switchSidebar(true);
        this.changeSidebarActive(null);
      }, 2500);
    }
  }
};
</script>

<style src="./Layout.scss" lang="scss" />
