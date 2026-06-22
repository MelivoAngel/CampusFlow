<script setup lang="ts">
import Sidebar from '../components/Sidebar.vue'
</script>

<template>
  <div class="app-container">

    <aside>
      <Sidebar />
    </aside>

    <div class="main-content">

      <header>
        Navbar
      </header>

      <main>

        <slot />

      </main>

    </div>

  </div>
</template>

<style scoped>

.app-container {
  display: flex;
  min-height: 100vh;
}

aside {
  width: 250px;
  border-right: 1px solid #ddd;
  padding: 20px;
}

.main-content {
  flex: 1;
}

header {
  padding: 20px;
  border-bottom: 1px solid #ddd;
}

main {
  padding: 20px;
}

</style>