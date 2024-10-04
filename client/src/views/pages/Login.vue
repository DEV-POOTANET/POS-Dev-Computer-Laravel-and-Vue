<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth'
import logo from '@/assets/images/logo.png';

const email = ref('');
const password = ref('');
const isLoading = ref(false);
const errorMessage = ref('');
const router = useRouter();
const authStore = useAuthStore()

const login = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    await authStore.login(email.value, password.value)
    router.push('/dashboard')
  } catch (error) {
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message // แสดงข้อความ error ที่ได้รับจาก API
    } else {
      errorMessage.value = 'เกิดข้อผิดพลาดบางอย่าง' // ถ้าไม่ได้รับข้อความจาก API
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="wrapper min-vh-100 d-flex flex-row align-items-center">
    <CContainer>
      <CRow class="justify-content-center">
        <CCol :md="8">
          <CCardGroup>
            <CCard class="p-4">
              <img :src="logo" alt="Logo" class="sidebar-logo" />
              <CCardBody>
                <CForm @submit.prevent="login">
                  <h1>Login</h1>
                  <p class="text-body-secondary">Sign In to your account</p>

                  <!--error เดิม -->
                  <!-- <div v-if="errorMessage" class="alert alert-danger">
                    {{ errorMessage }}
                  </div> -->

                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput
                      v-model="email"
                      placeholder="Email"
                      autocomplete="email"
                      required
                    />
                  </CInputGroup>

                  <CInputGroup class="mb-4">
                    <CInputGroupText>
                      <CIcon icon="cil-lock-locked" />
                    </CInputGroupText>
                    <CFormInput
                      type="password"
                      v-model="password"
                      placeholder="Password"
                      autocomplete="current-password"
                      required
                    />
                  </CInputGroup>

                  <CRow>
                    <CCol :xs="6">
                      <CButton color="primary" class="px-4" type="submit" :disabled="isLoading">
                        <span v-if="isLoading">
                          <CSpinner size="sm" /> Loading...
                        </span>
                        <span v-else>Login</span>
                      </CButton>
                    </CCol>
                  </CRow>
                </CForm>
              </CCardBody>
            </CCard>
          </CCardGroup>
        </CCol>
      </CRow>
    </CContainer>

    <!-- Toaster แสดง error -->
    <CToaster class="p-3" placement="top-end">
      <CToast v-if="errorMessage" visible autohide delay="5000" class="bg-danger text-white">
        <CToastHeader closeButton class="bg-danger text-white">
          <span class="me-auto fw-bold">Error</span>
        </CToastHeader>
        <CToastBody>{{ errorMessage }}</CToastBody>
      </CToast>
    </CToaster>
  </div>
</template>

<style scoped>

</style>
