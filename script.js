// ====================================================
// Ah Master Wash Cafe - interaksi halaman
// Catatan: ini baru sisi tampilan (frontend). Proses login,
// daftar, dan OTP masih simulasi; sambungkan ke backend/API
// pada bagian yang bertanda "TODO".
// ====================================================
 
const state = {
  role: null,      // "pelanggan" atau "kasir"
  contact: "",     // email / no. HP yang didaftarkan
};
 
const screenStack = ["welcome"]; // untuk tombol kembali
const OTP_LENGTH = 6;
const RESEND_SECONDS = 30;
 
// ---------- Helper ----------
const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => document.querySelectorAll(selector);
 
function show(name, { push = true } = {}) {
  $$(".screen").forEach((s) => s.classList.remove("active"));
  $("#screen-" + name).classList.add("active");
  if (push) screenStack.push(name);
  window.scrollTo(0, 0);
 
  if (name === "otp") startOtp();
  else stopTimer();
}
 
function goBack() {
  if (screenStack.length > 1) {
    screenStack.pop();
    show(screenStack[screenStack.length - 1], { push: false });
  }
}
 
let toastTimer;
function toast(message) {
  const el = $("#toast");
  el.textContent = message;
  el.classList.add("show");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove("show"), 2600);
}
 
const isEmail = (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
const isPhone = (v) => /^(\+62|62|0)8\d{7,12}$/.test(v.replace(/[\s-]/g, ""));
 
// ---------- Navigasi ----------
$$(".role-card").forEach((card) => {
  card.addEventListener("click", () => {
    state.role = card.dataset.role;
    show("login");
  });
});
 
$$("[data-back]").forEach((btn) => btn.addEventListener("click", goBack));
 
$$("[data-go]").forEach((link) => {
  link.addEventListener("click", (e) => {
    e.preventDefault();
    show(link.dataset.go);
  });
});
 
$("#forgot-link").addEventListener("click", (e) => {
  e.preventDefault();
  toast("Fitur lupa kata sandi belum tersedia");
});
 
// ---------- Login ----------
$("#form-login").addEventListener("submit", (e) => {
  e.preventDefault();
  const id = $("#login-id").value.trim();
  const pass = $("#login-pass").value;
  const error = $("#login-error");
 
  if (!id || !pass) {
    error.textContent = "Email/No.HP dan kata sandi wajib diisi.";
    return;
  }
  if (!isEmail(id) && !isPhone(id)) {
    error.textContent = "Format email atau nomor HP tidak valid.";
    return;
  }
  error.textContent = "";
 
  // TODO: kirim ke API login, lalu arahkan ke halaman utama sesuai peran
  toast("Login berhasil sebagai " + (state.role || "pelanggan") + " (simulasi)");
});
 
// ---------- Daftar ----------
$("#form-register").addEventListener("submit", (e) => {
  e.preventDefault();
  const name = $("#reg-name").value.trim();
  const id = $("#reg-id").value.trim();
  const pass = $("#reg-pass").value;
  const pass2 = $("#reg-pass2").value;
  const error = $("#reg-error");
 
  if (!name || !id || !pass || !pass2) {
    error.textContent = "Semua kolom wajib diisi.";
    return;
  }
  if (!isEmail(id) && !isPhone(id)) {
    error.textContent = "Format email atau nomor HP tidak valid.";
    return;
  }
  if (pass.length < 6) {
    error.textContent = "Kata sandi minimal 6 karakter.";
    return;
  }
  if (pass !== pass2) {
    error.textContent = "Konfirmasi kata sandi tidak sama.";
    return;
  }
  error.textContent = "";
 
  // TODO: kirim data pendaftaran ke API, server mengirim kode OTP
  state.contact = id;
  show("otp");
});
 
// ---------- OTP ----------
const otpInputs = Array.from($$("#otp-boxes input"));
let timerId = null;
 
function maskContact(value) {
  if (isEmail(value)) return value;
  const digits = value.replace(/\D/g, "");
  return digits.slice(0, 4) + "x".repeat(Math.max(digits.length - 4, 6));
}
 
function startOtp() {
  const viaEmail = isEmail(state.contact);
  $("#otp-via").textContent = viaEmail ? "email" : "nomor";
  $("#otp-target").textContent = maskContact(state.contact) || "nama@gmail.com";
 
  otpInputs.forEach((i) => (i.value = ""));
  $("#otp-boxes").classList.remove("error");
  $("#otp-error").textContent = "";
  otpInputs[0].focus();
  startTimer();
}
 
function formatTime(sec) {
  const m = String(Math.floor(sec / 60)).padStart(2, "0");
  const s = String(sec % 60).padStart(2, "0");
  return m + ":" + s;
}
 
function stopTimer() {
  clearInterval(timerId);
  timerId = null;
}
 
function startTimer() {
  stopTimer();
  let left = RESEND_SECONDS;
  const resend = $("#resend");
  resend.innerHTML = 'Kirim ulang dalam <strong id="timer">' + formatTime(left) + "</strong>";
 
  timerId = setInterval(() => {
    left -= 1;
    if (left > 0) {
      $("#timer").textContent = formatTime(left);
    } else {
      stopTimer();
      resend.innerHTML = '<button type="button" id="btn-resend">Kirim ulang kode</button>';
      $("#btn-resend").addEventListener("click", () => {
        // TODO: minta server mengirim ulang kode OTP
        toast("Kode baru telah dikirim");
        startTimer();
      });
    }
  }, 1000);
}
 
otpInputs.forEach((input, index) => {
  input.addEventListener("input", () => {
    input.value = input.value.replace(/\D/g, "").slice(0, 1); // hanya angka
    $("#otp-boxes").classList.remove("error");
    $("#otp-error").textContent = "";
    if (input.value && index < OTP_LENGTH - 1) otpInputs[index + 1].focus();
  });
 
  input.addEventListener("keydown", (e) => {
    if (e.key === "Backspace" && !input.value && index > 0) otpInputs[index - 1].focus();
    if (e.key === "ArrowLeft" && index > 0) otpInputs[index - 1].focus();
    if (e.key === "ArrowRight" && index < OTP_LENGTH - 1) otpInputs[index + 1].focus();
    if (e.key === "Enter") $("#btn-verify").click();
  });
 
  // Tempel (paste) 6 digit sekaligus
  input.addEventListener("paste", (e) => {
    e.preventDefault();
    const digits = (e.clipboardData.getData("text") || "").replace(/\D/g, "").slice(0, OTP_LENGTH);
    digits.split("").forEach((d, i) => (otpInputs[i].value = d));
    otpInputs[Math.min(digits.length, OTP_LENGTH - 1)].focus();
  });
});
 
$("#btn-verify").addEventListener("click", () => {
  const code = otpInputs.map((i) => i.value).join("");
 
  if (code.length < OTP_LENGTH) {
    $("#otp-boxes").classList.add("error");
    $("#otp-error").textContent = "Masukkan 6 digit kode verifikasi.";
    return;
  }
 
  // TODO: kirim `code` ke API untuk diverifikasi.
  // Sementara, semua kode 6 digit dianggap benar.
  show("success");
});
 
// ---------- Sukses ----------
$("#btn-to-login").addEventListener("click", () => {
  screenStack.length = 0;
  screenStack.push("welcome");
  show("login");
});
 
$("#btn-order").addEventListener("click", () => {
  // TODO: arahkan ke halaman menu/pemesanan
  toast("Halaman menu belum dibuat");
});